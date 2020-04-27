<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publications extends Model
{
    protected $table = 'publications';

    protected $fillable = [
        'title',
        'science_type_id',
        'impact_factor',
        'sub_db_index',
        'quartil',
        'department_id',
        'scie',
        'ssci',
        'nature_index',
        'nature_scince',
        'other_organizations',
        'forbes_fortune',
        'date',
        'international_patents',
        'snip'
    ];

    function type() {
        return $this->hasOne('App\Models\ScienceType', 'id');
    }

    function article() {
        return $this->hasOne('App\Models\Articles', 'id');
    }
}
