<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comanda;
use Illuminate\Support\Facades\Auth;

class LComanda extends Component
{
  public $comandas = [];

  public function mount()
  {
    $user = Auth::user();

    $query = Comanda::with(['mesero', 'mesa']);

    if ($user->role === 'mesero') {
      $query->where('id_mesero', $user->id);
    }

    $this->comandas = $query->latest('fecha')->get();
  }

  public function verDetalles($id)
  {
    session()->flash('message', "Ver detalles de la comanda #$id");
  }

  public function cerrarVenta($id)
  {
    session()->flash('message', "Venta cerrada para comanda #$id");
  }

  public function render()
  {
    return view('livewire.l-comanda', [
      'comandas' => $this->comandas,
    ]);
  }
}
