<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodTest extends Model
{
    protected $fillable = [
        'employee_id',
        'uree',
        'creat',
        'asat',
        'alat',
        'aghbs',
        'chol',
        'tg',
        'gaj',
        'hb',
        'hct',
        'gb',
        'plt',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function results()
    {
        return $this->hasMany(Resultat::class);
    }
}
