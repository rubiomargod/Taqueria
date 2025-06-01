<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
  public LoginForm $form;

  public function login(): void
  {
    $this->validate();
    $this->form->authenticate();
    Session::regenerate();

    $this->redirectIntended(default: route('INICIO', absolute: false), navigate: true);
  }
};
?>

<div class="container py-5" style="max-width: 500px;">
  <div class="card shadow rounded-4 p-4 border-0">
    <h2 class="text-center mb-4" style="color: var(--ColorPrincipal); font-weight: bold;">
      <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
    </h2>

    @if (session('status'))
    <div class="alert alert-success mb-3">
      {{ session('status') }}
    </div>
    @endif

    <form wire:submit.prevent="login">
      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Correo electrónico</label>
        <input wire:model="form.email" type="email" id="email" name="email" class="form-control" required autofocus>
        @error('form.email') <div class="text-danger mt-1">{{ $message }}</div> @enderror
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label for="password" class="form-label fw-semibold">Contraseña</label>
        <input wire:model="form.password" type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
        @error('form.password') <div class="text-danger mt-1">{{ $message }}</div> @enderror
      </div>

      <!-- Recordarme -->
      <div class="form-check mb-3">
        <input wire:model="form.remember" type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">Recordarme</label>
      </div>

      <!-- Enlaces -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: var(--ColorSecundario);">
          ¿Olvidaste tu contraseña?
        </a>
        @endif
      </div>

      <!-- Botones -->
      <div class="d-flex justify-content-between">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left me-1"></i> Volver atrás
        </a>

        <button type="submit" class="btn" style="background-color: var(--ColorPrincipal); color: var(--TextoClaro); font-weight: bold;">
          Iniciar Sesión
        </button>
      </div>
    </form>
  </div>
</div>