<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Auth; // <-- Asegúrate de importar Auth

Route::middleware('guest')->group(function () {
  Volt::route('registrar', 'Login.registrar')
    ->name('register');

  Volt::route('login', 'Login.login')
    ->name('login');

  Volt::route('Restablecer Clave', 'login.restablecer')
    ->name('password.request');

  Volt::route('Resetear Clave/{token}', 'login.resetear')
    ->name('password.reset');
});

Route::middleware('auth')->group(function () {

  Route::post('logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
  })->name('logout');

  /*Volt::route('verify-email', 'pages.auth.verify-email')
    ->name('verification.notice');

  Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

  Volt::route('confirm-password', 'pages.auth.confirm-password')
    ->name('password.confirm');*/
});
