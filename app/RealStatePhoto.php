<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RealStatePhoto extends Model
{
    protected $appends = ['photo_url'];

    protected $fillable =[
        'photo', 'is_thumb'
    ];

    protected $hidden = ['photo'];

    public function getPhotoUrlAttribute()
    {
        return url('storage/'.$this->photo);
    }

    public function realState()
    {
        return $this->belongsTo(RealState::class);
    }
}
