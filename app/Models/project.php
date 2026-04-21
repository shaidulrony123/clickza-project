<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class project extends Model
{
    protected $fillable = [
    'title',
    'description',
    'teach_stack',
    'image',
    'project_link',
    'github_link',
    'category_id',
    'status',
    'views'
];
public function category()
{
    return $this->belongsTo(Category::class);
}
}
