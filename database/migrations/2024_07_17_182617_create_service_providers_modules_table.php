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
        Schema::create('service_providers_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('module');
            $table->text('url');
            $table->string('request_method');
            $table->foreignId('service_provider_id')->references('id')->on('service_providers');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_providers_modules');
    }
};
