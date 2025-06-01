<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('Guest')] class extends Component
{
  public string $name = '';
  public string $email = '';
  public string $password = '';
  public string $password_confirmation = '';

  public function register(): void
  {
    $validated = $this->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
      'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
    ]);

    $validated['password'] = Hash::make($validated['password']);

    event(new Registered($user = User::create($validated)));

    Auth::login($user);

    $this->redirect(route('dashboard', absolute: false), navigate: true);
  }
};
?>

<div class="container py-5" style="max-width: 500px;">
  <div class="card shadow rounded-4 p-4 border-0">
    <h2 class="text-center mb-4" style="color: var(--ColorPrincipal); font-weight: bold;">
      <i class="fas fa-user-plus me-2"></i> Registro de Usuario
    </h2>

    <form wire:submit.prevent="register">
      <!-- Nombre -->
      <div class="mb-3">
        <label for="name" class="form-label fw-semibold">Nombre</label>
        <input wire:model="name" type="text" id="name" name="name" class="form-control" required autofocus autocomplete="name">
        @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
      </div>

      <!-- Correo -->
      <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Correo electrónico</label>
        <input wire:model="email" type="email" id="email" name="email" class="form-control" required autocomplete="username">
        @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror
      </div>

      <!-- Contraseña -->
      <div class="mb-3">
        <label for="password" class="form-label fw-semibold">Contraseña</label>
        <input wire:model="password" type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
        @error('password') <div class="text-danger mt-1">{{ $message }}</div> @enderror
      </div>

      <!-- Confirmar contraseña -->
      <div class="mb-4">
        <label for="password_confirmation" class="form-label fw-semibold">Confirmar contraseña</label>
        <input wire:model="password_confirmation" type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password">
        @error('password_confirmation') <div class="text-danger mt-1">{{ $message }}</div> @enderror
      </div>

      <!-- Enlace a login y botón -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--ColorSecundario);">
          ¿Ya estás registrado?
        </a>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn" style="background-color: var(--ColorPrincipal); color: var(--TextoClaro); font-weight: bold;">
          Registrarse
        </button>
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left me-1"></i> Volver atrás
        </a>
      </div>
    </form>
  </div>
</div>