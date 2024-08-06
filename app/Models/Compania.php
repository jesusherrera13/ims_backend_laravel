<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compania extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "company";
    protected $dates = ["deleted_at"];
    protected $fillable = ["company_id","company_name"];
}
