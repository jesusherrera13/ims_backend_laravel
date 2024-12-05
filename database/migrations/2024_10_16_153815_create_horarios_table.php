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
            $table->unsignedBigInteger('medico_id')->comment('identificador del medico');
            $table->unsignedBigInteger('especialidad_id')->comment('identificador de la especialidad');
            $table->foreign('medico_id')->references('id')->on('medicos')->onDelete('cascade');
            $table->foreign('especialidad_id')->references('id')->on('system_especialidades_medicas')->onDelete('cascade');
            $table->time('start_time')->comment('hora de inicio del horario');
            $table->time('end_time')->comment('hora de fin del horario');
            $table->integer('intervalo')->comment('intervalo de la cita en minutos');
           // $table->enum('day', ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'])->comment('dia de la semana');
            $table->boolean('vacaciones')->default(false)->comment('estado del horario por si esta de vacaciones'); // activo o inactivo por si esta de vacaciones
            $table->unique(['medico_id', 'especialidad_id', 'start_time', 'end_time'])->comment('unico por medico, especialidad, hora de inicio y fin');
            $table->softDeletes()->comment('fecha de eliminacion del horario');
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
