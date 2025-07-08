<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('tipo', 20);
            $table->string('puntoventa', 10);
            $table->string('nrofactura', 20);
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->string('moneda', 3)->default('ARS');
            $table->decimal('tipo_cambio', 15, 4)->nullable();
            $table->decimal('importe_total', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
