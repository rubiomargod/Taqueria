<div>
  <div class="container-fluid my-4" style="background-color: var(--FondoBase);">
    <h2 class="h3 fw-bold mb-4 text-center" style="color: var(--TextoOscuro);">
      <i class="bi bi-box-seam-fill me-2" style="color: var(--ColorPrincipal);"></i> Gestión de Productos
    </h2>

    <div class="row g-3 align-items-end mb-4">
      <div class="col-md-4">
        <label for="categoriaSelect" class="form-label fw-semibold" style="color: var(--TextoOscuro);">
          <i class="bi bi-tag-fill me-1" style="color: var(--ColorPrincipal);"></i> Filtrar por Categoría:
        </label>
        <select wire:model.live="categoriaSeleccionada" id="categoriaSelect" class="form-select shadow-sm">
          <option value="">-- Todas --</option>
          @foreach($categorias as $categoria)
          <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-auto d-flex gap-2 mt-4 mt-md-0">
        <button wire:click="cargarProductos" class="btn btn-primary shadow-sm" style="background-color: var(--ColorSecundario); color: var(--TextoOscuro);">
          <i class="bi bi-funnel-fill me-1"></i> Filtrar
        </button>
        <button wire:click="abrirModal()" class="btn btn-success shadow-sm" style="background-color: var(--ColorAcento); color: var(--TextoClaro);">
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
      <table class="table table-striped table-hover shadow-sm rounded-3 overflow-hidden">
        <thead class="table-dark">
          <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Precio</th>
            <th scope="col">Categoría</th>
            <th scope="col">Stock</th>
            <th scope="col" class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($productos as $producto)
          <tr>
            <td style="color: var(--TextoOscuro);">{{ $producto->nombre }}</td>
            <td style="color: var(--TextoOscuro);">${{ number_format($producto->precio, 2) }}</td>
            <td style="color: var(--TextoOscuro);">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</td>
            <td style="color: var(--TextoOscuro);">{{ $producto->stock }}</td>
            <td class="text-center d-flex gap-2 justify-content-center">
              <button wire:click="editarProducto({{ $producto->id }})"
                class="btn btn-sm btn-primary"
                style="background-color: var(--ColorSecundario); color: var(--TextoOscuro);">
                <i class="bi bi-pencil-fill"></i> Editar
              </button>
              <button wire:click="eliminarProducto({{ $producto->id }})"
                onclick="confirm('¿Estás seguro de eliminar este producto?') || event.stopImmediatePropagation()"
                class="btn btn-sm btn-danger">
                <i class="bi bi-trash-fill"></i> Eliminar
              </button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center py-4" style="color: var(--GrisNeutro);">
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
          <h5 class="modal-title fw-bold" style="color: var(--TextoOscuro);">
            <i class="bi bi-box-seam-fill me-2" style="color: var(--ColorPrincipal);"></i>
            {{ $productoId ? 'Editar Producto' : 'Nuevo Producto' }}
          </h5>
          <button type="button" class="btn-close" wire:click="cerrarModal"></button>
        </div>

        <form wire:submit.prevent="guardarProducto">
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold" style="color: var(--TextoOscuro);">Nombre</label>
                <input type="text" wire:model.defer="nombre" class="form-control" placeholder="Ej. Tacos">
                @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold" style="color: var(--TextoOscuro);">Precio</label>
                <input type="number" wire:model.defer="precio" step="0.01" class="form-control" placeholder="Ej. 29.99">
                @error('precio') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold" style="color: var(--TextoOscuro);">Categoría Existente</label>
                <select wire:model.defer="id_categoria" class="form-select">
                  <option value="">-- Selecciona --</option>
                  @foreach($categorias as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                  @endforeach
                </select>
                @error('id_categoria') <small class="text-danger">{{ $message }}</small> @enderror

                <div class="mt-3">
                  <button type="button" class="btn btn-link p-0 text-decoration-none fw-semibold"
                    style="color: var(--ColorPrincipal);"
                    wire:click="$set('agregandoCategoria', true)">
                    <i class="bi bi-plus-circle me-1"></i> ¿Añadir nueva categoría?
                  </button>
                </div>

                @if($agregandoCategoria)
                <div class="input-group mt-2">
                  <input type="text" wire:model.defer="newCategoriaNombre" class="form-control" placeholder="Nombre de la nueva categoría">
                  <button type="button" wire:click="guardarNuevaCategoria" class="btn btn-success" style="background-color: var(--ColorAcento); color: var(--TextoClaro);">
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
                <label class="form-label fw-semibold" style="color: var(--TextoOscuro);">Stock</label>
                <input type="number" wire:model.defer="stock" class="form-control" placeholder="Ej. 100">
                @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
              </div>
            </div>
          </div>

          <div class="modal-footer border-0">
            <button type="button" class="btn btn-secondary" style="background-color: var(--ColorSecundario); color: var(--TextoOscuro);" wire:click="cerrarModal">
              <i class="bi bi-x-circle-fill me-1"></i> Cancelar
            </button>
            <button type="submit" class="btn btn-primary px-4" style="background-color: var(--ColorAcento); color: var(--TextoClaro);">
              <i class="bi bi-save-fill me-1"></i> {{ $productoId ? 'Actualizar' : 'Guardar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>