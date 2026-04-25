<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'visitor_id',
        'ip',
        'country_code',
        'country_name',
        'user_agent',
        'path',
        'is_unique',
    ];
}
