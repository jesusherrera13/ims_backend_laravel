<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social');
            $table->string('nombre_comercial');
            $table->string('rfc');
            $table->foreignId('pais_id')->references('id')->on('system_paises');
            $table->foreignId('estado_id')->references('id')->on('system_estados');
            $table->foreignId('municipio_id')->references('id')->on('system_municipios');
            $table->foreignId('ciudad_id')->references('id')->on('system_ciudades');
            $table->string('calle');
            $table->string('numero_exterior');
            $table->string('numero_interior');
            $table->string('registro_patronal');
            $table->integer('regimen_id')->nullable();
            $table->foreignId('company_id')->references('id')->on('company');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};