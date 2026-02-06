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
        'date_naissance',
        'date_embauche',
        'emploi_occupe',
        'direction',
        'delegation_r',
        'service',
        'unite_communale',
        'telephone',
        'date_passage',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_embauche' => 'date',
        'date_passage' => 'date',
    ];

    public function getAgeAttribute(): ?int
    {
        if (!$this->date_naissance) {
            return null;
        }

        return Carbon::parse($this->date_naissance)->age;
    }

    public function getAncienneteAttribute(): ?string
    {
        if (!$this->date_embauche) {
            return null;
        }

        $years = Carbon::parse($this->date_embauche)->diffInYears(Carbon::today());
        return $years . ' ans';
    }

    public function medicalVisits()
    {
        return $this->hasMany(MedicalVisit::class);
    }
}
