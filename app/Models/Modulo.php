<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modulo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "system_modulos";
    protected $dates = ["deleted_at"];
    protected $fillable = ["nombre","route","icon","parent_id","orden"];
}
