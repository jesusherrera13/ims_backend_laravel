<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Actividad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "actividades";
    protected $dates = ["deleted_at"];
    protected $fillable = ["nombre","equipo_id","f_inicio","f_fin"];
}
