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
        'scie',
        'ssci',
        'nature_index',
        'nature_scince',
        'other_organizations',
        'forbes_fortune',
        'international_patents',
        'year',
        'number',
        'pages',
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
        'publisher',
        'name_magazine',
        'doi',
        'date'
    ];

    function author() {
        return $this->hasManyThrough(
            'App\Models\Authors',
            'App\Models\AuthorsPublications',
            'publications_id',
            'id'
        );
    }

    function scienceType() {
        return $this->hasOne('App\Models\ScienceType', 'id');
    }

    function publicationType() {
        return $this->hasOne('App\Models\PublicationType', 'id');
    }
}
