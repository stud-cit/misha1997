<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditScopusExport extends Model
{
    protected $table = 'audit_scopus_export';

    protected $fillable = [
        'last_number',
        'total_count',
        'count'
    ];

    protected $hidden = ['updated_at'];

    protected $casts = [
      'created_at' => 'datetime:d.m.Y H:m'
    ];

}
