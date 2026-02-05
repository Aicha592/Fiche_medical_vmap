<?php

namespace App\Http\Controllers;

use App\Models\MedicalVisit;
use Illuminate\Http\Request;

class MedicalVisitQhseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->isRh()) {
                abort(403, 'Accès réservé au rôle RH');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $visits = MedicalVisit::with('employee')
            ->latest()
            ->paginate(15);

        return view('medical_visits.qhse.index', [
            'visits' => $visits,
        ]);
    }

    public function edit(MedicalVisit $medicalVisit)
    {
        $medicalVisit->load('employee');

        return view('medical_visits.qhse.form', [
            'visit' => $medicalVisit,
            'employee' => $medicalVisit->employee,
        ]);
    }

    public function update(Request $request, MedicalVisit $medicalVisit)
    {
        $data = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
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

        $employeeId = $medicalVisit->employee_id ?? $data['employee_id'] ?? null;
        if (!$employeeId) {
            return back()->withErrors(['employee_id' => 'Employé manquant pour la mise à jour QHSE.']);
        }

        $visit = MedicalVisit::where('employee_id', $employeeId)
            ->latest()
            ->first();

        if (!$visit) {
            $visit = new MedicalVisit([
                'employee_id' => $employeeId,
                'created_by_user_id' => $request->user()->id,
            ]);
        }

        $visit->fill([
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
            'updated_by_user_id' => $request->user()->id,
        ]);
        $visit->save();

        return redirect()
            ->route('medical-visits.qhse.index')
            ->with('success', 'QHSE mis à jour avec succès.');
    }

    public function lookup(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $visit = MedicalVisit::where('employee_id', $data['employee_id'])
            ->latest()
            ->first();

        if (!$visit) {
            return response()->json([
                'message' => 'Aucune visite trouvée pour cet agent.',
            ], 404);
        }

        return response()->json([
            'visit_id' => $visit->id,
        ]);
    }
}
