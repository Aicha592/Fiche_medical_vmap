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
        'pdf_path',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    protected $casts = [
        'antecedents' => 'array',
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

    public function qhse()
    {
        return $this->hasOne(MedicalVisitQhse::class, 'employee_id', 'employee_id')->withDefault();
    }
}
