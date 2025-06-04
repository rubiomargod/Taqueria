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
          'precio' => $detalle->precio_unitario,
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

    // Verificar stock
    if ($producto->stock < $this->cantidad) {
      $this->addError('cantidad', 'No hay suficiente stock disponible.');
      return;
    }

    // Verificar si ya existe un detalle con ese producto
    $detalleExistente = ComandaDetalle::where('id', $this->comandaId)
      ->where('producto_id', $producto->id)
      ->first();

    if ($detalleExistente) {
      $nuevaCantidad = $detalleExistente->cantidad + $this->cantidad;

      if ($nuevaCantidad > $producto->stock) {
        $this->addError('cantidad', 'No puedes agregar más de lo que hay en stock.');
        return;
      }

      $detalleExistente->update(['cantidad' => $nuevaCantidad]);
    } else {
      ComandaDetalle::create([
        'id' => $this->comandaId,
        'producto_id' => $producto->id,
        'cantidad' => $this->cantidad,
        'precio_unitario' => $producto->precio,
      ]);
    }

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
