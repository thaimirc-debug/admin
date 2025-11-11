<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'category_id', 
        'title', 
        'description',
        'slug', 
        'content',
        'keywords',
        'image',
        'pin',
        'is_published', 
        'published_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
