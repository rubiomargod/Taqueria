<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  /**
   * Roles disponibles para los usuarios.
   */
  public const ROLE_ADMINISTRADOR = 'Administrador';
  public const ROLE_CAJERO = 'Cajero';
  public const ROLE_MESERO = 'Mesero';

  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'role', // Nuevo campo agregado
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var list<string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  /**
   * Verifica si el usuario tiene un rol específico.
   */
  public function hasRole(string $role): bool
  {
    return $this->role === $role;
  }
}
