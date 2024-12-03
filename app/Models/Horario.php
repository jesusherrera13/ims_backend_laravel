<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
   protected $table = 'horarios';
    protected $fillable = [
        'start_time',
        'end_time',
    ];

    public function medicos()
    {
        return $this->hasMany(Medico::class);
    }

    public function especialidades()
    {
        return $this->hasMany(Especialidad::class);
    }

public function citas()
    {
        return $this->hasMany(Appointment::class);
    }

}
