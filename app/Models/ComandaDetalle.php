<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComandaDetalle extends Model
{
  use HasFactory;

  protected $table = 'comanda_detalle'; // ← este nombre es clave

  protected $fillable = ['id_comanda', 'id_producto', 'cantidad'];

  public function producto()
  {
    return $this->belongsTo(Producto::class, 'id_producto'); // ← clave foránea personalizada
  }


  public function comanda()
  {
    return $this->belongsTo(Comanda::class, 'id_comanda');
  }
}
