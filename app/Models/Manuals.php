<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manuals extends Model
{
    protected $table = 'manuals';

    protected $fillable = [
        'year',
        'number_volumes',
        'volume',
        'country',
        'city',
        'editor_name',
        'number_pages',
        'languages',
        'doi',
        'publications_id'
    ];
}
