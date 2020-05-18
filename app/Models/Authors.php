<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Authors extends Model
{
    protected $table = 'authors';

    protected $fillable = [
        'guid',
        'name',
        'job',
        'country',
        'h_index',
        'scopus_autor_id',
        'scopus_researcher_id',
        'orcid',
        'department',
        'faculty',
        'is_student',
        'academic_code',
        'email',
        'roles_id'
    ];

    function role() {
        return $this->hasOne('App\Models\Roles', 'id');
    }

    function alias() {
        return $this->hasOne('App\Models\AutorsAliases', 'autors_id');
    }

    function notifications() {
        return $this->hasMany('App\Models\Notifications', 'autors_id');
    }

    function publications() {
        return $this->hasManyThrough(
            'App\Models\Publications',
            'App\Models\AuthorsPublications',
            'autors_id',
            'id'
        );
    }
}
