<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\MedicalVisitQhse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MedicalVisitQhseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (!auth()->check()) {
                abort(403, 'Accès réservé au rôle autorisé !!!!');
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('medical_visits.qhse.index', [
            'visits' => [],
        ]);
    }

    public function edit(Employee $employee)
    {
        $qhse = MedicalVisitQhse::firstOrNew([
            'employee_id' => $employee->id,
        ]);

        return view('medical_visits.qhse.form', [
            'qhse' => $qhse,
            'employee' => $employee,
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'qhse_manutention' => 'array|nullable',
            'qhse_manutention_frequence' => 'nullable|string',
            'qhse_manutention_precision' => 'nullable|string',
            'qhse_postures' => 'array|nullable',
            'qhse_postures_penibilite' => 'nullable|string',
            'qhse_nuisances_physiques' => 'array|nullable',
            'qhse_nuisances_chimiques' => 'array|nullable',
            'qhse_risques' => 'array|nullable',
            'qhse_organisation' => 'array|nullable',
            'qhse_epi_dispo' => 'array|nullable',
            'qhse_epi_utilisation' => 'nullable|string',
            'qhse_epi_difficulte' => 'array|nullable',
            'qhse_epi_autres' => 'nullable|string',
            'qhse_formation' => 'array|nullable',
            'qhse_appreciation' => 'nullable|string',
            'qhse_observations' => 'nullable|string',
            'qhse_synthese_risque' => 'nullable|string',
            'qhse_synthese_facteurs' => 'array|nullable',
            'qhse_synthese_actions' => 'array|nullable',
        ]);

        MedicalVisitQhse::updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'contrainte_manutention' => $data['qhse_manutention'] ?? null,
                'manutention_frequence' => $data['qhse_manutention_frequence'] ?? null,
                'manutention_precision' => $data['qhse_manutention_precision'] ?? null,
                'contrainte_postures' => $data['qhse_postures'] ?? null,
                'postures_penibilite' => $data['qhse_postures_penibilite'] ?? null,
                'nuisances_physiques' => $data['qhse_nuisances_physiques'] ?? null,
                'nuisances_chimiques' => $data['qhse_nuisances_chimiques'] ?? null,
                'risques_mecaniques' => $data['qhse_risques'] ?? null,
                'organisation_travail' => $data['qhse_organisation'] ?? null,
                'epi_disponibilite' => $data['qhse_epi_dispo'] ?? null,
                'epi_utilisation' => $data['qhse_epi_utilisation'] ?? null,
                'epi_difficultes' => $data['qhse_epi_difficulte'] ?? null,
                'epi_autres' => $data['qhse_epi_autres'] ?? null,
                'formation_sst' => $data['qhse_formation'] ?? null,
                'appreciation_poste' => $data['qhse_appreciation'] ?? null,
                'observations_qhse' => $data['qhse_observations'] ?? null,
                'synthese_risque' => $data['qhse_synthese_risque'] ?? null,
                'synthese_facteurs' => $data['qhse_synthese_facteurs'] ?? null,
                'synthese_actions' => $data['qhse_synthese_actions'] ?? null,
            ]
        );

        return redirect()
            ->route('medical-visits.qhse.index')
            ->with('success', 'QHSE mis à jour avec succès.');
    }

    public function export()
    {
        $qhseTable = $this->resolveQhseTable();
        $sourceColumns = collect(Schema::getColumnListing($qhseTable))
            ->reject(fn(string $column) => $column === 'updated_at')
            ->values()
            ->all();
        $headers = collect($sourceColumns)
            ->map(function (string $column) {
                if ($column === 'employee_id') {
                    return 'employe';
                }
                if ($column === 'created_at') {
                    return 'date_passage';
                }
                return $column;
            })
            ->push('age', 'service', 'direction', 'delegation')
            ->all();
        $filename = 'qhse-' . now()->format('Ymd-His') . '.csv';

        $query = MedicalVisitQhse::query()
            ->from("{$qhseTable} as medical_visit_qhses")
            ->with('employee')
            ->latest();

        return response()->streamDownload(function () use ($query, $sourceColumns, $headers) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, array_map([$this, 'normalizeCsvValue'], $headers), ';');

            $query->chunk(200, function ($records) use ($handle, $sourceColumns) {
                foreach ($records as $record) {
                    $employee = $record->employee;
                    $row = [];
                    foreach ($sourceColumns as $column) {
                        if ($column === 'employee_id') {
                            $row[] = $this->employeeFullName($employee);
                            continue;
                        }
                        if ($column === 'created_at') {
                            $row[] = $this->normalizeCsvValue($employee?->date_passage);
                            continue;
                        }
                        $row[] = $this->normalizeCsvValue($record->getAttribute($column));
                    }
                    $row[] = $this->employeeAge($employee?->date_naissance);
                    $row[] = $this->normalizeCsvValue($employee?->service);
                    $row[] = $this->normalizeCsvValue($employee?->direction);
                    $row[] = $this->normalizeCsvValue($employee?->delegation_r);
                    fputcsv($handle, $row, ';');
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=utf-8',
        ]);
    }

    private function employeeFullName($employee): string
    {
        if (!$employee) {
            return '';
        }

        return trim(($employee->nom ?? '') . ' ' . ($employee->prenom ?? ''));
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

    private function normalizeCsvValue($value): string
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        if ($value === null) {
            return '';
        }

        $text = (string) $value;

        if ($text === '') {
            return '';
        }

        if (!preg_match('//u', $text)) {
            if (function_exists('mb_convert_encoding')) {
                $text = mb_convert_encoding($text, 'UTF-8', 'auto');
            } elseif (function_exists('iconv')) {
                $converted = iconv('ISO-8859-1', 'UTF-8//IGNORE', $text);
                $text = $converted === false ? $text : $converted;
            }
        }

        return $text;
    }

    private function resolveQhseTable(): string
    {
        return Schema::hasTable('visitemedicalqhse') ? 'visitemedicalqhse' : 'medical_visit_qhses';
    }
}
