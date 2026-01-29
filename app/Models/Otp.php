<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // Vérifie si le code est expiré
    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }

    // Relation inverse vers User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
