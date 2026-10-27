<?php

use Acsi\AuthCode\Http\Controllers\Auth\LoginController;
use Acsi\AuthCode\Http\Controllers\Auth\RegistrationController;
use Illuminate\Support\Facades\Route;

// ─── Auth Routes ──────────────────────────────────────
Route::middleware('web')->group(function () {
    Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('auth.login');
    Route::post('/auth/login', [LoginController::class, 'sendCode'])->name('auth.login.send');

    Route::get('/auth/verify', [LoginController::class, 'showVerify'])->name('auth.verify');
    Route::post('/auth/verify', [LoginController::class, 'verify'])->name('auth.verify.post');

    Route::get('/register', [RegistrationController::class, 'showRegister'])->name('auth.register');
    Route::post('/auth/register', [RegistrationController::class, 'register'])->name('auth.register.post');

    Route::get('/auth/register/verify', [RegistrationController::class, 'showVerify'])->name('auth.register.verify');
    Route::post('/auth/register/verify', [RegistrationController::class, 'verify'])->name('auth.register.verify.post');
    Route::get('/auth/register/activate', [RegistrationController::class, 'verifyByLink'])->name('auth.register.verify.token');
    });

    Route::post('/auth/logout', [LoginController::class, 'logout'])
        ->middleware('auth')
        ->name('auth.logout');
});