<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessNameUpdateRequest extends FormRequest
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
            'business_name_id' => 'required',
            'business_name' => 'required',
            'company_id' => 'required',
            'short_name' => 'required',
            'rfc' => 'required',
            'line_of_business' => 'required',
            'country' => 'required',
            'country_id' => 'required',
            'state' => 'required',
            'city' => 'required',
            'municipality' => 'required',
            'neighborhood' => 'required',
            'phone_number' => 'required',
            'phone_number' => 'required',
            'street' => 'required',
            'interior_number' => 'required',
            'exterior_number' => 'required',
            'between_street' => 'required',
            'and_street' => 'required',
            'zip_code' => 'required',
            'representative_name' => 'required',
            'representative_rfc' => 'required',
            'representative_curp' => 'required',
            'ptu_decimals_for_factors' => 'required',
            'ptu_days_factor' => 'required',
            'ptu_income_limit' => 'required',
            'ptu_salary_factor' => 'required',
            'payment_policy' => 'required',
            'accounting_apportionment_applies' => 'required',
            'accounting_segment' => 'required'
        ];
    }
}
