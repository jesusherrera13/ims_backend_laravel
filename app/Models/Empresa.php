<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "empresas";
    protected $dates = ["deleted_at"];

    protected $fillable = [
        'razon_social',
        'nombre_comercial',
        'pais_id',
        'estado_id',
        'municipio_id',
        'ciudad_id',
        'calle',
        'numero_exterior',
        'numero_interior',
         'registro_patronal',
        'regimen_id', 
    ];

}