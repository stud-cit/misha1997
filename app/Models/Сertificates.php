<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificates extends Model
{
    protected $table = 'certificates';

    protected $fillable = [
        'number',
        'country',
        'applicant',
        'date_application',
        'date_publication',
        'commerc_university',
        'commerc_employees',
        'publications_id'
    ];
}
