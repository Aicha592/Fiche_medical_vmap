<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Otp;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('otps:clear-expired', function () {
    $deleted = Otp::where('expires_at', '<=', now())->delete();
    $this->info("{$deleted} OTP expiré(s) supprimé(s).");
})->purpose('Supprimer les OTP expirés');

Schedule::command('otps:clear-expired')->everyMinute();
