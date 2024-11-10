<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Horario extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'horarios';

    protected $fillable = [
        'start_time',
        'end_time',
        'intervalo',
        'active',
        'medico_id',
        'especialidad_id',
    ];

    // Definir la relación con el modelo Medico
    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    // Definir la relación con el modelo EspecialidadMedica
    public function especialidad()
    {
        return $this->belongsTo(EspecialidadMedica::class);
    }

    // Definir la relación con el modelo Appointment
    public function citas()
    {
        return $this->hasMany(Appointment::class);
    }
}
