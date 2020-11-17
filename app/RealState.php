<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RealState extends Model
{
    protected $appends = ['_links', 'thumb'];

    protected $fillable = [
        'title', 'description', 'content',
        'price', 'slug', 'bathrooms', 'bedrooms',
        'property_area', 'total_property_area'
    ];

    public function getLinksAttribute()
    {
        return [
            'href' => route('search.show', $this->id),
            'rel' => 'Imóvel'
        ];
    }

    public function getThumbAttribute()
    {
        $thumb = $this->photos()->where('is_thumb', true);

        if (!$thumb->count()) return null;

        return url($thumb->first()->photo);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function photos()
    {
        return $this->hasMany(RealStatePhoto::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
