<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'Layouts.Inicio.Inicio');

Route::view('/Inicio', 'Layouts.Inicio.Inicio')
  ->middleware(['auth', 'verified'])
  ->name('INICIO');

Route::view('/Usuarios', 'Layouts.Usuarios.Usuarios')
  ->middleware(['auth', 'verified'])
  ->name('USUARIOS');

require __DIR__ . '/auth.php';
