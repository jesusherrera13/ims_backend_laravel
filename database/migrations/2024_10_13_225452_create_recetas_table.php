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
        Schema::create('recetas', function (Blueprint $table) {
            $table->id('id_receta');
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            //$table->foreignId('cita_id')->constrained('citas')->onDelete('cascade');
            $table->text('cita_id')->nullable();
            $table->foreignId('especialidad_id')->constrained('system_especialidades_medicas')->onDelete('cascade');
            $table->text('descrip');
            $table->date('fecha');
            $table->char('sta', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recetas');
    }
};