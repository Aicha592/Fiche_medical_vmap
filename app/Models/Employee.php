<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'sexe',
        'age',
        'date_naissance',
        'date_embauche',
        'emploi_occupe',
        'direction',
        'delegation_r',
        'service',
        'unite_communale',
        'anciennete',
        'site',
        'telephone',
        'date_passage',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_embauche' => 'date',
        'date_passage' => 'date',
    ];

    protected static function booted()
    {
        static::saving(function (Employee $employee) {
            if ($employee->date_naissance) {
                $employee->age = Carbon::parse($employee->date_naissance)->age;
            }

            if ($employee->date_embauche) {
                $years = Carbon::parse($employee->date_embauche)->diffInYears(Carbon::today());
                $employee->anciennete = $years . ' ans';
            }
        });
    }

    public function medicalVisits()
    {
        return $this->hasMany(MedicalVisit::class);
    }
}
