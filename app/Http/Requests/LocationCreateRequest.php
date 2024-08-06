<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationCreateRequest extends FormRequest
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
            'location_id' => 'required',
            'alphanumeric_id_1' => 'required',
            'alphanumeric_id_2' => 'required',
            'alphanumeric_id_3' => 'required',
            'location_name' => 'required',
            'alternative_location_name' => 'required',
            'wage_zone_id' => 'required',
            'state_register' => 'required',
            'state_tax' => 'required',
            'additional_tax' => 'required',
            'accont_segment' => 'required',
            'use_tax_table' => 'required',
            'current_tax_table_id' => 'required',
            'current_tax_table_name' => 'required',
            'phone' => 'required',
            'zone' => 'required',
            'establishment_class_id' => 'required',
            'establishment_class_name' => 'required',
            'address_street' => 'required',
            'address_neighborhood' => 'required',
            'address_city' => 'required',
            'address_state_id' => 'required',
            'address_state_name' => 'required',
            'address_postal_code' => 'required',
            'representative_id' => 'required',
            'representative_name' => 'required',
            'representative_phone' => 'required',
            'representative_phone_extension' => 'required',
            'representative_rfc' => 'required',
            'representative_email' => 'required',
            'representative_curp' => 'required',
            'representative_additional_address' => 'required'
        ];
    }
}
