<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
  public string $email = '';

  public function sendPasswordResetLink(): void
  {
    $this->validate([
      'email' => ['required', 'string', 'email'],
    ]);

    $status = Password::sendResetLink(
      $this->only('email')
    );

    if ($status != Password::RESET_LINK_SENT) {
      $this->addError('email', __($status));
      return;
    }

    $this->reset('email');
    session()->flash('status', __($status));
  }
};
?>

<div class="container py-5" style="max-width: 500px;">
  <div class="card shadow rounded-4 p-4 border-0">
    <h2 class="text-center mb-4" style="color: var(--ColorPrincipal); font-weight: bold;">
      <i class="fas fa-envelope me-2"></i> Recuperar Contraseña
    </h2>

    <div class="mb-3 text-muted text-sm">
      ¿Olvidaste tu contraseña? No hay problema. Ingresa tu correo electrónico y te enviaremos un enlace para restablecerla.
    </div>

    <!-- Estado de la sesión -->
    @if (session('status'))
    <div class="alert alert-success mb-3">
      {{ session('status') }}
    </div>
    @endif

    <form wire:submit.prevent="sendPasswordResetLink">
      <!-- Correo -->
      <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Correo electrónico</label>
        <input wire:model="email" type="email" id="email" name="email" class="form-control" required autofocus>
        @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror
      </div>

      <!-- Botón -->
      <div class="d-grid mt-4">
        <button type="submit" class="btn" style="background-color: var(--ColorPrincipal); color: var(--TextoClaro); font-weight: bold;">
          Enviar enlace de recuperación
        </button>
      </div>
    </form>
  </div>
</div>