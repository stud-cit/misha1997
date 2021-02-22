<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LevelType extends Model
{
    protected $table = 'level_type';
    public $timestamps = false;

    protected $fillable = [
        'title'
    ];
}
