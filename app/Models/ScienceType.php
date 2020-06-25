<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScienceType extends Model
{
    protected $table = 'science_type';
    public $timestamps = false;

    protected $fillable = [
        'type'
    ];
}
