<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    protected $table = 'system_estados';

    //nombre
    protected $fillable = [
        'nombre',
        'codigo',
        'abreviacion',
        'pais_id'
    ];

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_id');
    }

    public function medicos()
    {
        return $this->hasMany(Medico::class);
    }
}