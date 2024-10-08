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
        Schema::create('medicos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('rfc')->unique();
            $table->string('direccion');
            $table->string('cp');
            // Claves foráneas
            $table->foreignId('ciudad_id')->nullable()->constrained('system_ciudades')->onDelete('set null');
            $table->foreignId('estado_id')->nullable()->constrained('system_estados')->onDelete('set null');
            $table->enum('genero', ['masculino', 'femenino', 'otro']);
            $table->foreignId('especialidad_id')->constrained('system_especialidades_medicas')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicos');
    }
};