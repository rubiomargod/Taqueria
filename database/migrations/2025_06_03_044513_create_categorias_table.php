<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id(); // equivalente a INT AUTO_INCREMENT PRIMARY KEY
            $table->string('nombre', 100);
            $table->timestamps(); // opcional, puedes quitar si no necesitas created_at / updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
