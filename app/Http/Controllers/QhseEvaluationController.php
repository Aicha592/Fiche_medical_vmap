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

        $qhseVisit = new QhseEvaluation();
        $qhseVisit->employee_id = $validated['employee_id'];
        $qhseVisit->type_activite_dominante = $validated['type_activite_dominante'];
        $qhseVisit->horaire_travail = $validated['horaire_travail'];
        $qhseVisit->manutention_efforts = $validated['manutention_efforts'] ?? [];
        $qhseVisit->frequence_manutention = $validated['frequence_manutention'];
        $qhseVisit->gestes_postures = $validated['gestes_postures'] ?? [];
        $qhseVisit->niveau_penibilite = $validated['niveau_penibilite'];
        $qhseVisit->outils_travail = $validated['outils_travail'] ?? [];
        $qhseVisit->outils_travail_autre = $validated['outils_travail_autre'] ?? null;
        $qhseVisit->nuisances_physiques = $validated['nuisances_physiques'] ?? [];
        $qhseVisit->nuisances_chimiques_biologiques = $validated['nuisances_chimiques_biologiques'] ?? [];
        $qhseVisit->risques_accidentels = $validated['risques_accidentels'] ?? [];
        $qhseVisit->temoin_accident = (bool) $validated['temoin_accident'];
        $qhseVisit->organisation_travail = $validated['organisation_travail'] ?? [];
        $qhseVisit->epi_fournis = $validated['epi_fournis'] ?? [];
        $qhseVisit->epi_fournis_autres = $validated['epi_fournis_autres'] ?? null;
        $qhseVisit->epi_utilises_quotidien = $validated['epi_utilises_quotidien'] ?? [];
        $qhseVisit->epi_utilises_autres = $validated['epi_utilises_autres'] ?? null;
        $qhseVisit->epi_difficultes = $validated['epi_difficultes'] ?? [];
        $qhseVisit->formations_recues = $validated['formations_recues'] ?? [];
        $qhseVisit->date_derniere_formation = $validated['date_derniere_formation'] ?? null;
        $qhseVisit->niveau_risque_agent = $validated['niveau_risque_agent'];
        $qhseVisit->ameliorations_necessaires = (bool) $validated['ameliorations_necessaires'];
        $qhseVisit->suggestions_amelioration = $validated['suggestions_amelioration'] ?? null;

        $qhseVisit->save();

        return redirect()->route('qhse.create')
            ->with('success', 'Le formulaire QHSE a bien été enregistré pour cet agent.');
    }
}
