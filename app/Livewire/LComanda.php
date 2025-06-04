<?php

namespace App\Livewire;

use App\Models\Comanda;
use App\Models\ComandaDetalle;
use App\Models\Mesa;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class LComanda extends Component
{
  public $comandas, $comandaId, $cantidad, $idProducto, $mesas;
  public $meseroNombre, $mesaNumero, $comandaFecha;
  public $productosDisponibles, $comandaDetalles = [];
  public $totalVenta = 0;

  public $mesaSeleccionada;
  public $agregandoMesa = false;
  public $nuevoNumeroMesa;
  public $mesasDisponibles = [];

  public function mount()
  {
    $this->loadComandas();
    $this->loadMesas();
  }

  public function render()
  {
    $this->productosDisponibles = Producto::all();

    if ($this->comandaId) {
      $this->cargarDetallesComanda();
    }

    return view('livewire.l-comanda');
  }

  public function loadComandas()
  {
    $user = Auth::user();

    $query = Comanda::with(['mesero', 'mesa'])
      ->where('estado', '!=', 'terminado'); // excluir comandas terminadas

    if ($user->role === 'mesero') {
      $query->where('id_mesero', $user->id);
    }

    $this->comandas = $query->latest('fecha')->get();
  }


  public function loadMesas()
  {
    $this->mesasDisponibles = Mesa::all();
  }

  public function AbrirModalDetalles($id)
  {
    $comanda = Comanda::with('mesero', 'mesa')->findOrFail($id);
    $this->comandaId = $comanda->id;
    $this->meseroNombre = $comanda->mesero->name ?? 'N/A';
    $this->mesaNumero = $comanda->mesa->numero ?? 'N/A';
    $this->comandaFecha = $comanda->fecha;
    $this->cargarDetallesComanda();
    $this->dispatch('AbrirModalDetalles');
  }

  public function cargarDetallesComanda()
  {
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

    $detalle = ComandaDetalle::firstOrCreate(
      ['id_comanda' => $this->comandaId, 'id_producto' => $producto->id],
      ['precio_unitario' => $producto->precio, 'cantidad' => 0]
    );

    $detalle->increment('cantidad', $this->cantidad);
    $producto->decrement('stock', $this->cantidad);

    $this->reset(['idProducto', 'cantidad']);
    $this->cargarDetallesComanda();
  }

  public function eliminarDetalleProducto($detalleId)
  {
    $detalle = ComandaDetalle::find($detalleId);
    if ($detalle) {
      Producto::where('id', $detalle->id_producto)->increment('stock', $detalle->cantidad);
      $detalle->delete();
      $this->cargarDetallesComanda();
    }
  }

  public function cerrarVenta($id)
  {
    $comanda = Comanda::with(['mesero', 'mesa', 'detalles.producto'])->findOrFail($id);

    $this->comandaId = $comanda->id;
    $this->meseroNombre = $comanda->mesero->name ?? 'N/A';
    $this->mesaNumero = $comanda->mesa->numero ?? 'N/A';
    $this->comandaFecha = $comanda->fecha;

    // Cargar detalles en array para mostrar en el modal
    $this->comandaDetalles = $comanda->detalles->map(function ($detalle) {
      return [
        'id' => $detalle->id,
        'producto_id' => $detalle->producto_id,
        'nombre' => $detalle->producto->nombre ?? 'N/A',
        'precio' => $detalle->precio_unitario ?? $detalle->producto->precio ?? 0,
        'cantidad' => $detalle->cantidad,
      ];
    })->toArray();

    // Calcular total
    $this->totalVenta = collect($this->comandaDetalles)->sum(fn($d) => $d['cantidad'] * $d['precio']);

    // Mostrar modal
    $this->dispatch('AbrirModalVenta');
  }


  public function confirmarVenta()
  {
    $this->dispatch('CerrarModalVenta');
    $this->reset(['comandaId', 'meseroNombre', 'mesaNumero', 'comandaFecha', 'comandaDetalles', 'totalVenta']);
    $this->loadComandas();
  }

  public function crearComanda()
  {
    $this->validate([
      'mesaSeleccionada' => 'required|exists:mesas,id',
    ]);

    $comanda = Comanda::create([
      'id_mesero' => Auth::id(),
      'id_mesa' => $this->mesaSeleccionada,
      'fecha' => now(),
      'estado' => 'abierta',
    ]);

    $this->comandas->prepend($comanda);
    $this->CerrarModalComanda();
    $this->AbrirModalDetalles($comanda->id);
    $this->mesaSeleccionada = null;
  }

  public function guardarNuevaMesa()
  {
    $this->validate([
      'nuevoNumeroMesa' => [
        'required',
        'numeric',
        'min:1',
        Rule::unique('mesas', 'numero')
      ],
    ]);

    $mesa = Mesa::create([
      'numero' => $this->nuevoNumeroMesa,
    ]);

    $this->loadMesas();
    $this->mesaSeleccionada = $mesa->id;
    $this->nuevoNumeroMesa = '';
    $this->agregandoMesa = false;
    $this->resetValidation('nuevoNumeroMesa');
  }

  public function AbrirModalComanda()
  {
    $this->dispatch('AbrirModalComanda');
  }


  public function CerrarModalComanda()
  {
    $this->dispatch('CerrarModalComanda');
  }

  public function CerrarModalDetalles()
  {
    $this->reset(['comandaId', 'meseroNombre', 'mesaNumero', 'comandaFecha', 'comandaDetalles']);
    $this->dispatch('CerrarModalDetalles');
  }
}
