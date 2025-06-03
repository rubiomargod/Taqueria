<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;

class LProductos extends Component
{
  public $productos = [];
  public $categorias = [];
  public $categoriaSeleccionada = '';

  public $showModal = false;
  public $nombre, $precio, $id_categoria, $stock;

  public function updatedCategoriaSeleccionada()
  {
    $this->cargarProductos();
  }

  public function cargarProductos()
  {
    $this->productos = Producto::with('categoria')
      ->when($this->categoriaSeleccionada, function ($query) {
        $query->where('id_categoria', $this->categoriaSeleccionada);
      })
      ->get();
  }

  public function mount()
  {
    $this->categorias = Categoria::all();
    $this->cargarProductos();
  }

  public function abrirModal()
  {
    $this->reset(['nombre', 'precio', 'id_categoria', 'stock']);
    $this->dispatch('AbrirNuevoProducto');
  }
  public function cerrarModal()
  {
    $this->dispatch('CerrarNuevoProducto');
  }

  public function guardarProducto()
  {
    $this->validate([
      'nombre' => 'required|string|max:100',
      'precio' => 'required|numeric|min:0',
      'id_categoria' => 'required|exists:categorias,id',
      'stock' => 'required|integer|min:0'
    ]);

    Producto::create([
      'nombre' => $this->nombre,
      'precio' => $this->precio,
      'id_categoria' => $this->id_categoria,
      'stock' => $this->stock,
    ]);

    $this->showModal = false;
    $this->cargarProductos();
  }

  public function render()
  {
    return view('livewire.l-productos');
  }
}
