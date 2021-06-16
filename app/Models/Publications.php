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
        'sub_db_scie',
        'sub_db_ssci',
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
        'out_data',
        'name_magazine',
        'doi',
        'nature_index',
        'nature_science',
        'db_scopus_percent',
        'db_wos_percent',
        'cited_international_patents',
        'name_monograph',
        'add_user_id',
        'edit_user_id',
        'not_previous_year',
        'not_this_year',
        'scopus_id',
        'verification'
    ];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y',
        'updated_at' => 'datetime:m.d.Y',
    ];

    function authors() {
        return $this->HasMany('App\Models\AuthorsPublications', 'publications_id');
    }

    function scienceType() {
        return $this->belongsTo('App\Models\ScienceType', 'science_type_id');
    }

    function publicationType() {
        return $this->belongsTo('App\Models\PublicationType', 'publication_type_id');
    }

    function publicationAdd() {
        return $this->belongsTo('App\Models\Authors', 'add_user_id');
    }

    function publicationEdit() {
        return $this->belongsTo('App\Models\Authors', 'edit_user_id');
    }
}
