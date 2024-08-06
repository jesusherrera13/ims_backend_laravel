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
        Schema::create('system_modulos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('route')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedTinyInteger('orden')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_modulos');
    }
};
