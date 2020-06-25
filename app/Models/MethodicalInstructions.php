<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MethodicalInstructions extends Model
{
    protected $table = 'methodical_instructions';

    protected $fillable = [
        'year',
        'country',
        'city',
        'editor_name',
        'number_pages',
        'publications_id'
    ];
}
