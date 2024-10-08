<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;

    protected $table = 'system_paises';

    //nombre
    protected $fillable = [
        'nombre',
        'codigo_iso_alfa2',
        'codigo_iso_alfa3',
        'codigo_iso_numerico'
    ];

    public function estados()
    {
        return $this->hasMany(Estado::class, 'pais_id');
    }
}