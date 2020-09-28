<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publications extends Model
{
    protected $table = 'publications';

    protected $fillable = [
        'title',
        'science_type_id',
        'publication_type_id',
        'snip',
        'impact_factor',
        'quartil_scopus',
        'quartil_wos',
        'sub_db_index',
        'year',
        'number',
        'pages',
        'initials',
        'country',
        'number_volumes',
        'by_editing',
        'city',
        'editor_name',
        'languages',
        'number_certificate',
        'applicant',
        'date_application',
        'date_publication',
        'commerc_university',
        'commerc_employees',
        'access_mode',
        'mpk',
        'application_number',
        'newsletter_number',
        'name_conference',
        'url',
//        'publisher',
        'name_magazine',
        'doi',
        'supervisor_id'
    ];

    function supervisor() {
        return $this->belongsTo('App\Models\Authors', 'supervisor_id');
    }

    function authors() {
        return $this->HasMany('App\Models\AuthorsPublications', 'publications_id');
    }

    function scienceType() {
        return $this->belongsTo('App\Models\ScienceType', 'science_type_id');
    }

    function publicationType() {
        return $this->belongsTo('App\Models\PublicationType', 'publication_type_id');
    }
}
