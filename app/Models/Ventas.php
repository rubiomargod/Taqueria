<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
  use HasFactory;

  protected $table = 'ventas';

  protected $fillable = [
    'comanda_id',
    'total',
  ];

  /**
   * Relación con la comanda.
   */
  public function comanda()
  {
    return $this->belongsTo(Comanda::class);
  }
}
