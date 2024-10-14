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
            $table -> foreignId('doctor_id')->references('id')->on('medicos');
            $table->dateTime('date');
            $table->time('hour');

            $table->timestamps();
        });

        {
            Schema::table('appointments', function (Blueprint $table) {
                $table->softDeletes(); // Agrega la columna deleted_at
            });
        }
    
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
