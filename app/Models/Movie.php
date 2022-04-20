<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

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
}
