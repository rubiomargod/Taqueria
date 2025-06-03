<div>
  <div class="container-fluid container-custom my-4">
    <h2 class="h3 fw-bold mb-4 text-center">
      <i class="bi bi-box-seam me-2"></i> Gestión de Productos
    </h2>

    <div class="row g-3 align-items-end mb-4">
      <div class="col-md-4">
        <label for="categoriaSelect" class="form-label fw-semibold">
          <i class="bi bi-tag me-1"></i> Filtrar por Categoría:
        </label>
        <select wire:model.live="categoriaSeleccionada" id="categoriaSelect" class="form-select shadow-sm">
          <option value="">-- Todas --</option>
          @foreach($categorias as $categoria)
          <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-auto d-flex gap-2 mt-4 mt-md-0">
        <button wire:click="cargarProductos" class="btn btn-custom-filter shadow-sm">
          <i class="bi bi-funnel-fill me-1"></i> Filtrar
        </button>
        <button wire:click="abrirModal()" class="btn btn-custom-accent shadow-sm">
          <i class="bi bi-plus-circle-fill me-1"></i> Agregar Producto
        </button>
      </div>
    </div>

    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="table-responsive">
      <table class="table table-striped table-hover table-custom shadow-sm rounded-3 overflow-hidden">
        <thead class="table-dark">
          <tr>
            <th scope="col" class="text-white">Nombre</th>
            <th scope="col" class="text-white">Precio</th>
            <th scope="col" class="text-white">Categoría</th>
            <th scope="col" class="text-white">Stock</th>
            <th scope="col" class="text-center text-white">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($productos as $producto)
          <tr>
            <td>{{ $producto->nombre }}</td>
            <td>${{ number_format($producto->precio, 2) }}</td>
            <td>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</td>
            <td>{{ $producto->stock }}</td>
            <td class="text-center">
              <button wire:click="abrirModal({{ $producto->id }})" class="btn btn-sm btn-custom-edit">
                <i class="bi bi-pencil-fill"></i> Editar
              </button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center py-4">
              <i class="bi bi-info-circle-fill me-1"></i> No hay productos encontrados.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Renombré los eventos para que sean más genéricos para el modal de productos
      Livewire.on('AbrirModalProducto', () => {
        let modal = new bootstrap.Modal(document.getElementById('ModalNuevoProducto'));
        modal.show();
      });

      Livewire.on('CerrarModalProducto', () => {
        let modal = bootstrap.Modal.getInstance(document.getElementById('ModalNuevoProducto'));
        if (modal) {
          modal.hide();
        }
      });
    });
  </script>

  <div class="modal fade" id="ModalNuevoProducto" tabindex="-1" aria-labelledby="ModalNuevoProductoLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold">
            <i class="bi bi-box-seam-fill me-2"></i>
            {{ $productoId ? 'Editar Producto' : 'Nuevo Producto' }}
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
                <label class="form-label fw-semibold">Categoría Existente</label>
                <select wire:model.defer="id_categoria" class="form-select">
                  <option value="">-- Selecciona --</option>
                  @foreach($categorias as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                  @endforeach
                </select>
                @error('id_categoria') <small class="text-danger">{{ $message }}</small> @enderror

                <div class="mt-3">
                  <button type="button" class="btn btn-link p-0 text-decoration-none fw-semibold" wire:click="$set('agregandoCategoria', true)">
                    <i class="bi bi-plus-circle me-1"></i> ¿Añadir nueva categoría?
                  </button>
                </div>

                @if($agregandoCategoria)
                <div class="input-group mt-2">
                  <input type="text" wire:model.defer="newCategoriaNombre" class="form-control" placeholder="Nombre de la nueva categoría">
                  <button type="button" wire:click="guardarNuevaCategoria" class="btn btn-custom-accent">
                    <i class="bi bi-plus-circle me-1"></i> Añadir
                  </button>
                  <button type="button" wire:click="$set('agregandoCategoria', false)" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i>
                  </button>
                </div>
                @error('newCategoriaNombre') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                @endif
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Stock</label>
                <input type="number" wire:model.defer="stock" class="form-control" placeholder="Ej. 100">
                @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
              </div>
            </div>
          </div>

          <div class="modal-footer border-0">
            <button type="button" class="btn btn-custom-secondary" wire:click="cerrarModal">
              <i class="bi bi-x-circle me-1"></i> Cancelar
            </button>
            <button type="submit" class="btn btn-custom-primary px-4">
              <i class="bi bi-save me-1"></i> {{ $productoId ? 'Actualizar' : 'Guardar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>