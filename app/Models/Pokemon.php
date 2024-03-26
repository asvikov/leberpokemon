<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    protected $table = 'pokemones';

    protected $fillable = [
        'name',
        'image',
        'region_id'
    ];

    public function region() {

        return $this->belongsTo('App\Models\Region');
    }

    public function shapes() {

        return $this->belongsToMany('App\Models\Shape');
    }

    public function abilities() {

        return $this->belongsToMany('App\Models\Ability');
    }
}
