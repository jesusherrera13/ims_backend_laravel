<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRol extends Model
{
    use HasFactory;
    protected $table = "system_users_roles";

    protected $fillable = [
        'nombre',
        'created_at',
        'updated_at'
    ];
}
