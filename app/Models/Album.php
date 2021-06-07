<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Album extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
    	return $this->hasOne(Category::class,'id','category_id');
    }

    public function albumimages()
    {
    	return $this->hasMany(Image::class,'album_id','id');
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
