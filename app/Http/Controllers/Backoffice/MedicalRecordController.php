<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\BloodTest;
use App\Models\Employee;
use App\Models\MedicalVisit;
use App\Support\StreamingXlsxWriter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MedicalRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if ($user && ($user->isAdmin() || $user->isMedecin() || $user->isCh())) {
                return $next($request);
            }
            abort(403, 'Accès réservé à l’administrateur');
        });
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $search = $request->string('q')->trim()->value();

        [$medicalTable, $qhseTable] = $this->resolveTables();

        $query = MedicalVisit::query()
            ->from("{$medicalTable} as medical_visits")
            ->with([
                'employee',
                'employee.bloodTests',
                'employee.qhse' => function ($builder) use ($qhseTable) {
                    $builder->from("{$qhseTable} as medical_visit_qhses");
                },
            ])
            ->latest('medical_visits.created_at');

        if ($search !== '') {
            $query->whereHas('employee', function ($builder) use ($search) {
                $builder->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $visits = $query->paginate(15)->withQueryString();
        $bloodTestsByEmployee = BloodTest::query()
            ->whereIn('employee_id', $visits->getCollection()->pluck('employee_id')->filter()->unique())
            ->latest()
            ->get()
            ->groupBy('employee_id');

        return view('backoffice.medical_records.index', [
            'user' => $user,
            'visits' => $visits,
            'search' => $search,
            'bloodTestsByEmployee' => $bloodTestsByEmployee,
        ]);
    }

    public function show(Request $request, $medicalVisit)
    {
        [$medicalTable, $qhseTable] = $this->resolveTables();

        $medicalVisit = MedicalVisit::query()
            ->from("{$medicalTable} as medical_visits")
            ->with([
                'employee',
                'employee.qhse' => function ($builder) use ($qhseTable) {
                    $builder->from("{$qhseTable} as medical_visit_qhses");
                },
            ])
            ->findOrFail($medicalVisit);

        return view('backoffice.medical_records.show', [
            'user' => $request->user(),
            'visit' => $medicalVisit,
        ]);
    }

    public function export(Request $request)
    {
        $request->validate(['format' => 'nullable|in:medical,dch']);
        // Keep the PHP time limit from interrupting a large disk-based export.
        set_time_limit(0);
        DB::connection()->disableQueryLog();
        $user = $request->user();
        $search = $request->string('q')->trim()->value();
        [$medicalTable, $qhseTable] = $this->resolveTables();
        $format = $request->input('format', 'medical');
        $canExportMedicalSheet = (bool) ($user?->isMedecin() || $user?->isAdmin());
        $sheets = [];

        if ($format === 'dch') {
            $employees = Employee::query()->select('employees.*')
                ->selectSub(DB::table($medicalTable)->selectRaw('MAX(created_at)')
                    ->whereColumn('employee_id', 'employees.id'), 'last_visit_at')
                ->selectSub(DB::table($medicalTable)->selectRaw('COUNT(*)')
                    ->whereColumn('employee_id', 'employees.id'), 'visit_count');
            if ($search !== '') {
                $employees->where(function ($builder) use ($search) {
                    $builder->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('matricule', 'like', "%{$search}%");
                });
            }
            $sheets[] = [
                'title' => 'DCH',
                'headers' => ['MATRICULE', 'AGENT', 'AGE', 'SEXE', 'EMPLOI OCCUPE', 'DIRECTION',
                    'DELEGATION REGIONALE', 'DELEGATION DEPARTEMENTALE / SERVICE', 'UNITE COMMUNALE',
                    'VISITE MEDICALE', 'DATE VISITE'],
                'rows' => $employees->lazyById(500)->map(fn ($employee) => [
                    $this->normalizeCsvValue($employee->matricule),
                    $this->employeeFullName($employee),
                    $this->normalizeCsvValue($employee->age),
                    $this->normalizeCsvValue($employee->sexe),
                    $this->normalizeCsvValue($employee->emploi_occupe),
                    $this->normalizeCsvValue($employee->direction),
                    $this->normalizeCsvValue($employee->delegation_r),
                    $this->normalizeCsvValue($employee->service),
                    $this->normalizeCsvValue($employee->unite_communale),
                    $employee->visit_count > 0 ? 'OUI' : 'NON',
                    $this->formatDateValue($employee->last_visit_at),
                ]),
            ];
        } else {
            $query = MedicalVisit::query()->from("{$medicalTable} as medical_visits")
                ->select('medical_visits.*');
            if ($search !== '') {
                $query->whereHas('employee', function ($builder) use ($search) {
                    $builder->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('matricule', 'like', "%{$search}%");
                });
            }
            if ($canExportMedicalSheet) {
                $medicalQuery = (clone $query)->with(['employee', 'employee.bloodTests' => fn ($builder) => $builder->limit(1)]);
                $sheets[] = ['title' => 'Donnees medicales', 'headers' => $this->medicalHeaders(),
                    'rows' => $medicalQuery->orderByDesc('medical_visits.created_at')->orderByDesc('medical_visits.id')
                        ->lazy(500)->map(fn ($visit) => $this->medicalRow($visit))];
            }
            $qhseQuery = (clone $query)->with(['employee', 'employee.qhse' => function ($builder) use ($qhseTable) {
                $builder->from("{$qhseTable} as medical_visit_qhses");
            }]);
            $sheets[] = ['title' => 'Donnees QHSE', 'headers' => $this->qhseHeaders(),
                'rows' => $qhseQuery->orderByDesc('medical_visits.created_at')->orderByDesc('medical_visits.id')
                    ->lazy(500)->map(fn ($visit) => $this->qhseRow($visit))];
        }

        $generatedAt = now();
        $path = (new StreamingXlsxWriter)->write($sheets, [
            'reference' => (string) Str::uuid(),
            'generated_at' => $generatedAt->toIso8601String(),
            'generated_by' => $user?->name ?: $user?->email ?: 'Utilisateur VMAP',
        ]);
        $filename = ($format === 'dch' ? 'dch-' : 'fiches-medicales-').$generatedAt->format('Ymd-His').'.xlsx';

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function medicalHeaders(): array
    {
        return [
            'MATRICULE',
            'AGENT',
            'SEXE',
            'AGE',
            'ANCIENNETE (ANS)',
            'EMPLOI OCCUPE',
            'DIRECTION',
            'DELEGATION REGIONALE',
            'DELEGATION DEPARTEMENTALE / SERVICE',
            'UNITE COMMUNALE',
            'ANTECEDENTS',
            'ANTECEDENTS AUTRES',
            'TAILLE',
            'POIDS',
            'IMC',
            'TENSION',
            'STRESS',
            'SOMMEIL',
            'CHARGE DE TRAVAIL',
            'SOUTIEN',
            'AVIS',
            'OBSERVATIONS',
            'DATE DE VISITE',
            'DATE DERNIER BILAN BIOLOGIQUE',
            'UREE (G/L)',
            'CREAT (MG/L)',
            'ASAT (UI/L)',
            'ALAT (UI/L)',
            'AGHBS',
            'CHOL TOT (G/L)',
            'TG (G/L)',
            'GAJ (G/L)',
            'HB (G/DL)',
            'HCT (%)',
            'GB (10^9/L)',
            'PLT (10^9/L)',
        ];
    }

    private function qhseHeaders(): array
    {
        return [
            'MATRICULE',
            'AGENT',
            'SEXE',
            'AGE',
            'ANCIENNETE (ANS)',
            'EMPLOI OCCUPE',
            'DIRECTION',
            'DELEGATION REGIONALE',
            'DELEGATION DEPARTEMENTALE / SERVICE',
            'UNITE COMMUNALE',
            'CONTRAINTE MANUTENTION',
            'MANUTENTION FREQUENCE',
            'MANUTENTION PRECISION',
            'CONTRAINTE POSTURES',
            'POSTURES PENIBILITE',
            'NUISANCES PHYSIQUES',
            'NUISANCES CHIMIQUES',
            'RISQUES MECANIQUES',
            'ORGANISATION TRAVAIL',
            'EPI DISPONIBILITE',
            'EPI UTILISATION',
            'EPI DIFFICULTES',
            'EPI AUTRES',
            'FORMATION SST',
            'APPRECIATION POSTE',
            'OBSERVATIONS QHSE',
            'SYNTHESE RISQUE',
            'SYNTHESE FACTEURS',
            'SYNTHESE ACTIONS',
            'DATE DE VISITE',
        ];
    }

    private function baseEmployeeData($employee): array
    {
        return [
            $this->normalizeCsvValue($employee?->matricule),
            $this->employeeFullName($employee),
            $this->normalizeCsvValue($employee?->sexe),
            $this->normalizeCsvValue($employee?->age),
            $this->employeeSeniority($employee?->date_embauche),
            $this->normalizeCsvValue($employee?->emploi_occupe),
            $this->normalizeCsvValue($employee?->direction),
            $this->normalizeCsvValue($employee?->delegation_r),
            $this->normalizeCsvValue($employee?->service),
            $this->normalizeCsvValue($employee?->unite_communale),
        ];
    }

    private function medicalRow(MedicalVisit $visit): array
    {
        $employee = $visit->employee;
        $bloodTest = $employee?->bloodTests->first();

        return array_merge(
            $this->baseEmployeeData($employee),
            [
                $this->normalizeCsvValue($visit->antecedents),
                $this->normalizeCsvValue($visit->antecedents_precisions),
                $this->normalizeCsvValue($visit->taille),
                $this->normalizeCsvValue($visit->poids),
                $this->normalizeCsvValue($visit->imc),
                $this->normalizeCsvValue($visit->tension),
                $this->normalizeCsvValue($visit->stress),
                $this->normalizeCsvValue($visit->sommeil),
                $this->normalizeCsvValue($visit->charge_travail),
                $this->normalizeCsvValue($visit->soutien),
                $this->normalizeCsvValue($visit->avis),
                $this->normalizeCsvValue($visit->observations),
                $this->formatDateValue($visit->created_at),
                $this->formatDateTimeValue($bloodTest?->created_at),
                $this->normalizeCsvValue($bloodTest?->uree),
                $this->normalizeCsvValue($bloodTest?->creat),
                $this->normalizeCsvValue($bloodTest?->asat),
                $this->normalizeCsvValue($bloodTest?->alat),
                $this->normalizeCsvValue($bloodTest?->aghbs),
                $this->normalizeCsvValue($bloodTest?->chol),
                $this->normalizeCsvValue($bloodTest?->tg),
                $this->normalizeCsvValue($bloodTest?->gaj),
                $this->normalizeCsvValue($bloodTest?->hb),
                $this->normalizeCsvValue($bloodTest?->hct),
                $this->normalizeCsvValue($bloodTest?->gb),
                $this->normalizeCsvValue($bloodTest?->plt),
            ]
        );
    }

    private function qhseRow(MedicalVisit $visit): array
    {
        $employee = $visit->employee;
        $qhse = $employee?->qhse;

        return array_merge(
            $this->baseEmployeeData($employee),
            [
                $this->normalizeCsvValue($qhse?->contrainte_manutention),
                $this->normalizeCsvValue($qhse?->manutention_frequence),
                $this->normalizeCsvValue($qhse?->manutention_precision),
                $this->normalizeCsvValue($qhse?->contrainte_postures),
                $this->normalizeCsvValue($qhse?->postures_penibilite),
                $this->normalizeCsvValue($qhse?->nuisances_physiques),
                $this->normalizeCsvValue($qhse?->nuisances_chimiques),
                $this->normalizeCsvValue($qhse?->risques_mecaniques),
                $this->normalizeCsvValue($qhse?->organisation_travail),
                $this->normalizeCsvValue($qhse?->epi_disponibilite),
                $this->normalizeCsvValue($qhse?->epi_utilisation),
                $this->normalizeCsvValue($qhse?->epi_difficultes),
                $this->normalizeCsvValue($qhse?->epi_autres),
                $this->normalizeCsvValue($qhse?->formation_sst),
                $this->normalizeCsvValue($qhse?->appreciation_poste),
                $this->normalizeCsvValue($qhse?->observations_qhse),
                $this->normalizeCsvValue($qhse?->synthese_risque),
                $this->normalizeCsvValue($qhse?->synthese_facteurs),
                $this->normalizeCsvValue($qhse?->synthese_actions),
                $this->formatDateValue($visit->created_at),
            ]
        );
    }

    private function employeeFullName($employee): string
    {
        if (! $employee) {
            return '';
        }

        return trim(($employee->prenom ?? '').' '.($employee->nom ?? ''));
    }

    private function employeeAge($dateNaissance): string
    {
        if (empty($dateNaissance)) {
            return '';
        }

        try {
            return (string) now()->diffInYears($dateNaissance);
        } catch (\Throwable $exception) {
            return '';
        }
    }

    private function employeeSeniority($dateEmbauche): string
    {
        if (empty($dateEmbauche)) {
            return '';
        }

        try {
            return $this->normalizeCsvValue((int) Carbon::parse($dateEmbauche)->diffInYears(Carbon::today()));
        } catch (\Throwable $exception) {
            return '';
        }
    }

    private function normalizeCsvValue($value): string
    {
        if (is_array($value)) {
            $parts = $this->flattenValues($value);

            return implode(' - ', $parts);
        }

        if (is_object($value)) {
            if ($value instanceof \JsonSerializable) {
                $value = $value->jsonSerialize();
            } else {
                $value = (array) $value;
            }

            $parts = $this->flattenValues((array) $value);

            return implode(' - ', $parts);
        }

        if ($value === null) {
            return '';
        }

        $text = (string) $value;

        if ($text === '') {
            return '';
        }

        if (! preg_match('//u', $text)) {
            if (function_exists('mb_convert_encoding')) {
                $text = mb_convert_encoding($text, 'UTF-8', 'auto');
            } elseif (function_exists('iconv')) {
                $converted = iconv('ISO-8859-1', 'UTF-8//IGNORE', $text);
                $text = $converted === false ? $text : $converted;
            }
        }

        return $text;
    }

    private function flattenValues(array $values): array
    {
        $flat = [];

        foreach ($values as $value) {
            if (is_array($value)) {
                $flat = array_merge($flat, $this->flattenValues($value));

                continue;
            }

            if (is_object($value)) {
                $flat = array_merge($flat, $this->flattenValues((array) $value));

                continue;
            }

            if ($value === null) {
                continue;
            }

            $text = trim((string) $value);
            if ($text !== '') {
                $flat[] = $text;
            }
        }

        return $flat;
    }

    private function formatDateValue($value): string
    {
        if (empty($value)) {
            return '';
        }

        try {
            return \Illuminate\Support\Carbon::parse($value)->format('d/m/Y');
        } catch (\Throwable $exception) {
            return $this->normalizeCsvValue($value);
        }
    }

    private function formatDateTimeValue($value): string
    {
        if (empty($value)) {
            return '';
        }

        try {
            return \Illuminate\Support\Carbon::parse($value)->format('Y-m-d H:i');
        } catch (\Throwable $exception) {
            return $this->normalizeCsvValue($value);
        }
    }

    private function resolveTables(): array
    {
        $medicalTable = Schema::hasTable('visitemedicale') ? 'visitemedicale' : 'medical_visits';
        $qhseTable = Schema::hasTable('visitemedicalqhse') ? 'visitemedicalqhse' : 'medical_visit_qhses';

        return [$medicalTable, $qhseTable];
    }
}
