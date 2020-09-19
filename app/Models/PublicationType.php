<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicationType extends Model
{
    protected $table = 'publication_type';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'scopus_wos',
        'type'
    ];
}
