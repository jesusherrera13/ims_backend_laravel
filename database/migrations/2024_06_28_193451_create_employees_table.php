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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->foreignId('company_id')->references('id')->on('company');
            $table->string('employee_name');
            $table->string('employee_last_name');
            $table->string('employee_second_last_name');
            $table->string('employee_status')->nullable();
            $table->string('company_name')->nullable();
            $table->unsignedInteger('cost_center_id')->nullable();
            $table->string('cost_center_name')->nullable();
            $table->foreignId('base_location_id')->nullable();
            $table->string('base_location_name')->nullable();
            $table->foreignId('pay_location_id')->references('id')->on('locations');
            $table->string('pay_location_name')->nullable();
            $table->unsignedInteger('period_id')->nullable();
            $table->string('period_name')->nullable();
            $table->foreignId('department_id')->references('id')->on('departments');
            $table->string('department_name')->nullable();
            $table->unsignedInteger('work_station_id')->nullable();
            $table->string('work_station_name')->nullable();
            $table->unsignedInteger('work_shift_id')->nullable();
            $table->string('work_shift_name')->nullable();
            $table->unsignedInteger('imss_regime_id')->nullable();
            $table->string('imss_regime_name')->nullable();
            $table->unsignedInteger('benefit_tabulator_id')->nullable();
            $table->string('benefit_tabulator_name')->nullable();
            $table->unsignedInteger('clasification_id')->nullable();
            $table->string('clasification_name')->nullable();
            $table->unsignedInteger('pay_method_id')->nullable();
            $table->string('pay_method_name')->nullable();
            $table->string('rfc')->nullable();
            $table->string('imss_number')->nullable();
            $table->string('blood_type')->nullable();
            $table->date('entry_date')->nullable();
            $table->date('group_entry_date')->nullable();
            $table->date('contract_end_date')->nullable();
            $table->tinyInteger('unionized')->nullable();
            $table->string('employee_email')->nullable();
            $table->string('employee_personal_email')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('nationality')->nullable();
            $table->string('gender')->nullable();
            $table->string('street')->nullable();
            $table->string('inner_number')->nullable();
            $table->string('external_number')->nullable();
            $table->string('between_street')->nullable();
            $table->string('and_street')->nullable();
            $table->string('district')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('telephone_number')->nullable();
            $table->string('curp')->nullable();
            $table->string('marital_status')->nullable();
            $table->unsignedInteger('bank_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->unsignedInteger('contract_type')->nullable();
            $table->unsignedInteger('roll_id')->nullable();
            $table->string('roll_name')->nullable();
            $table->string('base_seniority')->nullable();
            $table->string('salary_type')->nullable();
            $table->unsignedInteger('salary_tabulator_id')->nullable();
            $table->string('salary_tabulator_name')->nullable();
            $table->unsignedInteger('salary_tabulator_level')->nullable();
            $table->string('salary_tabulator_level_name')->nullable();
            $table->unsignedInteger('business_name_id')->nullable();
            $table->string('business_name')->nullable();
            $table->unsignedInteger('business_unit_id')->nullable();
            $table->string('business_unit_name')->nullable();
            $table->string('alphanumeric_id_1')->nullable();
            $table->string('alphanumeric_id_2')->nullable();
            $table->string('alphanumeric_id_3')->nullable();
            $table->date('termination_date')->nullable();
            $table->string('business_rfc')->nullable();
            $table->unsignedInteger('payment_frequency')->nullable();
            $table->unsignedInteger('collaborator_id')->nullable();
            $table->decimal('daily_wage', 9, 2)->nullable();
            $table->decimal('monthly_salary', 9, 2)->nullable();
            $table->decimal('integrated_salary', 9, 2)->nullable();
            $table->decimal('integrated_salary_without_limit', 9, 2)->nullable();
            $table->float('fixed_factor')->nullable();
            $table->float('monthly_salary_avg', 9, 2)->nullable();
            $table->string('employee_picture')->nullable();
            $table->integer('regimen_sat')->nullable();
            $table->string('regimen_sat_nom')->nullable();
            $table->string('jornada_sat_nom')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
