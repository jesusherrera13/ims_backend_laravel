<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Periodo extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "periodos";
    protected $dates = ["deleted_at"];
    protected $fillable = ["f_inicio","f_fin"];
}
