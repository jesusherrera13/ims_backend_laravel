<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class citasE extends Model
{
    use HasFactory;
    protected $table = "citas_e_s";
    protected $fillable = [
        'paciente',
        'especialidadM',
        'doctor',
        'fecha',
        'efectividad',
    ];
}
