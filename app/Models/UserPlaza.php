<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlaza extends Model
{
    use HasFactory;
    protected $table = "user_plazas";
    protected $fillable = ["user_id","plaza_id"];
}
