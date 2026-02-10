<?php

namespace App\Http\Controllers\Backoffice;

use App\Models\MedicalVisit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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

        return view('backoffice.medical_records.index', [
            'user' => $user,
            'visits' => $visits,
            'search' => $search,
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

    private function resolveTables(): array
    {
        $medicalTable = Schema::hasTable('visitemedicale') ? 'visitemedicale' : 'medical_visits';
        $qhseTable = Schema::hasTable('visitemedicalqhse') ? 'visitemedicalqhse' : 'medical_visit_qhses';

        return [$medicalTable, $qhseTable];
    }
}
