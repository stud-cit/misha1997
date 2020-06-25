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

    function author() {
        return $this->hasManyThrough(
            'App\Models\Authors',
            'App\Models\AuthorsPublications',
            'autors_id',
            'id'
        );
    }

    function type() {
        return $this->hasOne('App\Models\ScienceType', 'id');
    }

    function article() {
        return $this->hasOne('App\Models\Articles', 'publications_id');
    }
    function certificates() {
        return $this->hasOne('App\Models\Certificates', 'publications_id');
    }
    function abstracts() {
        return $this->hasOne('App\Models\Abstracts', 'publications_id');
    }
    function electronicPublications() {
        return $this->hasOne('App\Models\ElectronicPublications', 'publications_id');
    }
    function manuals() {
        return $this->hasOne('App\Models\Manuals', 'publications_id');
    }
    function methodicalInstructions() {
        return $this->hasOne('App\Models\MethodicalInstructions', 'publications_id');
    }
    function monographs() {
        return $this->hasOne('App\Models\Monographs', 'publications_id');
    }
    function patents() {
        return $this->hasOne('App\Models\Patents', 'publications_id');
    }
    function textbooks() {
        return $this->hasOne('App\Models\Textbooks', 'publications_id');
    }
}
