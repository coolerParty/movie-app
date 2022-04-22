<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $dates = [''];

    protected $fillable = [
        'tmdb_id',      
        'title' ,       
        'slug',         
        'runtime' ,     
        'rating' ,      
        'release_date' ,
        'lang'     ,    
        'video_format' ,
        'is_public'   , 
        'overview'  ,   
        'poster_path',  
        'backdrop_path'
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_movie');
    }

    public function trailers()
    {
        return $this->morphMany(TrailerUrl::class, 'trailerable');
    }

}
