<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "departments";
    protected $dates = ['deleted_at'];

    protected $fillable = [
        "company_id","company_name","department_id","department_name","alternative_department_name","business_unit_id",
        "business_unit_name","area_id","area_name","reference_1","reference_2","reference_3","reference_4","alphanumeric_id_1","alphanumeric_id_2",
        "applies_all_location","locations"
    ];
}
