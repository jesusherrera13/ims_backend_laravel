<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    //nombre
    protected $fillable = [
        'nombre',
        'apellido1',
        'apellido2',
        'religion_id',
        'f_nacimiento',
        'domicilio',
        'foto_perfil'
    ];

    public function religion()
    {
        
        return $this->belongsTo(Religion::class, 'religion_id');
    }
}