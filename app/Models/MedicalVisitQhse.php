<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalVisitQhse extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'contrainte_manutention',
        'manutention_frequence',
        'manutention_precision',
        'contrainte_postures',
        'postures_penibilite',
        'nuisances_physiques',
        'nuisances_chimiques',
        'risques_mecaniques',
        'organisation_travail',
        'epi_disponibilite',
        'epi_utilisation',
        'epi_difficultes',
        'epi_autres',
        'formation_sst',
        'appreciation_poste',
        'observations_qhse',
        'synthese_risque',
        'synthese_facteurs',
        'synthese_actions',
    ];

    protected $casts = [
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
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
