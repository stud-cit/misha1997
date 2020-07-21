<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectronicPublications extends Model
{
    protected $table = 'electronic_publications';

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
        'access_mode',
        'publications_id'
    ];
}
