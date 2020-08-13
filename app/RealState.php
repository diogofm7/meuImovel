<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RealState extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'content',
        'price', 'slug', 'bathrooms', 'bedrooms',
        'property_area', 'total_property_area'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
