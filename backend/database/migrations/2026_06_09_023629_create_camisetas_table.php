<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('camisetas', function (Blueprint $table) {
            $table->id();

            $table->string('titulo');
            $table->string('club');
            $table->string('pais');
            $table->string('tipo');
            $table->string('color');

            $table->unsignedInteger('precio');
            $table->unsignedInteger('precio_oferta')->nullable();

            $table->unsignedInteger('stock')->default(0);

            $table->text('detalles')->nullable();

            $table->string('codigo_producto')->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camisetas');
    }
};
