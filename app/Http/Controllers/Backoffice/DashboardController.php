<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\MedicalVisit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || !$user->isAdmin()) {
                abort(403, 'Accès réservé à l’administrateur');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $selectedDate = $request->string('date_passage')->trim()->value();
        $onlyToday = $request->boolean('today');

        if ($onlyToday) {
            $selectedDate = now()->toDateString();
        }

        $stats = [
            'total_visits' => MedicalVisit::count(),
            'visits_last_7_days' => MedicalVisit::where('created_at', '>=', now()->subDays(7))->count(),
            'qhse_filled' => MedicalVisit::whereNotNull('synthese_risque')
                ->orWhereNotNull('observations_qhse')
                ->orWhereNotNull('synthese_actions')
                ->count(),
            'users_count' => User::count(),
            'employees_count' => Employee::count(),
        ];

        $recentVisits = MedicalVisit::with('employee')
            ->latest()
            ->take(5)
            ->get();

        $visitsByPassage = Employee::query()
            ->leftJoin('medical_visits', 'medical_visits.employee_id', '=', 'employees.id')
            ->selectRaw('employees.date_passage as date_passage')
            ->selectRaw('count(distinct employees.id) as planned_total')
            ->selectRaw('count(medical_visits.id) as done_total')
            ->whereNotNull('employees.date_passage')
            ->whereDate('employees.date_passage', '<=', now()->toDateString())
            ->groupBy('employees.date_passage')
            ->orderBy('employees.date_passage')
            ->get();

        $visitsByPassageDay = null;
        if ($selectedDate) {
            $visitsByPassageDay = Employee::query()
                ->leftJoin('medical_visits', 'medical_visits.employee_id', '=', 'employees.id')
                ->whereDate('employees.date_passage', $selectedDate)
                ->selectRaw('count(distinct employees.id) as planned_total')
                ->selectRaw('count(medical_visits.id) as done_total')
                ->first();
        }

        return view('backoffice.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentVisits' => $recentVisits,
            'visitsByPassage' => $visitsByPassage,
            'selectedDate' => $selectedDate,
            'visitsByPassageDay' => $visitsByPassageDay,
            'onlyToday' => $onlyToday,
        ]);
    }
}
