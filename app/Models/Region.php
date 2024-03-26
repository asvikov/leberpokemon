<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'geometry'
    ];
}
