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

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Listener para abrir el modal de nuevo producto
      Livewire.on('AbrirNuevoProducto', () => {
        // Crea una nueva instancia del modal de Bootstrap
        let modal = new bootstrap.Modal(document.getElementById('ModalNuevoProducto'));
        modal.show(); // Muestra el modal
      });

      // Listener para cerrar el modal de nuevo producto
      Livewire.on('CerrarNuevoProducto', () => {
        // Obtiene la instancia existente del modal de Bootstrap
        let modal = bootstrap.Modal.getInstance(document.getElementById('ModalNuevoProducto'));
        if (modal) { // Verifica si la instancia existe antes de intentar ocultarla
          modal.hide(); // Oculta el modal
        }
      });
    });
  </script>

  <div class="modal fade" id="ModalNuevoProducto" tabindex="-1" aria-labelledby="ModalNuevoProductoLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold">
            <i class="bi bi-box-seam-fill me-2"></i> Nuevo Producto
          </h5>
          <button type="button" class="btn-close" wire:click="cerrarModal"></button>
        </div>

        <form wire:submit.prevent="guardarProducto">
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Nombre</label>
                <input type="text" wire:model.defer="nombre" class="form-control" placeholder="Ej. Camisa">
                @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Precio</label>
                <input type="number" wire:model.defer="precio" step="0.01" class="form-control" placeholder="Ej. 29.99">
                @error('precio') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Categoría</label>
                <select wire:model.defer="id_categoria" class="form-select">
                  <option value="">-- Selecciona --</option>
                  @foreach($categorias as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                  @endforeach
                </select>
                @error('id_categoria') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Stock</label>
                <input type="number" wire:model.defer="stock" class="form-control" placeholder="Ej. 100">
                @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
              </div>
            </div>
          </div>

          <div class="modal-footer border-0">
            <button type="button" class="btn btn-custom-secondary" wire:click="cerrarModal">Cancelar</button>
            <button type="submit" class="btn btn-custom-primary px-4">
              <i class="bi bi-save me-1"></i> Guardar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>