<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comanda extends Model
{
  use HasFactory;

  protected $table = 'comandas';

  protected $fillable = ['id_mesero', 'id_mesa', 'fecha'];

  public function mesero()
  {
    return $this->belongsTo(User::class, 'id_mesero');
  }

  public function mesa()
  {
    return $this->belongsTo(Mesa::class, 'id_mesa');
  }
}
