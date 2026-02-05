<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\MedicalVisit;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || (!$user->isDoctor() && !$user->isRh() && !$user->isAdmin())) {
                abort(403, 'Accès réservé au backoffice');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total_visits' => MedicalVisit::count(),
            'visits_last_7_days' => MedicalVisit::where('created_at', '>=', now()->subDays(7))->count(),
            'qhse_filled' => MedicalVisit::whereNotNull('synthese_risque')
                ->orWhereNotNull('observations_qhse')
                ->orWhereNotNull('synthese_actions')
                ->count(),
            'users_count' => User::count(),
        ];

        $recentVisits = MedicalVisit::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('backoffice.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentVisits' => $recentVisits,
        ]);
    }
}
