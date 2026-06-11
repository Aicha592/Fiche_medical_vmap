<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\MedicalVisit;
use App\Models\MedicalVisitQhse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if ($user && ($user->isAdmin() || $user->isMedecin() || $user->isCh())) {
                return $next($request);
            }

            abort(403, 'Accès réservé à l’administrateur');
        });
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // -----------------------------
        // 1) Filtres date (logique index)
        // -----------------------------
        $selectedDate = $request->string('date_passage')->trim()->value();
        $onlyToday = $request->boolean('today');
        $selectedDelegation = $request->string('delegation_r_filter')->trim()->value();

        if ($onlyToday) {
            $selectedDate = now()->toDateString();
        }

        // Get all unique delegations for dropdown
        $allDelegations = Employee::whereNotNull('delegation_r')
            ->distinct('delegation_r')
            ->orderBy('delegation_r')
            ->pluck('delegation_r');

        // ----------------------------------------------------
        // 2) Base "dernière visite par employé" (logique index1)
        // ----------------------------------------------------
        $latestVisitIds = MedicalVisit::query()
            ->selectRaw('MAX(id)')
            ->groupBy('employee_id');

        $latestVisitsBase = MedicalVisit::query()
            ->whereIn('id', $latestVisitIds);

        $latestVisitEmployeeIds = (clone $latestVisitsBase)->select('employee_id');

        $employeesCount = Employee::count();
        $employeesWithVisit = MedicalVisit::distinct('employee_id')->count('employee_id');

        // Avis (sur dernières visites)
        $avisCounts = (clone $latestVisitsBase)
            ->selectRaw('avis, COUNT(*) as total')
            ->groupBy('avis')
            ->pluck('total', 'avis');

        $aptesSansRestriction = (int) ($avisCounts['Apte sans restriction'] ?? 0);
        $aptesAvecAmenagement = (int) ($avisCounts['Apte avec aménagement'] ?? 0);
        $inaptes = (int) ($avisCounts['Inapte temporaire'] ?? 0) + (int) ($avisCounts['Inapte définitif'] ?? 0);

        // RPS alert (sur dernières visites)
        $rpsAlerts = (clone $latestVisitsBase)
            ->whereRaw(
                '(
                (CASE WHEN stress IN (?, ?) THEN 1 ELSE 0 END) +
                (CASE WHEN sommeil IN (?, ?) THEN 1 ELSE 0 END) +
                (CASE WHEN charge_travail IN (?, ?) THEN 1 ELSE 0 END) +
                (CASE WHEN soutien IN (?, ?) THEN 1 ELSE 0 END)
            ) >= 2',
                ['Oui', 'Parfois', 'Oui', 'Parfois', 'Non', 'Variable', 'Peu', 'Pas du tout']
            )
            ->count();

        // IMC (sur dernières visites)
        $imcRisk = (clone $latestVisitsBase)->where('imc', '>=', 25)->count();
        $imcObesity = (clone $latestVisitsBase)->where('imc', '>=', 30)->count();

        // QHSE (sur employés ayant une "dernière visite")
        $qhseBase = MedicalVisitQhse::query()
            ->whereIn('employee_id', $latestVisitEmployeeIds);

        $postesRisque = (clone $qhseBase)->where('synthese_risque', 'Oui')->count();
        $risqueEleve = (clone $qhseBase)->whereIn('appreciation_poste', ['Risque élevé', 'Risque très élevé'])->count();
        $epiInsuffisant = (clone $qhseBase)->whereIn('epi_utilisation', ['Rarement', 'Jamais'])->count();

        // -----------------------------------
        // 3) Stats globales (index + index1)
        // -----------------------------------
        $stats = [
            // logique index
            'total_visits' => MedicalVisit::count(),
            'daily_visits' => MedicalVisit::whereDate('created_at', Carbon::today())->count(),
            'total_visits_pilot' => MedicalVisit::whereDate('created_at', '<=', '2026-06-01')->count(),
            'qhse_filled' => MedicalVisitQhse::query()
                ->where(function ($query) {
                    $query->whereNotNull('synthese_risque')
                        ->orWhereNotNull('observations_qhse')
                        ->orWhereNotNull('synthese_actions');
                })
                ->count(),
            'users_count' => User::count(),
            'employees_count' => $employeesCount,

            // logique index1 (coverage + indicateurs)
            'employees_with_visit' => $employeesWithVisit,
            'coverage_pct' => $employeesCount > 0
                ? round(($employeesWithVisit / $employeesCount) * 100, 1)
                : 0,

            'aptes_sans_restriction' => $aptesSansRestriction,
            'aptes_avec_amenagement' => $aptesAvecAmenagement,
            'inaptes' => $inaptes,

            'postes_risque' => $postesRisque,
            'risque_eleve' => $risqueEleve,
            'epi_insuffisant' => $epiInsuffisant,

            'rps_alerts' => $rpsAlerts,
            'imc_risk' => $imcRisk,
            'imc_obesity' => $imcObesity,
        ];

        // -----------------------------------
        // 4) Charts (index1)
        // -----------------------------------
        $avisLabels = [
            'Apte sans restriction',
            'Apte avec aménagement',
            'Inapte temporaire',
            'Inapte définitif',
        ];

        $avisChart = collect($avisLabels)
            ->map(function (string $label) use ($avisCounts) {
                $color = match ($label) {
                    'Apte sans restriction' => '#356a45',
                    'Apte avec aménagement' => '#4a7e59',
                    'Inapte temporaire' => '#9b7d34',
                    default => '#9f3f3f',
                };

                return [
                    'label' => $label,
                    'count' => (int) ($avisCounts[$label] ?? 0),
                    'color' => $color,
                ];
            })
            ->values();

        $latestVisitCount = (int) $avisChart->sum('count');

        $avisChart = $avisChart->map(function (array $item) use ($latestVisitCount) {
            $item['percent'] = $latestVisitCount > 0
                ? round(($item['count'] / $latestVisitCount) * 100, 1)
                : 0;
            return $item;
        });

        // Top facteurs QHSE (index1) - gère JSON string ou array
        $facteursCounts = [];
        (clone $qhseBase)
            ->whereNotNull('synthese_facteurs')
            ->pluck('synthese_facteurs')
            ->each(function ($facteurs) use (&$facteursCounts) {
                if (is_string($facteurs)) {
                    $decoded = json_decode($facteurs, true);
                    $facteurs = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
                }

                if (!is_array($facteurs)) {
                    return;
                }

                foreach ($facteurs as $facteur) {
                    if (!is_string($facteur) || trim($facteur) === '') {
                        continue;
                    }
                    $facteursCounts[$facteur] = ($facteursCounts[$facteur] ?? 0) + 1;
                }
            });

        arsort($facteursCounts);

        $topFacteurs = collect($facteursCounts)
            ->take(8)
            ->map(fn(int $count, string $label) => ['label' => $label, 'count' => $count])
            ->values();

        $maxFacteur = (int) $topFacteurs->max('count');

        $facteursChart = $topFacteurs->map(function (array $item) use ($maxFacteur) {
            $item['percent'] = $maxFacteur > 0
                ? round(($item['count'] / $maxFacteur) * 100, 1)
                : 0;
            $item['color'] = '#b1c56a';
            return $item;
        });

        // -----------------------------------
        // 5) Recent visits (logique index)
        // -----------------------------------
        $recentVisits = MedicalVisit::with(['employee', 'employee.qhse'])
            ->latest()
            ->take(5)
            ->get();

        // --------------------------------------------------------
        // 6) Visits by region + QHSE totals (group by region)
        // --------------------------------------------------------
        $visitsByRegion = Employee::query()
            ->leftJoin('medical_visits', 'medical_visits.employee_id', '=', 'employees.id')
            ->leftJoin('qhse_evaluations', 'qhse_evaluations.employee_id', '=', 'employees.id')
            ->selectRaw('COALESCE(employees.delegation_r, ?) as region', ['Non renseignée'])
            ->selectRaw('count(distinct employees.id) as region_total')
            ->selectRaw('count(distinct medical_visits.id) as done_total')
            ->selectRaw('count(distinct qhse_evaluations.id) as qhse_total');

        // Apply delegation filter if selected
        if ($selectedDelegation) {
            $visitsByRegion->where('employees.delegation_r', $selectedDelegation);
        }

        $visitsByRegion = $visitsByRegion
            //->whereDate('employees.date_passage', '<=', now()->toDateString())
            ->groupBy('employees.delegation_r')
            ->orderByRaw('COALESCE(employees.delegation_r, ?)', ['Non renseignée'])
            ->get();

        $visitsByRegionDay = null;
        if ($selectedDate) {
            $visitsByRegionDay = Employee::query()
                ->leftJoin('medical_visits', 'medical_visits.employee_id', '=', 'employees.id')
                ->leftJoin('qhse_evaluations', 'qhse_evaluations.employee_id', '=', 'employees.id')
                ->whereDate('employees.date_passage', $selectedDate);

            // Apply delegation filter if selected
            if ($selectedDelegation) {
                $visitsByRegionDay->where('employees.delegation_r', $selectedDelegation);
            }

            $visitsByRegionDay = $visitsByRegionDay
                ->selectRaw('count(distinct employees.id) as planned_total')
                ->selectRaw('count(distinct medical_visits.id) as done_total')
                ->selectRaw('count(distinct qhse_evaluations.id) as qhse_total')
                ->first();
        }

        return view('backoffice.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentVisits' => $recentVisits,
            'visitsByRegion' => $visitsByRegion,
            'selectedDate' => $selectedDate,
            'visitsByRegionDay' => $visitsByRegionDay,
            'onlyToday' => $onlyToday,
            'selectedDelegation' => $selectedDelegation,
            'allDelegations' => $allDelegations,

            // ajouts index1
            'avisChart' => $avisChart,
            'latestVisitCount' => $latestVisitCount,
            'facteursChart' => $facteursChart,
        ]);
    }

    // public function index(Request $request)
    // {
    //     $user = $request->user();

    //     $selectedDate = $request->string('date_passage')->trim()->value();
    //     $onlyToday = $request->boolean('today');

    //     if ($onlyToday) {
    //         $selectedDate = now()->toDateString();
    //     }

    //     $stats = [
    //         'total_visits' => MedicalVisit::count(),
    //         'visits_last_7_days' => MedicalVisit::where('created_at', '>=', now()->subDays(7))->count(),
    //         'qhse_filled' => MedicalVisitQhse::whereNotNull('synthese_risque')
    //             ->orWhereNotNull('observations_qhse')
    //             ->orWhereNotNull('synthese_actions')
    //             ->count(),
    //         'users_count' => User::count(),
    //         'employees_count' => Employee::count(),
    //     ];

    //     $recentVisits = MedicalVisit::with(['employee', 'employee.qhse'])
    //         ->latest()
    //         ->take(5)
    //         ->get();

    //     $visitsByPassage = Employee::query()
    //         ->leftJoin('medical_visits', 'medical_visits.employee_id', '=', 'employees.id')
    //         ->leftJoin('medical_visit_qhses', 'medical_visit_qhses.employee_id', '=', 'employees.id')
    //         ->selectRaw('employees.date_passage as date_passage')
    //         ->selectRaw('count(distinct employees.id) as planned_total')
    //         ->selectRaw('count(medical_visits.id) as done_total')
    //         ->selectRaw('count(distinct case when medical_visit_qhses.synthese_risque is not null or medical_visit_qhses.observations_qhse is not null or medical_visit_qhses.synthese_actions is not null then employees.id end) as qhse_total')
    //         ->whereNotNull('employees.date_passage')
    //         ->whereDate('employees.date_passage', '<=', now()->toDateString())
    //         ->groupBy('employees.date_passage')
    //         ->orderBy('employees.date_passage')
    //         ->get();

    //     $visitsByPassageDay = null;
    //     if ($selectedDate) {
    //         $visitsByPassageDay = Employee::query()
    //             ->leftJoin('medical_visits', 'medical_visits.employee_id', '=', 'employees.id')
    //             ->leftJoin('medical_visit_qhses', 'medical_visit_qhses.employee_id', '=', 'employees.id')
    //             ->whereDate('employees.date_passage', $selectedDate)
    //             ->selectRaw('count(distinct employees.id) as planned_total')
    //             ->selectRaw('count(medical_visits.id) as done_total')
    //             ->selectRaw('count(distinct case when medical_visit_qhses.synthese_risque is not null or medical_visit_qhses.observations_qhse is not null or medical_visit_qhses.synthese_actions is not null then employees.id end) as qhse_total')
    //             ->first();
    //     }

    //     return view('backoffice.dashboard', [
    //         'user' => $user,
    //         'stats' => $stats,
    //         'recentVisits' => $recentVisits,
    //         'visitsByPassage' => $visitsByPassage,
    //         'selectedDate' => $selectedDate,
    //         'visitsByPassageDay' => $visitsByPassageDay,
    //         'onlyToday' => $onlyToday,
    //     ]);
    // }
}
