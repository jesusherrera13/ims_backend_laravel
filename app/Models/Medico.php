<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'rfc',
        'direccion',
        'cp',
        'ciudad_id',
        'estado_id',
        'genero',
        'especialidad_id',
    ];


    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function especialidad()
    {
        return $this->belongsTo(EspecialidadMedica::class, 'especialidad_id');
    }
}