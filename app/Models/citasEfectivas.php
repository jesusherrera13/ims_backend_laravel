<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class citasEfectivas extends Model
{
    use HasFactory;
    protected $table = "citas_efectivas";
    protected $fillable = [
        'paciente',
        'especialidadM',
        'doctor',
        'fecha',
        'efectividad',
    ];
}
