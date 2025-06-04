<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('comandas', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('id_mesero');
      $table->unsignedBigInteger('id_mesa');
      $table->string('estado')->default('activo');
      $table->dateTime('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
      $table->timestamps();

      $table->foreign('id_mesero')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('id_mesa')->references('id')->on('mesas')->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('comandas');
  }
};
