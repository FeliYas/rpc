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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('alias')->nullable();
            $table->string('usuario')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('telefono')->nullable();
            $table->string('cuit')->nullable();
            $table->string('direccion')->nullable();
            $table->string('provincia')->nullable();
            $table->string('localidad')->nullable();
            $table->string('rol')->default('cliente')->nullable();
            $table->string('descuento')->default(0);
            $table->string('password')->nullable();
            $table->boolean('autorizado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
