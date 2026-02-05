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
        'role',
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

    public function isDoctor(): bool
    {
        return $this->is_doctor || $this->role === 'doctor';
    }

    public function isRh(): bool
    {
        return $this->role === 'rh';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
