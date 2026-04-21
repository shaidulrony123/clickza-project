<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['badge', 'name', 'description', 'long_description', 'price', 'discount', 'image', 'tag','icon', 'live_link', 'status'];
}
