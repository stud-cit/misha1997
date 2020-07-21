<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Textbooks extends Model
{
    protected $table = 'textbooks';

    protected $fillable = [
        'year',
        'number_volumes',
        'volume',
        'by_editing',
        'country',
        'city',
        'editor_name',
        'number_pages',
        'languages',
        'doi',
        'publications_id'
    ];
}
