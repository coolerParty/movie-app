<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Movie;
use Livewire\WithPagination;
use Illuminate\Support\Facades\HTTP;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MovieIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sort = 'asc';
    public $perPage = '5';

    public $title;
    public $runtime;
    public $lang;
    public $videoFormat;
    public $rating;
    public $postPath;
    public $backdropPath;
    public $overview;
    public $isPublic;
    public $tmdbId;

    public function generateMovie()
    {
        $movie = Movie::where('tmdb_id',$this->tmdbId)->exists();
        if($movie)
        {
            $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Movie already Exist!']);
            return;
        }

        $url = 'https://api.themoviedb.org/3/movie/'.$this->tmdbId.'?api_key=5267a519dbe54ffbef5e4a2ede3f35b0&language=en-US';
        $apiMovie = HTTP::get($url);

        if($apiMovie->successful())
        {
            $newMovie = $apiMovie->json();
            Movie::create([
                'tmdb_id'       => $newMovie['id'],
                'title'         => $newMovie['title'],
                'slug'          => Str::slug($newMovie['title']),
                'runtime'       => $newMovie['runtime'],
                'rating'        => $newMovie['vote_average'],
                'release_date'  => $newMovie['release_date'],
                'lang'          => $newMovie['original_language'],
                'video_format'  => 'HD',
                'is_public'     => false,
                'overview'      => $newMovie['overview'],
                'poster_path'   => $newMovie['poster_path'],
                'backdrop_path' => $newMovie['backdrop_path'],
            ]);
            
            $this->reset('tmdbId');
            $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'New movie " ' . $newMovie['title'] .' " Added successfully!']);

        }
        else
        {

            $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Api not Exist!']);
            $this->reset('tmdbId');

        }
        
    } 

    public function resetFilters()
    {
        $this->reset(['search','sort','perPage']);
    }

    public function render()
    {
        return view('livewire.movie-index',[
            'movies' => Movie::search('title', $this->search)->orderBy('title', $this->sort)->paginate($this->perPage),
        ]);
    }
}
