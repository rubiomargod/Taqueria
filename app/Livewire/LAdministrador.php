<?php

namespace App\Livewire;

use Illuminate\Support\Carbon;
use Livewire\Component;
use App\Models\Ventas;
use App\Models\User;



class LAdministrador extends Component
{
  public function render()
  {
    $this->cargarVentasPorTurno();
    return view('livewire.l-administrador');
  }

  public $ventasTurno1 = [];
  public $ventasTurno2 = [];

  public function cargarVentasPorTurno()
  {
    $hoy = now()->startOfDay();

    $inicioTurno1 = $hoy->copy()->setTime(8, 0);
    $finTurno1    = $hoy->copy()->setTime(15, 0);

    $inicioTurno2 = $hoy->copy()->setTime(15, 0);
    $finTurno2    = $hoy->copy()->setTime(22, 0);

    // Turno 1: 08:00 - 15:00
    $this->ventasTurno1 = Ventas::with('usuario')
      ->whereBetween('created_at', [$inicioTurno1, $finTurno1])
      ->get()
      ->groupBy('user_id')
      ->map(function ($ventas) {
        return [
          'usuario' => $ventas->first()->usuario->name ?? 'Desconocido',
          'total' => $ventas->sum('total'),
        ];
      })->values();

    // Turno 2: 15:00 - 22:00
    $this->ventasTurno2 = Ventas::with('usuario')
      ->whereBetween('created_at', [$inicioTurno2, $finTurno2])
      ->get()
      ->groupBy('user_id')
      ->map(function ($ventas) {
        return [
          'usuario' => $ventas->first()->usuario->name ?? 'Desconocido',
          'total' => $ventas->sum('total'),
        ];
      })->values();
  }
}
