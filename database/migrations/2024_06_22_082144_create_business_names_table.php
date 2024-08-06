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
        Schema::create('business_names', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('business_name_id');
            $table->string('business_name');
            $table->foreignId('company_id')->references('id')->on('company');
            $table->string('company_name');
            $table->string('short_name')->nullable();
            $table->string('rfc')->nullable();
            $table->string('line_of_business')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('country')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('municipality')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('street')->nullable();
            $table->string('interior_number')->nullable();
            $table->string('exterior_number')->nullable();
            $table->string('between_street')->nullable();
            $table->string('and_street')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('representative_name')->nullable();
            $table->string('representative_rfc')->nullable();
            $table->string('representative_curp')->nullable();
            $table->unsignedTinyInteger('ptu_decimals_for_factors')->nullable();
            $table->double('ptu_days_factor', 9, 5)->nullable();
            $table->unsignedTinyInteger('ptu_income_limit')->nullable();
            $table->double('ptu_salary_factor', 6, 5)->nullable();
            $table->string('payment_policy')->nullable();
            $table->integer('accounting_apportionment_applies')->nullable();
            $table->string('accounting_segment')->nullable();	
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_names');
    }
};
