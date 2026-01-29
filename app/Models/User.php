<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $fillable = [
        'nom',
        'prenom',
        'matricule',
        'sexe',
        'age',
        'direction',
        'poste',
        'anciennete',
        'site',
        'email',
        'password',
        'is_doctor',
        'telephone'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function medicalVisits()
    {
        return $this->hasMany(MedicalVisit::class);
    }
}
