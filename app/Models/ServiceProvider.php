<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceProvider extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'service_providers';
    protected $fillable = ['name'];

    public function modules(): HasMany
    {
        return $this->hasMany(ServiceProviderModule::class);
    }
}
