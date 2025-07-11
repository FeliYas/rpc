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
        Schema::create('liquidacion_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('liquidacion_id')->constrained('liquidaciones')->onDelete('cascade');
            $table->date('fecha'); // Fecha específica del día
            $table->enum('dia_semana', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo']);
            $table->enum('estado', ['presente', 'ausente', 'feriado', 'vacaciones'])->default('presente');
            $table->decimal('horas_trabajadas', 8, 2)->default(0); // Horas trabajadas ese día
            $table->decimal('horas_extra', 8, 2)->default(0); // Horas extra ese día
            $table->decimal('valor_hora', 10, 2)->nullable(); // Valor hora para ese día (puede variar)
            $table->decimal('valor_hora_extra', 10, 2)->nullable(); // Valor hora extra (generalmente 1.5x)
            $table->decimal('premio_dia', 10, 2)->default(0); // Premio específico del día
            $table->decimal('subtotal_normal', 10, 2)->default(0); // Subtotal horas normales del día
            $table->decimal('subtotal_extra', 10, 2)->default(0); // Subtotal horas extra del día
            $table->decimal('total_dia', 10, 2)->default(0); // Total del día (normal + extra + premio)
            $table->timestamps();
            
            // Índices para mejorar consultas
            $table->index(['liquidacion_id', 'fecha']);
            $table->index(['dia_semana', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liquidacion_detalles');
    }
};
