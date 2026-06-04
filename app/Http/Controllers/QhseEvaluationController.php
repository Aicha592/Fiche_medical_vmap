<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQhseEvaluationRequest;
use App\Models\Employee; // Chargement de la liste des agents
use App\Models\MedicalVisitQhse;
use App\Models\QhseEvaluation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QhseEvaluationController extends Controller
{
    /**
     * Afficher l'interface de recherche et le formulaire.
     */
    public function create(): View
    {
        return view('medical_visits.qhse.form-evaluation');
    }

    /**
     * Rechercher un employé de manière dynamique (Ajax/Fetch).
     */
    public function searchEmployee(Request $request): JsonResponse
    {
        $search = $request->query('query');

        if (empty($search)) {
            return response()->json([]);
        }

        // Recherche par matricule exact ou par nom/prénom partiel
        $employees = Employee::where('matricule', '=', $search)
            ->orWhere('nom', 'LIKE', "%{$search}%")
            ->orWhere('prenom', 'LIKE', "%{$search}%")
            ->take(5) // Limite aux 5 premiers résultats pour l'autocomplétion
            ->get(['id', 'matricule', 'nom', 'prenom', 'direction', 'unite_communale', 'emploi_occupe']);

        return response()->json($employees);
    }

    /**
     * Enregistrer le formulaire QHSE.
     */
    public function store(StoreQhseEvaluationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $data = array_merge([
            'poste_occupe' => [],
            'horaire_travail' => [],
            'manutention_efforts' => [],
            'gestes_postures' => [],
            'outils_travail' => [],
            'nuisances_physiques' => [],
            'nuisances_chimiques_biologiques' => [],
            'risques_accidentels' => [],
            'organisation_travail' => [],
            'epi_fournis' => [],
            'epi_utilises_quotidien' => [],
            'epi_difficultes' => [],
            'formations_recues' => [],
        ], $validated);

        QhseEvaluation::create($data);

        return redirect()->route('qhse.create')
            ->with('success', 'Le formulaire QHSE a bien été enregistré pour cet agent.');
    }
}
