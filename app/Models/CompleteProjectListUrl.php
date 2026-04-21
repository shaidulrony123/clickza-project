<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompleteProjectListUrl extends Model
{
    protected $fillable = [
        'title',
        'image',
        'url',
        'status',
    ];
}
