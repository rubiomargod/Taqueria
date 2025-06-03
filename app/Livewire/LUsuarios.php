<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LUsuarios extends Component
{
  public $usuarios = [];
  public $name, $email, $password, $role;
  public $usuarioId = null;
  public $roles = ['mesero', 'cajero', 'administrador'];

  public function mount()
  {
    $this->cargarUsuarios();
  }

  public function cargarUsuarios()
  {
    $this->usuarios = User::all();
  }

  public function abrirModal()
  {
    $this->reset(['name', 'email', 'password', 'role', 'usuarioId']);
    $this->dispatch('AbrirModalUsuario');
  }

  public function cerrarModal()
  {
    $this->dispatch('CerrarModalUsuario');
  }

  public function editarUsuario($id)
  {
    $usuario = User::findOrFail($id);
    $this->usuarioId = $usuario->id;
    $this->name = $usuario->name;
    $this->email = $usuario->email;
    $this->role = $usuario->role;
    $this->password = '';
    $this->dispatch('AbrirModalUsuario');
  }

  public function guardarUsuario()
  {
    $this->validate([
      'name' => 'required|string|max:100',
      'email' => 'required|email|unique:users,email,' . $this->usuarioId,
      'role' => 'required|in:mesero,cajero,administrador',
      'password' => $this->usuarioId ? 'nullable|min:6' : 'required|min:6',
    ]);

    if ($this->usuarioId) {
      $usuario = User::findOrFail($this->usuarioId);
      $usuario->update([
        'name' => $this->name,
        'email' => $this->email,
        'role' => $this->role,
        'password' => $this->password ? Hash::make($this->password) : $usuario->password
      ]);
    } else {
      User::create([
        'name' => $this->name,
        'email' => $this->email,
        'role' => $this->role,
        'password' => Hash::make($this->password)
      ]);
    }

    $this->cerrarModal();
    $this->cargarUsuarios();
  }

  public function eliminarUsuario($id)
  {
    $usuario = User::findOrFail($id);
    $usuario->delete();
    $this->cargarUsuarios();
  }

  public function render()
  {
    return view('livewire.l-usuarios');
  }
}
