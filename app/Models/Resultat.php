<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resultat extends Model
{
    protected $fillable = [
        'blood_test_id',
        'file_path',
    ];

    public function bloodTest()
    {
        return $this->belongsTo(BloodTest::class);
    }
}
