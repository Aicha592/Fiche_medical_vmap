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
        $visits = MedicalVisit::with('user')
            ->latest()
            ->paginate(15);

        return view('medical_visits.qhse.index', [
            'visits' => $visits,
        ]);
    }

    public function edit(MedicalVisit $medicalVisit)
    {
        $medicalVisit->load('user');

        return view('medical_visits.qhse.form', [
            'visit' => $medicalVisit,
            'user' => $medicalVisit->user,
        ]);
    }

    public function update(Request $request, MedicalVisit $medicalVisit)
    {
        $data = $request->validate([
            'qhse_manutention' => 'array|nullable',
            'qhse_postures' => 'array|nullable',
            'qhse_nuisances_physiques' => 'array|nullable',
            'qhse_nuisances_chimiques' => 'array|nullable',
            'qhse_risques' => 'array|nullable',
            'qhse_organisation' => 'array|nullable',
            'qhse_epi_dispo' => 'array|nullable',
            'qhse_epi_utilisation' => 'nullable|string',
            'qhse_epi_difficulte' => 'array|nullable',
            'qhse_formation' => 'array|nullable',
            'qhse_appreciation' => 'nullable|string',
            'qhse_observations' => 'nullable|string',
            'qhse_synthese_risque' => 'nullable|string',
            'qhse_synthese_facteurs' => 'array|nullable',
            'qhse_synthese_actions' => 'array|nullable',
        ]);

        $medicalVisit->update([
            'contrainte_manutention' => $data['qhse_manutention'] ?? null,
            'contrainte_postures' => $data['qhse_postures'] ?? null,
            'nuisances_physiques' => $data['qhse_nuisances_physiques'] ?? null,
            'nuisances_chimiques' => $data['qhse_nuisances_chimiques'] ?? null,
            'risques_mecaniques' => $data['qhse_risques'] ?? null,
            'organisation_travail' => $data['qhse_organisation'] ?? null,
            'epi_disponibilite' => $data['qhse_epi_dispo'] ?? null,
            'epi_utilisation' => $data['qhse_epi_utilisation'] ?? null,
            'epi_difficultes' => $data['qhse_epi_difficulte'] ?? null,
            'formation_sst' => $data['qhse_formation'] ?? null,
            'appreciation_poste' => $data['qhse_appreciation'] ?? null,
            'observations_qhse' => $data['qhse_observations'] ?? null,
            'synthese_risque' => $data['qhse_synthese_risque'] ?? null,
            'synthese_facteurs' => $data['qhse_synthese_facteurs'] ?? null,
            'synthese_actions' => $data['qhse_synthese_actions'] ?? null,
        ]);

        return redirect()
            ->route('medical-visits.qhse.index')
            ->with('success', 'QHSE mis à jour avec succès.');
    }
}
