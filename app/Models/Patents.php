<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patents extends Model
{
    protected $table = 'patents';

    protected $fillable = [
        'number',
        'country',
        'mpk',
        'applicant',
        'application_number',
        'date_application',
        'date_publication',
        'newsletter_number',
        'commerc_university',
        'commerc_employees',
        'publications_id'
    ];
}
