<div class="p-4">
  <h2 class="text-xl font-bold mb-4">Filtrar Productos por Categoría</h2>

  <div class="flex flex-col md:flex-row md:items-end gap-4 mb-6">
    <div class="w-full md:w-1/3">
      <label class="block text-sm font-medium mb-1">Categoría:</label>
      <select wire:model.defer="categoriaSeleccionada" class="border p-2 rounded w-full">
        <option value="">-- Todas --</option>
        @foreach($categorias as $categoria)
        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
        @endforeach
      </select>
    </div>

    <button wire:click="cargarProductos" class="bg-blue-600 text-white px-4 py-2 rounded">
      Filtrar
    </button>

    <button wire:click="abrirModal" class="bg-green-600 text-white px-4 py-2 rounded">
      + Agregar Producto
    </button>
  </div>

  <table class="w-full border border-gray-300 mb-6">
    <thead class="bg-gray-100">
      <tr>
        <th class="border p-2">Nombre</th>
        <th class="border p-2">Precio</th>
        <th class="border p-2">Categoría</th>
        <th class="border p-2">Stock</th>
      </tr>
    </thead>
    <tbody>
      @forelse($productos as $producto)
      <tr>
        <td class="border p-2">{{ $producto->nombre }}</td>
        <td class="border p-2">${{ number_format($producto->precio, 2) }}</td>
        <td class="border p-2">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</td>
        <td class="border p-2">{{ $producto->stock }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="4" class="border p-2 text-center">No hay productos encontrados.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  {{-- MODAL --}}
  @if($showModal)
  <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
      <h3 class="text-lg font-bold mb-4">Nuevo Producto</h3>

      <div class="mb-3">
        <label class="block text-sm font-medium">Nombre</label>
        <input type="text" wire:model.defer="nombre" class="w-full p-2 border rounded">
        @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium">Precio</label>
        <input type="number" wire:model.defer="precio" step="0.01" class="w-full p-2 border rounded">
        @error('precio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium">Categoría</label>
        <select wire:model.defer="id_categoria" class="w-full p-2 border rounded">
          <option value="">-- Selecciona --</option>
          @foreach($categorias as $cat)
          <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
          @endforeach
        </select>
        @error('id_categoria') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium">Stock</label>
        <input type="number" wire:model.defer="stock" class="w-full p-2 border rounded">
        @error('stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
      </div>

      <div class="flex justify-end gap-2">
        <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-300 rounded">
          Cancelar
        </button>
        <button wire:click="guardarProducto" class="px-4 py-2 bg-blue-600 text-white rounded">
          Guardar
        </button>
      </div>
    </div>
  </div>
  @endif
</div>