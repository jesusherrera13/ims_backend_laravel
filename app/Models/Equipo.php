<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "equipos";
    protected $dates = ["deleted_at"];
    protected $fillable = ["nombre","departamento_id"];

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }
}
