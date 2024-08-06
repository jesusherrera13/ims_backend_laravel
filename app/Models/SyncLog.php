<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    use HasFactory;

    protected $table = "sync_logs";
    protected $fillable = ["service_provider_id", "module", "status", "user_id"];
}
