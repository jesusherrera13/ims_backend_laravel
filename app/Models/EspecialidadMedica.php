<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EspecialidadMedica extends Model
{
    use HasFactory;

    protected $table = 'system_especialidades_medicas';

    protected $fillable = ['nombre'];

  /*   public function medicos()
    {
        return $this->hasMany(Medico::class, 'especialidad_id');
    } */
}