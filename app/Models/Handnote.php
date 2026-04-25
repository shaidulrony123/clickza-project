<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Handnote extends Model
{
    protected $fillable = [
        'title',
        'target',
        'note',
        'target_date',
        'status',
    ];
}
