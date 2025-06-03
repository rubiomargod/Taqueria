<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Validation\Rule; // Importar para la regla unique

class LProductos extends Component
{
  // Propiedades principales para la tabla y filtros
  public $productos = [];
  public $categorias = [];
  public $categoriaSeleccionada = '';

  // Propiedades del formulario del modal (Producto)
  public $productoId = null; // null para nuevo, ID para editar
  public $nombre, $precio, $id_categoria, $stock;

  // Propiedades para la funcionalidad de agregar nueva categoría
  public $newCategoriaNombre = ''; // <-- ¡CORREGIDO! Usar el mismo nombre que en el HTML
  public $agregandoCategoria = false; // Controla la visibilidad del input de nueva categoría

  // Propiedad para el título del modal (simplificado)
  // Ya no necesitas 'editando', 'productoId' se encarga de esto.
  // public $editando = false; // Esta línea ya no es necesaria

  // Hook: Se ejecuta cuando se actualiza la propiedad categoriaSeleccionada
  public function updatedCategoriaSeleccionada()
  {
    $this->cargarProductos();
  }

  // Carga los productos según el filtro de categoría
  public function cargarProductos()
  {
    $this->productos = Producto::with('categoria')
      ->when($this->categoriaSeleccionada, function ($query) {
        $query->where('id_categoria', $this->categoriaSeleccionada);
      })
      ->orderBy('nombre') // Opcional: ordenar productos
      ->get();
  }

  // Hook: Se ejecuta al inicializar el componente
  public function mount()
  {
    $this->loadCategorias(); // Carga las categorías al inicio
    $this->cargarProductos(); // Carga los productos iniciales
  }

  // Método auxiliar para cargar las categorías (mejorado)
  protected function loadCategorias()
  {
    $this->categorias = Categoria::orderBy('nombre')->get(); // Ordenar por nombre para mejor UX
  }

  // Abre el modal para agregar un nuevo producto
  public function abrirModal()
  {
    // Resetea todas las propiedades del formulario del producto y nueva categoría
    $this->reset([
      'productoId',
      'nombre',
      'precio',
      'id_categoria',
      'stock',
      'newCategoriaNombre',
      'agregandoCategoria'
    ]);
    $this->resetValidation(); // Limpia los errores de validación
    $this->dispatch('AbrirModalProducto'); // Emite el evento para abrir el modal
  }

  // Cierra el modal
  public function cerrarModal()
  {
    // Resetea todas las propiedades del formulario y errores
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
    $this->dispatch('CerrarModalProducto'); // Emite el evento para cerrar el modal
  }

  // Edita un producto existente
  public function editarProducto($id)
  {
    $this->resetValidation(); // Limpia errores de validación anteriores
    $this->agregandoCategoria = false; // Asegura que el campo de nueva categoría esté oculto al editar

    $producto = Producto::findOrFail($id);
    $this->productoId = $producto->id;
    $this->nombre = $producto->nombre;
    $this->precio = $producto->precio;
    $this->id_categoria = $producto->id_categoria;
    $this->stock = $producto->stock;

    $this->dispatch('AbrirModalProducto'); // Abre el modal
  }

  // Guarda un nuevo producto o actualiza uno existente
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
        // Actualiza el producto existente
        Producto::findOrFail($this->productoId)->update([
          'nombre' => $this->nombre,
          'precio' => $this->precio,
          'id_categoria' => $this->id_categoria,
          'stock' => $this->stock,
        ]);
        //session()->flash('message', 'Producto actualizado exitosamente!');
      } else {
        // Crea un nuevo producto
        Producto::create([
          'nombre' => $this->nombre,
          'precio' => $this->precio,
          'id_categoria' => $this->id_categoria,
          'stock' => $this->stock,
        ]);
        //session()->flash('message', 'Producto agregado exitosamente!');
      }

      $this->cerrarModal(); // Cierra el modal
      $this->cargarProductos(); // Recarga la lista de productos
    } catch (\Exception $e) {
      //session()->flash('error', 'Error al guardar el producto: ' . $e->getMessage());
      \Log::error('Error al guardar producto: ' . $e->getMessage());
    }
  }

  // Guarda una nueva categoría
  public function guardarNuevaCategoria()
  {
    $this->validate([
      'newCategoriaNombre' => [ // <-- ¡CORREGIDO! Usar 'newCategoriaNombre'
        'required',
        'string',
        'max:100',
        Rule::unique('categorias', 'nombre'),
      ],
    ], [
      'newCategoriaNombre.required' => 'El nombre de la categoría es obligatorio.',
      'newCategoriaNombre.unique' => 'Esta categoría ya existe.',
    ]);

    try {
      $categoria = Categoria::create([
        'nombre' => $this->newCategoriaNombre, // <-- ¡CORREGIDO! Usar 'newCategoriaNombre'
      ]);

      $this->loadCategorias(); // Recarga la lista de categorías para el select
      $this->id_categoria = $categoria->id; // Selecciona automáticamente la nueva categoría en el dropdown del producto
      $this->newCategoriaNombre = ''; // Limpia el input de nueva categoría
      $this->agregandoCategoria = false; // Oculta el campo de nueva categoría
      $this->resetValidation('newCategoriaNombre'); // Limpia los errores de validación de este campo específico

      //session()->flash('message', 'Categoría "' . $categoria->nombre . '" añadida exitosamente!');
    } catch (\Exception $e) {
      //session()->flash('error', 'Error al añadir la categoría: ' . $e->getMessage());
      \Log::error('Error al guardar nueva categoría: ' . $e->getMessage());
    }
  }

  public function render()
  {
    return view('livewire.l-productos');
  }
}
