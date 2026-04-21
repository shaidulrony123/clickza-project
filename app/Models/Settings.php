<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'header_logo',
        'footer_logo',
        'favicon',
        'email',
        'phone',
        'address',
        'copyright',
        'facebook',
        'twitter',
        'linkedin',
        'github',
        'youtube',
        'whatsapp',
    ];
}
