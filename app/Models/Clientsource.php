<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clientsource extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'work',
        'price',
    ];
}
