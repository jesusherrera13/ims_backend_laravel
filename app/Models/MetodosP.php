<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodosP extends Model
{
    use HasFactory;
    protected $table = "metodos_p_s";

    protected $fillable = [
        'nombre',
    ];
}
