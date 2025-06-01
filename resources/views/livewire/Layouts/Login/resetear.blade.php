<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
  #[Locked]
  public string $token = '';
  public string $email = '';
  public string $password = '';
  public string $password_confirmation = '';

  public function mount(string $token): void
  {
    $this->token = $token;
    $this->email = request()->string('email');
  }

  public function resetPassword(): void
  {
    $this->validate([
      'token' => ['required'],
      'email' => ['required', 'string', 'email'],
      'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
    ]);

    $status = Password::reset(
      $this->only('email', 'password', 'password_confirmation', 'token'),
      function ($user) {
        $user->forceFill([
          'password' => Hash::make($this->password),
          'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));
      }
    );

    if ($status != Password::PASSWORD_RESET) {
      $this->addError('email', __($status));
      return;
    }

    Session::flash('status', __($status));
    $this->redirectRoute('login', navigate: true);
  }
};
?>

<div class="container py-5" style="max-width: 500px;">
  <div class="card shadow rounded-4 p-4 border-0">
    <h2 class="text-center mb-4" style="color: var(--ColorPrincipal); font-weight: bold;">
      <i class="fas fa-lock me-2"></i> Restablecer Contraseña
    </h2>

    <form wire:submit.prevent="resetPassword">
      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Correo electrónico</label>
        <input wire:model="email" type="email" id="email" name="email" class="form-control" required autofocus autocomplete="username">
        @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror
      </div>

      <!-- Contraseña -->
      <div class="mb-3">
        <label for="password" class="form-label fw-semibold">Nueva contraseña</label>
        <input wire:model="password" type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
        @error('password') <div class="text-danger mt-1">{{ $message }}</div> @enderror
      </div>

      <!-- Confirmar contraseña -->
      <div class="mb-4">
        <label for="password_confirmation" class="form-label fw-semibold">Confirmar contraseña</label>
        <input wire:model="password_confirmation" type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password">
        @error('password_confirmation') <div class="text-danger mt-1">{{ $message }}</div> @enderror
      </div>

      <!-- Botón -->
      <div class="d-grid">
        <button type="submit" class="btn" style="background-color: var(--ColorPrincipal); color: var(--TextoClaro); font-weight: bold;">
          Restablecer Contraseña
        </button>
      </div>
    </form>
  </div>
</div>