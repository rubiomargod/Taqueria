<div class="p-4" style="background-color: var(--FondoBase);">
  <h2 class="h2 text-dark mb-4" style="color: var(--TextoOscuro);">
    <i class="bi bi-people-fill me-2" style="color: var(--ColorPrincipal);"></i>Gestión de Usuarios
  </h2>

  <button wire:click="abrirModal" class="btn text-white mb-4" style="background-color: var(--ColorAcento);">
    <i class="bi bi-person-plus-fill me-2"></i>+ Agregar Usuario
  </button>

  <div class="table-responsive">
    <table class="table table-hover table-bordered shadow-sm">
      <thead style="background-color: var(--ColorPrincipal); color: var(--TextoClaro);">
        <tr>
          <th scope="col">Nombre</th>
          <th scope="col">Email</th>
          <th scope="col">Rol</th>
          <th scope="col" class="text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($usuarios as $usuario)
        <tr>
          <td>{{ $usuario->name }}</td>
          <td>{{ $usuario->email }}</td>
          <td>{{ ucfirst($usuario->role) }}</td>
          <td class="text-center">
            <button wire:click="editarUsuario({{ $usuario->id }})" class="btn btn-sm me-2" style="background-color: var(--ColorSecundario); color: var(--TextoOscuro);" title="Editar">
              <i class="bi bi-pencil-square"></i>
            </button>
            <button wire:click="eliminarUsuario({{ $usuario->id }})"
              onclick="confirm('¿Seguro que deseas eliminar este usuario?') || event.stopImmediatePropagation()"
              class="btn btn-sm btn-danger" title="Eliminar">
              <i class="bi bi-trash-fill"></i>
            </button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" class="text-center text-muted">No hay usuarios registrados.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Livewire.on('AbrirModalUsuario', () => {
        new bootstrap.Modal(document.getElementById('ModalUsuario')).show();
      });

      Livewire.on('CerrarModalUsuario', () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('ModalUsuario'));
        if (modal) modal.hide();
      });
    });
  </script>

  <div class="modal fade" id="ModalUsuario" tabindex="-1" aria-labelledby="ModalUsuarioLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-md modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold" id="ModalUsuarioLabel" style="color: var(--TextoOscuro);">
            <i class="bi bi-person-circle me-2" style="color: var(--ColorPrincipal);"></i>{{ $usuarioId ? 'Editar Usuario' : 'Nuevo Usuario' }}
          </h5>
          <button type="button" class="btn-close" wire:click="cerrarModal" aria-label="Close"></button>
        </div>

        <form wire:submit.prevent="guardarUsuario">
          <div class="modal-body">
            <div class="mb-3">
              <label for="name" class="form-label fw-semibold" style="color: var(--TextoOscuro);">
                <i class="bi bi-person-fill me-1"></i>Nombre
              </label>
              <input type="text" wire:model.defer="name" class="form-control" id="name">
              @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
              <label for="email" class="form-label fw-semibold" style="color: var(--TextoOscuro);">
                <i class="bi bi-envelope-fill me-1"></i>Email
              </label>
              <input type="email" wire:model.defer="email" class="form-control" id="email">
              @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
              <label for="role" class="form-label fw-semibold" style="color: var(--TextoOscuro);">
                <i class="bi bi-person-badge-fill me-1"></i>Rol
              </label>
              <select wire:model.defer="role" class="form-select" id="role">
                <option value="">-- Selecciona Rol --</option>
                @foreach($roles as $rol)
                <option value="{{ $rol }}">{{ ucfirst($rol) }}</option>
                @endforeach
              </select>
              @error('role') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
              <label for="password" class="form-label fw-semibold" style="color: var(--TextoOscuro);">
                <i class="bi bi-key-fill me-1"></i>Contraseña
              </label>
              <input type="password" wire:model.defer="password" class="form-control" id="password">
              @error('password') <small class="text-danger">{{ $message }}</small> @enderror
              @if($usuarioId)
              <small class="form-text text-muted">Déjalo vacío para mantener la contraseña actual.</small>
              @endif
            </div>
          </div>

          <div class="modal-footer border-0 pt-0">
            <button type="button" class="btn btn-secondary" wire:click="cerrarModal">
              <i class="bi bi-x-circle me-1"></i>Cancelar
            </button>
            <button type="submit" class="btn text-white" style="background-color: var(--ColorAcento);">
              <i class="bi bi-save-fill me-1"></i>{{ $usuarioId ? 'Actualizar' : 'Guardar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>