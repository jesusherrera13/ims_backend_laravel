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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->string('company_name');
            $table->unsignedInteger('department_id');
            $table->string('department_name');
            $table->string('alternative_department_name')->nullable();
            $table->unsignedInteger('business_unit_id')->nullable();
            $table->string('business_unit_name')->nullable();
            $table->unsignedInteger('area_id')->nullable();
            $table->string('area_name')->nullable();
            $table->string('reference_1')->nullable();
            $table->string('reference_2')->nullable();
            $table->string('reference_3')->nullable();
            $table->string('reference_4')->nullable();
            $table->string('alphanumeric_id_1')->nullable();
            $table->string('alphanumeric_id_2')->nullable();
            $table->string('alphanumeric_id_3')->nullable();
            $table->string('applies_all_location')->nullable();
            $table->text('locations')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
