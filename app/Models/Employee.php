<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "employees";
    protected $dates = ["deleted_at"];
    protected $fillable = [
        "employee_id","company_id","employee_name","employee_last_name","employee_second_last_name","employee_status","company_name","cost_center_id","cost_center_name",
        "base_location_id","base_location_name","pay_location_id","pay_location_name","period_id","period_name","department_id","department_name","work_station_id",
        "work_station_name","work_shift_id","work_shift_name","imss_regime_id","imss_regime_name","benefit_tabulator_id","benefit_tabulator_name","clasification_id",
        "clasification_name","pay_method_id","pay_method_name","rfc","imss_number","blood_type","entry_date","group_entry_date","contract_end_date","unionized","employee_email",
        "employee_personal_email","birth_place","birth_date","nationality","gender","street","inner_number","external_number","between_street","and_street","district","postal_code",
        "city","estado","telephone_number","curp","marital_status","bank_id","bank_name","bank_account","contract_type","roll_id","roll_name","base_seniority","salary_type","salary_tabulator_id",
        "salary_tabulator_name","salary_tabulator_level","salary_tabulator_level_name","business_name_id","business_name","business_unit_id","business_unit_name","alphanumeric_id_1","alphanumeric_id_2",
        "alphanumeric_id_3","termination_date","business_rfc","payment_frequency","collaborator_id","daily_wage","monthly_salary","integrated_salary","integrated_salary_without_limit","fixed_factor",
        "monthly_salary_avg","employee_picture","created_at","updated_at","regimen_sat","regimen_sat_nom","jornada_sat_nom"	
    ];
}
