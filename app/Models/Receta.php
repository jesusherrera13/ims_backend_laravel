<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    use HasFactory;

    protected $table = 'recetas';
    protected $primaryKey = 'id_receta';

    protected $fillable = ['paciente_id', 'cita_id', 'especialidad_id', 'descrip', 'fecha', 'sta'];

    // Relación con Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    // Relación con Cita
    // public function cita()
    // {
    //     return $this->belongsTo(Cita::class, 'cita_id');
    // }

    // Relación con Especialidad
    public function especialidad()
    {
        return $this->belongsTo(EspecialidadMedica::class, 'especialidad_id');
    }
}