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
        Schema::create('citas_efectivas', function (Blueprint $table) {
            $table->id();
            $table->string('paciente');
            $table->string('especialidadM');
            $table->string('doctor');
            $table->date('fecha');
            $table->string('efectividad');
            $table->timestamps();
        });
    }//

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas_efectivas');
    }
};
