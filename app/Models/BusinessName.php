<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessName extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "business_names";
    protected $fillable = [
        "business_name_id","business_name","company_id","company_name","short_name","rfc","line_of_business","phone_number","country",
        "country_id","state","city","municipality","neighborhood","street","interior_number","exterior_number","between_street","and_street",
        "zip_code","representative_name","representative_rfc","representative_curp","ptu_decimals_for_factors","ptu_days_factor",
        "ptu_income_limit","ptu_salary_factor","payment_policy","accounting_apportionment_applies","accounting_segment"
    ];
}
