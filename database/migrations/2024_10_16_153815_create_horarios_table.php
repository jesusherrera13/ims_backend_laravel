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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medico_id')->constrained()->onDelete('cascade')->comment('se agrega la llave foranea de medico_id y se le asigna la accion de cascade para que se eliminen los registros relacionados en cascada si se elimina el registro de la tabla medico'); // 
            $table->foreignId('especialidad_id')->constrained()->onDelete('cascade')->comment('se agrega la llave foranea de especialidad_id y se le asigna la accion de cascade para que se eliminen los registros relacionados en cascada si se elimina el registro de la tabla especialidad'); // 
            $table->time('start_time')->comment('hora de inicio del horario');
            $table->time('end_time')->comment('hora de fin del horario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
