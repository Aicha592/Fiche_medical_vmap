<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicalVisitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;



Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    // Page principale de l'application (après login)
    Route::get('/home', [MedicalVisitController::class, 'index'])->name('home');

    // Recherche d'agents 
    Route::get('/agents/search', [UserController::class, 'search'])->name('agents.search');

    // Enregistrement visite médicale
    Route::post('/medical-visits', [MedicalVisitController::class, 'store'])->name('medical-visits.store');

    // Profil médecin 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
