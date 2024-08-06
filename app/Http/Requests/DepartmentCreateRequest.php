<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentCreateRequest extends FormRequest
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
            'company_id' => 'required',
            'company_name' => 'required',
            'department_id' => 'required',
            'department_name' => 'required',
            'alternative_department_name' => 'required',
            'business_unit_id' => 'required',
            'business_unit_name' => 'required',
            'area_id' => 'required',
            'area_name' => 'required',
            'reference_1' => 'required',
            'reference_2' => 'required',
            'reference_3' => 'required',
            'reference_4' => 'required',
            'alphanumeric_id_1' => 'required',
            'alphanumeric_id_2' => 'required',
            'alphanumeric_id_3' => 'required',
            'applies_all_location' => 'required',
            'locations' => 'required'
        ];
    }
}
