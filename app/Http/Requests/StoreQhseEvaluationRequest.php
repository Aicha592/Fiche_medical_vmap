<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQhseEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Vérification de l'existence de l'employé lié
            'employee_id' => ['required', 'integer', 'exists:employees,id'],

            // Spécificités du parcours QHSE
            'poste_occupe' => ['nullable', 'array'],
            'poste_occupe.*' => ['string', 'max:255'],
            'poste_occupe_autre' => ['nullable', 'string', 'max:255'],
            'type_activite_dominante' => ['required', Rule::in(['Terrain', 'Bureau', 'Mixte'])],
            'horaire_travail' => ['required', 'array', 'min:1'],
            'horaire_travail.*' => [Rule::in(['Jour', 'après midi', 'Nuit'])],

            'manutention_efforts' => ['nullable', 'array'],
            'manutention_efforts.*' => ['string', 'max:255'],
            'frequence_manutention' => ['required', Rule::in(['Rare', 'Occasionnelle', 'Fréquente', 'Permanente'])],
            'gestes_postures' => ['nullable', 'array'],
            'gestes_postures.*' => ['string', 'max:255'],
            'niveau_penibilite' => ['required', 'integer', 'between:1,5'],
            'outils_travail' => ['nullable', 'array'],
            'outils_travail.*' => ['string', 'max:255'],
            'outils_travail_autre' => ['nullable', 'string', 'max:255'],

            'nuisances_physiques' => ['nullable', 'array'],
            'nuisances_physiques.*' => ['string', 'max:255'],
            'nuisances_chimiques_biologiques' => ['nullable', 'array'],
            'nuisances_chimiques_biologiques.*' => ['string', 'max:255'],

            'risques_accidentels' => ['nullable', 'array'],
            'risques_accidentels.*' => ['string', 'max:255'],
            'temoin_accident' => ['required', 'boolean'],

            'organisation_travail' => ['nullable', 'array'],
            'organisation_travail.*' => ['string', 'max:255'],

            'epi_fournis' => ['nullable', 'array'],
            'epi_fournis.*' => ['string', 'max:255'],
            'epi_fournis_autres' => ['nullable', 'string', 'max:255'],
            'epi_utilises_quotidien' => ['nullable', 'array'],
            'epi_utilises_quotidien.*' => ['string', 'max:255'],
            'epi_utilises_autres' => ['nullable', 'string', 'max:255'],
            'epi_difficultes' => ['nullable', 'array'],
            'epi_difficultes.*' => ['string', 'max:255'],

            'formations_recues' => ['nullable', 'array'],
            'formations_recues.*' => ['string', 'max:255'],
            'date_derniere_formation' => ['nullable', 'date'],

            'niveau_risque_agent' => ['required', Rule::in(['Faible', 'Modéré', 'Élevé', 'Très élevé'])],
            'ameliorations_necessaires' => ['required', 'boolean'],
            'suggestions_amelioration' => ['nullable', 'string', 'max:3000'],
        ];
    }
}
