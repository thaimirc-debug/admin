<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 
        's_id',
        'slug',
        'description', 
        'position', 
        'pin',
    ];

    public function children()
    {
        return $this->hasMany(Category::class, 's_id')->orderBy('position');
    }
}
