<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abstracts extends Model
{
    protected $table = 'abstracts';

    protected $fillable = [
        'year',
        'name_conference',
        'publisher',
        'pages',
        'country',
        'city',
        'doi',
        'publications_id'
    ];
}
