<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = 'audit';

    protected $fillable = [
        'text'
    ];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y',
        'updated_at' => 'datetime:m.d.Y',
    ];
}
