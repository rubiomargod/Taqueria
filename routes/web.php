<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'Layouts.Inicio.Inicio');

Route::view('/Inicio', 'Layouts.Inicio.Inicio')
  ->middleware(['auth', 'verified'])
  ->name('INICIO');

Route::view('/Usuarios', 'Layouts.Usuarios.Usuarios')
  ->middleware(['auth', 'verified'])
  ->name('USUARIOS');

Route::view('/Productos', 'Layouts.Productos.Productos')
  ->middleware(['auth', 'verified'])
  ->name('Productos');

Route::view('/Ventas', 'Layouts.Ventas.Ventas')
  ->middleware(['auth', 'verified'])
  ->name('Ventas');

Route::view('/Comanda', 'Layouts.Comanda.Comanda')
  ->middleware(['auth', 'verified'])
  ->name('Comanda');

require __DIR__ . '/auth.php';
