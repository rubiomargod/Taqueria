<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Venta;
use App\Models\Ventas;
use Illuminate\Support\Carbon;

class LVentas extends Component
{
  public $fecha_inicio;
  public $fecha_fin;
  public $ventas = [];

  public function mount()
  {
    $this->filtrar();
  }

  public function filtrar()
  {
    $query = Ventas::with(['comanda.mesa']);

    if ($this->fecha_inicio) {
      $query->whereDate('created_at', '>=', Carbon::parse($this->fecha_inicio));
    }

    if ($this->fecha_fin) {
      $query->whereDate('created_at', '<=', Carbon::parse($this->fecha_fin));
    }

    $this->ventas = $query->orderBy('created_at', 'desc')->get();
  }

  public function limpiarFiltros()
  {
    $this->reset(['fecha_inicio', 'fecha_fin']);
    $this->filtrar();
  }

  public function render()
  {
    return view('livewire.l-ventas');
  }
}
