<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comanda;
use App\Models\Producto;
use App\Models\ComandaDetalle;
use Illuminate\Support\Facades\Auth;

class LComanda extends Component
{
  public $comandas, $comandaId, $cantidad, $idProducto;
  public $meseroNombre, $mesaNumero, $comandaFecha;
  public $productosDisponibles, $comandaDetalles = [];

  public function mount()
  {
    $user = Auth::user();
    $query = Comanda::with(['mesero', 'mesa']);

    if ($user->role === 'mesero') {
      $query->where('id_mesero', $user->id);
    }

    $this->comandas = $query->latest('fecha')->get();
  }

  public function render()
  {
    $this->productosDisponibles = Producto::all();

    if ($this->comandaId) {
      $comanda = Comanda::with('detalles.producto')->find($this->comandaId);
      $this->comandaDetalles = $comanda->detalles->map(function ($detalle) {
        return [
          'id' => $detalle->id,
          'producto_id' => $detalle->producto_id,
          'nombre' => $detalle->producto->nombre ?? 'N/A',
          'precio' => $detalle->producto->precio ?? 0,
          'cantidad' => $detalle->cantidad,
        ];
      });
    }

    return view('livewire.l-comanda', [
      'comandas' => $this->comandas,
    ]);
  }

  public function AbrirModalDetalles($ID)
  {
    $comanda = Comanda::with('mesero', 'mesa')->findOrFail($ID);
    $this->comandaId = $comanda->id;
    $this->meseroNombre = $comanda->mesero->name ?? 'N/A';
    $this->mesaNumero = $comanda->mesa->numero ?? 'N/A';
    $this->comandaFecha = $comanda->fecha;

    $this->dispatch('AbrirModalDetalles');
  }

  public function agregarDetalleProducto()
  {
    $this->validate([
      'idProducto' => 'required|exists:productos,id',
      'cantidad' => 'required|integer|min:1',
    ]);

    $producto = Producto::findOrFail($this->idProducto);

    // Verificar si hay suficiente stock disponible en este momento
    if ($this->cantidad > $producto->stock) {
      $this->addError('cantidad', 'No hay suficiente stock disponible.');
      return;
    }

    $detalleExistente = ComandaDetalle::where('id_comanda', $this->comandaId)
      ->where('id_producto', $producto->id)
      ->first();

    if ($detalleExistente) {
      // Si hay suficiente stock, simplemente actualizamos la cantidad
      $detalleExistente->update([
        'cantidad' => $detalleExistente->cantidad + $this->cantidad
      ]);
    } else {
      ComandaDetalle::create([
        'id_comanda' => $this->comandaId,
        'id_producto' => $producto->id,
        'cantidad' => $this->cantidad,
        'precio_unitario' => $producto->precio,
      ]);
    }

    // Descontar del stock disponible
    $producto->stock -= $this->cantidad;
    $producto->save();

    $this->reset(['idProducto', 'cantidad']);
  }



  public function eliminarDetalleProducto($detalleId)
  {
    $detalle = ComandaDetalle::find($detalleId);

    if ($detalle && $detalle->id == $this->comandaId) {
      $detalle->delete();
    }
  }

  public function CerrarModalDetalles()
  {
    $this->reset([
      'comandaId',
      'idProducto',
      'cantidad',
      'comandaDetalles',
      'meseroNombre',
      'mesaNumero',
      'comandaFecha',
    ]);
    $this->dispatch('CerrarModalDetalles');
  }
}
