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
        Schema::create('liquidaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->date('fecha_inicio'); // Fecha de inicio del período (semana/quincena)
            $table->date('fecha_fin'); // Fecha de fin del período
            $table->decimal('valor_hora_base', 10, 2)->nullable(); // Valor hora base del empleado en ese momento
            $table->decimal('total_horas_trabajadas', 8, 2)->default(0); // Total de horas trabajadas en el período
            $table->decimal('total_horas_extra', 8, 2)->default(0); // Total de horas extra
            $table->decimal('total_premios', 10, 2)->default(0); // Total de premios del período
            $table->decimal('subtotal_base', 10, 2)->default(0); // Subtotal por horas normales
            $table->decimal('subtotal_extra', 10, 2)->default(0); // Subtotal por horas extra
            $table->decimal('total_neto', 10, 2)->default(0); // Total neto a pagar
            $table->timestamps();
            
            $table->index(['fecha_inicio', 'fecha_fin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liquidaciones');
    }
};
