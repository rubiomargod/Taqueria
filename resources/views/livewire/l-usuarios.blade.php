<div class="p-4">
  <h2 class="text-xl font-bold mb-4">Gestión de Usuarios</h2>

  <button wire:click="abrirModal" class="bg-green-600 text-white px-4 py-2 rounded mb-4">
    + Agregar Usuario
  </button>

  <table class="w-full border border-gray-300 mb-6">
    <thead class="bg-gray-100">
      <tr>
        <th class="border p-2">Nombre</th>
        <th class="border p-2">Email</th>
        <th class="border p-2">Rol</th>
        <th class="border p-2 text-center">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($usuarios as $usuario)
      <tr>
        <td class="border p-2">{{ $usuario->name }}</td>
        <td class="border p-2">{{ $usuario->email }}</td>
        <td class="border p-2">{{ ucfirst($usuario->role) }}</td>
        <td class="border p-2 text-center space-x-2">
          <button wire:click="editarUsuario({{ $usuario->id }})" class="bg-yellow-500 text-white px-3 py-1 rounded">
            Editar
          </button>
          <button wire:click="eliminarUsuario({{ $usuario->id }})"
            onclick="confirm('¿Seguro que deseas eliminar este usuario?') || event.stopImmediatePropagation()"
            class="bg-red-600 text-white px-3 py-1 rounded">
            Eliminar
          </button>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="4" class="border p-2 text-center">No hay usuarios registrados.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

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
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold">
            {{ $usuarioId ? 'Editar Usuario' : 'Nuevo Usuario' }}
          </h5>
          <button type="button" class="btn-close" wire:click="cerrarModal"></button>
        </div>

        <form wire:submit.prevent="guardarUsuario">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Nombre</label>
              <input type="text" wire:model.defer="name" class="form-control">
              @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" wire:model.defer="email" class="form-control">
              @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Rol</label>
              <select wire:model.defer="role" class="form-select">
                <option value="">-- Selecciona Rol --</option>
                @foreach($roles as $rol)
                <option value="{{ $rol }}">{{ ucfirst($rol) }}</option>
                @endforeach
              </select>
              @error('role') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Contraseña</label>
              <input type="password" wire:model.defer="password" class="form-control">
              @error('password') <small class="text-danger">{{ $message }}</small> @enderror
              @if($usuarioId)
              <small class="text-muted">Déjalo vacío para mantener la contraseña actual.</small>
              @endif
            </div>
          </div>

          <div class="modal-footer border-0">
            <button type="button" class="btn btn-secondary" wire:click="cerrarModal">Cancelar</button>
            <button type="submit" class="btn btn-primary">
              {{ $usuarioId ? 'Actualizar' : 'Guardar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>