<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'appointments';

    protected $fillable = [
        'patient_id',
        'medico_id',
        'especialidad_id',
        'date',
        'hour'
    ];

    public function patient()
    {
        return $this->belongsTo(Paciente::class);
    }

    /**
     * Get the medico that owns the appointment.
     */
    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    public function especialidad()
    {
        return $this->belongsTo(EspecialidadMedica::class);
    }

}
