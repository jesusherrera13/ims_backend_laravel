<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceProviderModule extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'service_providers_modules';
    protected $fillable = ['name','service_provider_id'];

    public function modules(): BelongsTo
    {
        return $this->belongsTo(ServiceProvider::class);
    }
}
