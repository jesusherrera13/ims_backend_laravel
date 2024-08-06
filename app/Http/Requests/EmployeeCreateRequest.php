<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'required',
            'company_id' => 'required',
            'employee_name' => 'required',
            'employee_last_name' => 'required',
            'employee_second_last_name' => 'required',
            'employee_status' => 'required',
            'company_name' => 'required',
            'cost_center_id' => 'required',
            'cost_center_name' => 'required',
            'base_location_id' => 'required',
            'base_location_name' => 'required',
            'pay_location_id' => 'required',
            'pay_location_name' => 'required',
            'period_id' => 'required',
            'period_name' => 'required',
            'department_id' => 'required',
            'department_name' => 'required',
            'work_station_id' => 'required',
            'work_station_name' => 'required',
            'work_shift_id' => 'required',
            'work_shift_name' => 'required',
            'imss_regime_id' => 'required',
            'imss_regime_name' => 'required',
            'benefit_tabulator_id' => 'required',
            'benefit_tabulator_name' => 'required',
            'clasification_id' => 'required',
            'clasification_name' => 'required',
            'pay_method_id' => 'required',
            'pay_method_name' => 'required',
            'rfc' => 'required',
            'imss_number' => 'required',
            'blood_type' => 'required',
            'entry_date' => 'required',
            'group_entry_date' => 'required',
            'contract_end_date' => 'required',
            'unionized' => 'required',
            'employee_email' => 'required',
            'employee_personal_email' => 'required',
            'birth_place' => 'required',
            'birth_date' => 'required',
            'nationality' => 'required',
            'gender' => 'required',
            'street' => 'required',
            'district' => 'required',
            'postal_code' => 'required',
            'city' => 'required',
            'state' => 'required',
            'telephone_number' => 'required',
            'curp' => 'required',
            'marital_status' => 'required',
            'bank_id' => 'required',
            'bank_name' => 'required',
            'bank_account' => 'required',
            'contract_type' => 'required',
            'roll_id' => 'required',
            'roll_name' => 'required',
            'base_seniority' => 'required',
            'salary_type' => 'required',
            'salary_tabulator_id' => 'required',
            'salary_tabulator_name' => 'required',
            'salary_tabulator_level' => 'required',
            'salary_tabulator_level_name' => 'required',
            'business_name_id' => 'required',
            'business_name' => 'required',
            'business_unit_id' => 'required',
            'business_unit_name' => 'required',
            'alphanumeric_id_1' => 'required',
            'alphanumeric_id_2' => 'required',
            'alphanumeric_id_3' => 'required',
            'termination_date' => 'required',
            'business_rfc' => 'required',
            'payment_frequency' => 'required',
            'collaborator_id' => 'required',
            'daily_wage' => 'required',
            'monthly_salary' => 'required',
            'integrated_salary' => 'required',
            'integrated_salary_without_limit' => 'required',
            'fixed_factor' => 'required',
            'monthly_salary_avg' => 'required',
        ];
    }
}
