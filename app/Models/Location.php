<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "locations";
    protected $fillable = [
        "company_id",
        "company_name",
        "location_id",
        "alphanumeric_id_1",
        "alphanumeric_id_2",
        "alphanumeric_id_3",
        "location_name",
        "alternative_location_name",
        "wage_zone_id",
        "state_register",
        "state_tax",
        "additional_tax",
        "accont_segment",
        "use_tax_table",
        "current_tax_table_id",
        "current_tax_table_name",
        "phone",
        "zone",
        "establishment_class_id",
        "establishment_class_name",
        "address_street",
        "address_neighborhood",
        "address_city",
        "address_state_id",
        "address_state_name",
        "address_postal_code",
        "representative_id",
        "representative_name",
        "representative_phone",
        "representative_phone_extension",
        "representative_rfc",
        "representative_email",
        "representative_curp",
        "representative_additional_address",
    ];
}
