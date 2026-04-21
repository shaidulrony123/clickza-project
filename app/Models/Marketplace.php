<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marketplace extends Model
{
    protected $fillable = ['user_name','description','url','badge','tag','item','sales','rating','is_active'];
}
