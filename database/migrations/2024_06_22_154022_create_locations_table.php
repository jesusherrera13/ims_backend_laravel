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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->references('id')->on('company');
            $table->string('company_name');
            $table->unsignedBigInteger('location_id');
            $table->string('alphanumeric_id_1')->nullable();
            $table->string('alphanumeric_id_2')->nullable();
            $table->string('alphanumeric_id_3')->nullable();
            $table->string('location_name');
            $table->string('alternative_location_name')->nullable();
            $table->string('wage_zone_id')->nullable();
            $table->string('state_register')->nullable();
            $table->double('state_tax', 4, 2)->nullable();
            $table->string('additional_tax')->nullable();
            $table->string('accont_segment')->nullable();
            $table->double('use_tax_table', 4, 2)->nullable();
            $table->unsignedInteger('current_tax_table_id')->nullable();
            $table->string('current_tax_table_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('zone')->nullable();
            $table->unsignedInteger('establishment_class_id')->nullable();
            $table->string('establishment_class_name')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_neighborhood')->nullable();
            $table->string('address_city')->nullable();
            $table->unsignedInteger('address_state_id')->nullable();
            $table->string('address_state_name')->nullable();
            $table->string('address_postal_code')->nullable();
            $table->unsignedInteger('representative_id')->nullable();
            $table->string('representative_name')->nullable();
            $table->string('representative_phone')->nullable();
            $table->string('representative_phone_extension')->nullable();
            $table->string('representative_rfc')->nullable();
            $table->string('representative_email')->nullable();
            $table->string('representative_curp')->nullable();
            $table->string('representative_additional_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
