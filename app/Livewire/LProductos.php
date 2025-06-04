<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Validation\Rule;

class LProductos extends Component
{
  public $productos = [];
  public $categorias = [];
  public $categoriaSeleccionada = '';

  public $productoId = null;
  public $nombre, $precio, $id_categoria, $stock;

  public $newCategoriaNombre = '';
  public $agregandoCategoria = false;

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
      ->orderBy('nombre')
      ->get();
  }

  public function mount()
  {
    $this->loadCategorias();
    $this->cargarProductos();
  }

  protected function loadCategorias()
  {
    $this->categorias = Categoria::orderBy('nombre')->get();
  }

  public function abrirModal()
  {
    $this->reset([
      'productoId',
      'nombre',
      'precio',
      'id_categoria',
      'stock',
      'newCategoriaNombre',
      'agregandoCategoria'
    ]);
    $this->resetValidation();
    $this->dispatch('AbrirModalProducto');
  }

  public function cerrarModal()
  {
    $this->reset([
      'productoId',
      'nombre',
      'precio',
      'id_categoria',
      'stock',
      'newCategoriaNombre',
      'agregandoCategoria'
    ]);
    $this->resetValidation();
    $this->dispatch('CerrarModalProducto');
  }

  public function editarProducto($id)
  {
    $this->resetValidation();
    $this->agregandoCategoria = false;

    $producto = Producto::findOrFail($id);
    $this->productoId = $producto->id;
    $this->nombre = $producto->nombre;
    $this->precio = $producto->precio;
    $this->id_categoria = $producto->id_categoria;
    $this->stock = $producto->stock;

    $this->dispatch('AbrirModalProducto');
  }

  public function guardarProducto()
  {
    $this->validate([
      'nombre' => 'required|string|max:100',
      'precio' => 'required|numeric|min:0',
      'id_categoria' => 'required|exists:categorias,id',
      'stock' => 'required|integer|min:0'
    ]);

    try {
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
    } catch (\Exception $e) {
      \Log::error('Error al guardar producto: ' . $e->getMessage());
    }
  }

  public function guardarNuevaCategoria()
  {
    $this->validate([
      'newCategoriaNombre' => [
        'required',
        'string',
        'max:100',
        Rule::unique('categorias', 'nombre')
      ],
    ], [
      'newCategoriaNombre.required' => 'El nombre de la categoría es obligatorio.',
      'newCategoriaNombre.unique' => 'Esta categoría ya existe.'
    ]);

    try {
      $categoria = Categoria::create([
        'nombre' => $this->newCategoriaNombre,
      ]);

      $this->loadCategorias();
      $this->id_categoria = $categoria->id;
      $this->newCategoriaNombre = '';
      $this->agregandoCategoria = false;
      $this->resetValidation('newCategoriaNombre');
    } catch (\Exception $e) {
      \Log::error('Error al guardar nueva categoría: ' . $e->getMessage());
    }
  }

  public function eliminarProducto($id)
  {
    try {
      $producto = Producto::findOrFail($id);
      $producto->delete();

      $this->cargarProductos();
      session()->flash('message', 'Producto eliminado correctamente.');
    } catch (\Exception $e) {
      \Log::error('Error al eliminar producto: ' . $e->getMessage());
      session()->flash('error', 'Error al eliminar el producto.');
    }
  }

  public function render()
  {
    return view('livewire.l-productos');
  }
}
