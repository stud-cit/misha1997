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

    function author() {
        return $this->belongsTo('App\Models\Authors', 'autors_id');
    }
    function publications() {
        return $this->belongsTo('App\Models\Publications', 'publications_id');
    }
}
