<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    protected $table = 'job_type';
    public $timestamps = false;

    protected $fillable = [
        'title'
    ];
}
