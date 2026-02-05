<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'antecedents',
        'antecedents_precisions',
        'taille',
        'poids',
        'imc',
        'tension',
        'stress',
        'sommeil',
        'charge_travail',
        'soutien',
        'avis',
        'observations',

        // QHSE / SST
        'contrainte_manutention',
        'contrainte_postures',
        'nuisances_physiques',
        'nuisances_chimiques',
        'risques_mecaniques',
        'organisation_travail',
        'epi_disponibilite',
        'epi_utilisation',
        'epi_difficultes',
        'formation_sst',
        'appreciation_poste',
        'observations_qhse',
        'synthese_risque',
        'synthese_facteurs',
        'synthese_actions',
        'pdf_path',
        'created_by_user_id',
        'updated_by_user_id',
        'manutention_frequence',
        'manutention_precision',
        'postures_penibilite',
        'epi_autres',
    ];

    protected $casts = [
        'antecedents' => 'array',

        'contrainte_manutention' => 'array',
        'contrainte_postures' => 'array',
        'nuisances_physiques' => 'array',
        'nuisances_chimiques' => 'array',
        'risques_mecaniques' => 'array',
        'organisation_travail' => 'array',
        'epi_disponibilite' => 'array',
        'epi_difficultes' => 'array',
        'formation_sst' => 'array',
        'synthese_facteurs' => 'array',
        'synthese_actions' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }
}
