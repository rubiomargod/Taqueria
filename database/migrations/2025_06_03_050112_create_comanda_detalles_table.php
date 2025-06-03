<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('comanda_detalle', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('id_comanda');
      $table->unsignedBigInteger('id_producto');
      $table->integer('cantidad');
      $table->timestamps();

      $table->foreign('id_comanda')->references('id')->on('comandas')->onDelete('cascade');
      $table->foreign('id_producto')->references('id')->on('productos')->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('comanda_detalle');
  }
};
