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
  public $nuevaCategoria = '';
  public $agregandoCategoria = false;


  public $nombre, $precio, $id_categoria, $stock;
  public $productoId = null;

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
    $this->reset(['nombre', 'precio', 'id_categoria', 'stock', 'productoId']);
    $this->dispatch('AbrirNuevoProducto');
  }

  public function cerrarModal()
  {
    $this->dispatch('CerrarNuevoProducto');
  }

  public function editarProducto($id)
  {
    $producto = Producto::findOrFail($id);
    $this->productoId = $producto->id;
    $this->nombre = $producto->nombre;
    $this->precio = $producto->precio;
    $this->id_categoria = $producto->id_categoria;
    $this->stock = $producto->stock;
    $this->dispatch('AbrirNuevoProducto');
  }

  public function guardarProducto()
  {
    $this->validate([
      'nombre' => 'required|string|max:100',
      'precio' => 'required|numeric|min:0',
      'id_categoria' => 'required|exists:categorias,id',
      'stock' => 'required|integer|min:0'
    ]);

    if ($this->productoId) {
      Producto::findOrFail($this->productoId)->update([
        'nombre' => $this->nombre,
        'precio' => $this->precio,
        'id_categoria' => $this->id_categoria,
        'stock' => $this->stock,
      ]);
    } else {
      Producto::create([
        'nombre' => $this->nombre,
        'precio' => $this->precio,
        'id_categoria' => $this->id_categoria,
        'stock' => $this->stock,
      ]);
    }

    $this->cerrarModal();
    $this->cargarProductos();
  }
  public function guardarNuevaCategoria()
  {
    $this->validate([
      'nuevaCategoria' => 'required|string|max:100|unique:categorias,nombre',
    ]);

    $categoria = Categoria::create([
      'nombre' => $this->nuevaCategoria,
    ]);

    $this->categorias = Categoria::all(); // Recarga lista
    $this->id_categoria = $categoria->id; // Selecciona automáticamente
    $this->nuevaCategoria = '';
    $this->agregandoCategoria = false;
  }

  public function render()
  {
    return view('livewire.l-productos');
  }
}
