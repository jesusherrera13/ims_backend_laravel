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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->references('id')->on('pacientes');
            $table -> foreignId('medico_id')->references('id')->on('medicos');
            $table -> foreignId('especialidad_id')->references('id')->on('system_especialidades_medicas');
            $table->date('date');
            $table->time('hour');
            $table->softDeletes()->comment('ayuda a que no se elimine el registro de la base de datos');
            $table->timestamps();

        });
    
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Elimina la columna deleted_at
        });

        Schema::dropIfExists('appointments');
    }


    
};
