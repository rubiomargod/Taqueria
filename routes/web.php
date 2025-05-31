<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('/Inicio', 'Layouts.Inicio.Inicio')
  ->middleware(['auth', 'verified'])
  ->name('INICIO');

Route::view('profile', 'profile')
  ->middleware(['auth'])
  ->name('profile');

require __DIR__ . '/auth.php';
