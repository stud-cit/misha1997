<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatentType extends Model
{
    use HasFactory;

    protected $table = 'patent_type';
    public $timestamps = false;

    protected $fillable = [
        'title'
    ];
}
