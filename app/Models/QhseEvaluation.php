<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QhseEvaluation extends Model
{
    protected $fillable = [
        'employee_id',
        'anciennete',
        'type_activite_dominante',
        'horaire_travail',
        'manutention_efforts',
        'frequence_manutention',
        'gestes_postures',
        'niveau_penibilite',
        'outils_travail',
        'nuisances_physiques',
        'nuisances_chimiques_biologiques',
        'risques_accidentels',
        'temoin_accident',
        'organisation_travail',
        'epi_fournis',
        'epi_fournis_autres',
        'epi_utilises_quotidien',
        'epi_utilises_autres',
        'epi_difficultes',
        'formations_recues',
        'date_derniere_formation',
        'niveau_risque_agent',
        'ameliorations_necessaires',
        'suggestions_amelioration',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
