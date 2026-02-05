<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\MedicalVisit;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
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
        $search = $request->string('q')->trim()->value();

        $query = MedicalVisit::with('employee')->latest();

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

    public function show(Request $request, MedicalVisit $medicalVisit)
    {
        $medicalVisit->load('employee');

        return view('backoffice.medical_records.show', [
            'user' => $request->user(),
            'visit' => $medicalVisit,
        ]);
    }
}
