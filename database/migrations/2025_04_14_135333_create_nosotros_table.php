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
        Schema::create('nosotros', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('titulo');
            $table->mediumText('descripcion');
            $table->string('imagen1');
            $table->string('titulo1');
            $table->mediumText('descripcion1');
            $table->string('imagen2');
            $table->string('titulo2');
            $table->mediumText('descripcion2');
            $table->string('imagen3');
            $table->string('titulo3');
            $table->mediumText('descripcion3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nosotros');
    }
};
