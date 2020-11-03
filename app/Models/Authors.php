<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Authors extends Model
{
    protected $table = 'authors';

    protected $fillable = [
        'guid',
        'name',
        'date_bth',
        'job',
        'faculty_code',
        'department_code',
        'country',
        'h_index',
        'scopus_autor_id',
        'scopus_researcher_id',
        'orcid',
        'academic_code',
        'roles_id',
        'categ_1',
        'categ_2',
        'five_publications',
        'forbes_fortune',
        'token'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    function role() {
        return $this->belongsTo('App\Models\Roles', 'roles_id');
    }

    function notifications() {
        return $this->hasMany('App\Models\Notifications', 'autors_id');
    }

    function publications() {
        return $this->HasMany('App\Models\AuthorsPublications', 'autors_id');
    }
}
