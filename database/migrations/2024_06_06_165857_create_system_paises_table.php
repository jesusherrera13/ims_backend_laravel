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
        Schema::create('system_paises', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo_iso_alfa2');
            $table->string('codigo_iso_alfa3');
            $table->string('codigo_iso_numerico');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_paises');
    }
};
