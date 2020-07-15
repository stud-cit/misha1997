<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutorsAliases extends Model
{
    protected $table = 'autors_aliases';

    protected $fillable = [
        'surname_initials',
        'autors_id',
        'autors_aliases_id'
    ];

    function authors() {
        return $this->belongsTo('App\Models\Authors', 'autors_id');
    }
}
