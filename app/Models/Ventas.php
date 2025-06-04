<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
  use HasFactory;

  protected $table = 'ventas';

  protected $fillable = ['comanda_id', 'total', 'user_id'];

  /**
   * Relación con la comanda.
   */
  public function usuario()
  {
    return $this->belongsTo(\App\Models\User::class, 'user_id');
  }

  public function comanda()
  {
    return $this->belongsTo(\App\Models\Comanda::class, 'comanda_id');
  }
}
