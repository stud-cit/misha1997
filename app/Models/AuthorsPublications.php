<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorsPublications extends Model
{
    protected $table = 'authors_has_publications';
    public $timestamps = false;

    protected $fillable = [
        'autors_id',
        'publications_id'
    ];
}
