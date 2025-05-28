<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factura_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained('facturas')->onDelete('cascade');
            $table->decimal('gravado', 15, 2);
            $table->decimal('iva_porcentaje', 5, 2); // Ej: 21.00
            $table->decimal('iva_monto', 15, 2);     // gravado * (iva_porcentaje / 100)
            $table->decimal('subtotal', 15, 2);      // gravado + iva_monto
            $table->timestamps();
        });
    }    public function down(): void
    {
        Schema::dropIfExists('factura_detalles');
    }
};
