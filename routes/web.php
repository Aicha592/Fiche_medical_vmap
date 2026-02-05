<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MedicalVisitController;
use App\Http\Controllers\MedicalVisitQhseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\Backoffice\DashboardController as BackofficeDashboardController;
use App\Http\Controllers\Backoffice\MedicalRecordController;
use App\Http\Controllers\Backoffice\UserAdminController;

/*
|--------------------------------------------------------------------------
| REDIRECTION PAR DÉFAUT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION + OTP
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/otp', [AuthController::class, 'showOtp'])->name('otp.form');
Route::post('/otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ENVOI SMS (optionnel pour tests)
|--------------------------------------------------------------------------
*/
Route::post('/send-sms', [SmsController::class, 'envoyerSms'])->name('sms.send');

/*
|--------------------------------------------------------------------------
| ZONE PROTÉGÉE (APRÈS OTP)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Tableau de bord
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Page principale / visites médicales
    Route::get('/home', [MedicalVisitController::class, 'index'])->name('home');

    // Recherche agents
    Route::get('/agents/search', [UserController::class, 'search'])->name('agents.search');

    // Ajouter une visite médicale
    Route::post('/medical-visits', [MedicalVisitController::class, 'store'])->name('medical-visits.store');
    Route::get('/medical-visits/{medicalVisit}/pdf', [MedicalVisitController::class, 'pdf'])->name('medical-visits.pdf');

    // QHSE (RH uniquement)
    Route::get('/medical-visits-qhse', [MedicalVisitQhseController::class, 'index'])->name('medical-visits.qhse.index');
    Route::get('/medical-visits/{medicalVisit}/qhse', [MedicalVisitQhseController::class, 'edit'])->name('medical-visits.qhse.edit');
    Route::put('/medical-visits/{medicalVisit}/qhse', [MedicalVisitQhseController::class, 'update'])->name('medical-visits.qhse.update');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | BACKOFFICE
    |--------------------------------------------------------------------------
    */
    Route::prefix('backoffice')->name('backoffice.')->group(function () {
        Route::get('/dashboard', [BackofficeDashboardController::class, 'index'])->name('dashboard');
        Route::get('/fiches', [MedicalRecordController::class, 'index'])->name('medical-records.index');
        Route::get('/fiches/{medicalVisit}', [MedicalRecordController::class, 'show'])->name('medical-records.show');
        Route::get('/utilisateurs', [UserAdminController::class, 'index'])->name('users.index');
    });
});
