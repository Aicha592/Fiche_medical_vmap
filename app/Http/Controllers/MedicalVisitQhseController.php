<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\MedicalVisitQhse;
use Illuminate\Http\Request;

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

}
