<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipoIntegrante extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = "equipos_integrantes";
    protected $dates = ["deleted_at"];
    protected $fillable = ["equipo_id","user_id"];
}
