<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indicador extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "indicadores";
    protected $dates = ['deleted_at'];
    protected $fillable = [
        "departamento_id","nombre","forma_calculo","actividades","observaciones"
    ];
}
