<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class User extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $fillable = [
        'email',
        'password',
        'is_doctor',
        'role',
        'name',
        'telephone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isDoctor(): bool
    {
        return $this->is_doctor || $this->role === 'doctor';
    }

    public function isRh(): bool
    {
        return $this->role == 'rh';
    }

    public function isCh(): bool
    {
        return $this->role == 'ch';
    }

    public function isMedecin(): bool
    {
        return $this->role === 'med-taf';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBio(): bool
    {
        return $this->role === 'bio';
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}
