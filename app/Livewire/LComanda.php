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
  public $totalVenta = 0;

  // Propiedades para crear nueva comanda
  public $mesaSeleccionada;

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
      })->toArray();
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
    $this->cargarDetallesComanda();
    $this->dispatch('AbrirModalDetalles');
  }

  public function agregarDetalleProducto()
  {
    $this->validate([
      'idProducto' => 'required|exists:productos,id',
      'cantidad' => 'required|integer|min:1',
    ]);

    $producto = Producto::findOrFail($this->idProducto);

    if ($this->cantidad > $producto->stock) {
      $this->addError('cantidad', 'No hay suficiente stock disponible.');
      return;
    }

    $detalleExistente = ComandaDetalle::where('id_comanda', $this->comandaId)
      ->where('id_producto', $producto->id)
      ->first();

    if ($detalleExistente) {
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

    $producto->stock -= $this->cantidad;
    $producto->save();

    $this->reset(['idProducto', 'cantidad']);
    $this->cargarDetallesComanda();
  }

  public function eliminarDetalleProducto($detalleId)
  {
    $detalle = ComandaDetalle::find($detalleId);

    if (!$detalle) return;

    $producto = Producto::find($detalle->id_producto);
    if ($producto) {
      $producto->stock += $detalle->cantidad;
      $producto->save();
    }

    $detalle->delete();
    $this->cargarDetallesComanda();
  }

  public function cargarDetallesComanda()
  {
    if ($this->comandaId) {
      $comanda = Comanda::with('detalles.producto')->find($this->comandaId);

      $this->comandaDetalles = $comanda->detalles->map(function ($detalle) {
        return [
          'id' => $detalle->id,
          'producto_id' => $detalle->producto_id,
          'nombre' => $detalle->producto->nombre ?? 'N/A',
          'precio' => $detalle->precio_unitario ?? $detalle->producto->precio ?? 0,
          'cantidad' => $detalle->cantidad,
        ];
      })->toArray();

      $this->totalVenta = collect($this->comandaDetalles)->sum(fn($d) => $d['cantidad'] * $d['precio']);
    }
  }

  public function cerrarVenta($id)
  {
    $comanda = Comanda::with('mesero', 'mesa')->findOrFail($id);
    $this->comandaId = $comanda->id;
    $this->meseroNombre = $comanda->mesero->name ?? 'N/A';
    $this->mesaNumero = $comanda->mesa->numero ?? 'N/A';
    $this->comandaFecha = $comanda->fecha;
    $this->cargarDetallesComanda();

    $this->totalVenta = collect($this->comandaDetalles)->sum(fn($d) => $d['cantidad'] * $d['precio']);

    $this->dispatch('AbrirModalVenta');
  }

  public function CerrarModalDetalles()
  {
    $this->reset(['comandaId', 'meseroNombre', 'mesaNumero', 'comandaFecha', 'comandaDetalles']);
    $this->dispatch('CerrarModalDetalles');
  }

  public function confirmarVenta()
  {
    // Aquí podrías implementar la lógica para registrar la venta, marcar la comanda como cerrada, etc.
    // Por ejemplo:
    // Ventas::create([...]);

    $this->dispatch('CerrarModalVenta');
    $this->reset(['comandaId', 'meseroNombre', 'mesaNumero', 'comandaFecha', 'comandaDetalles', 'totalVenta']);

    // Recargar comandas para reflejar cambios
    $this->comandas = Comanda::with(['mesero', 'mesa'])->latest('fecha')->get();
  }

  // Nuevo método para crear una comanda nueva
  public function crearComanda()
  {
    $user = Auth::user();
    $comanda = Comanda::create([
      'id_mesero' => $user->id,
      'id_mesa' => $this->mesaSeleccionada ?? null,
      'fecha' => now(),
      'estado' => 'abierta',
    ]);

    $this->comandas->prepend($comanda);
    $this->CerrarModalComanda();
    $this->AbrirModalDetalles($comanda->id);
    $this->mesaSeleccionada = null;
  }
  public function  AbrirModalComanda()
  {
    $this->dispatch('AbrirModalComanda');
  }
  public function CerrarModalComanda()
  {
    $this->dispatch('CerrarModalComanda');
  }
}
