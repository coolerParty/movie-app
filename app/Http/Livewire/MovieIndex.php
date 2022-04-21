<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Movie;
use App\Models\Genre;
use Livewire\WithPagination;
use Illuminate\Support\Facades\HTTP;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MovieIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sortColumn = 'title';
    public $sortDirection = 'asc';
    public $perPage = '5';

    public $title;
    public $runtime;
    public $lang;
    public $videoFormat;
    public $rating;
    public $posterPath;
    public $backdropPath;
    public $overview;
    public $isPublic;
    public $tmdbId;
    public $movieId;

    public $showMovieModal = false;

    protected $rules = [
        'title'        => 'required',
        'runtime'      => 'required',
        'lang'         => 'required',
        'videoFormat'  => 'required',
        'rating'       => 'required',
        'posterPath'     => 'required',
        'backdropPath' => 'required',
        'overview'     => 'required',
        'isPublic'     => 'required',
    ];

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
            $created_movie = Movie::create([
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
            
            $tmdb_genres = $newMovie['genres'];
            $tmdb_genres_ids = collect($tmdb_genres)->pluck('id');
            $genres = Genre::whereIn('tmdb_id', $tmdb_genres_ids)->get();
            $created_movie->genres()->attach($genres);

            $this->reset('tmdbId');
            $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'New movie " ' . $newMovie['title'] .' " Added successfully!']);

        }
        else
        {

            $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Api not Exist!']);
            $this->reset('tmdbId');

        }
        
    } 

    public function sortByColumn($column)
    {
        if($this->sortColumn == $column)
        {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        }
        else
        {
            $this->sortDirection = 'asc';
        }
        $this->sortColumn = $column;
    }

    public function closeMovieModal()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function showEditModal($movieId)
    {
        $this->movieId = $movieId;
        $this->loadMovie();
        $this->showMovieModal = true;
    }

    public function loadMovie()
    {
        $movie              = Movie::findOrFail($this->movieId);
        $this->title        = $movie->title;
        $this->runtime      = $movie->runtime;
        $this->lang         = $movie->lang;
        $this->videoFormat  = $movie->video_format;
        $this->rating       = $movie->rating;
        $this->posterPath   = $movie->poster_path;
        $this->backdropPath = $movie->backdrop_path;
        $this->overview     = $movie->overview;
        $this->isPublic     = $movie->is_public;
    }

    public function updateMovie()
    {
        $this->validate();
        $movie = Movie::findOrFail($this->movieId);
        $movie->update([
            'title'         => $this->title,
            'slug'          => Str::slug($this->title),
            'runtime'       => $this->runtime,
            'lang'          => $this->lang,
            'video_format'  => $this->videoFormat,
            'rating'        => $this->rating,
            'poster_path'   => $this->posterPath,
            'backdrop_path' => $this->backdropPath,
            'overview'      => $this->overview,
            'is_public'     => $this->isPublic,
        ]);
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Movie " ' . $movie->title .' " Updated successfully!']);
        $this->reset();
    }

    public function deleteMovie($movieId)
    {
        $m = Movie::findOrFail($movieId);
        $mTitle = $m->title;
        $m->delete();
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Movie " ' . $mTitle .' " has been deleted successfully!']);
        $this->reset();
    }

    public function render()
    {
        return view('livewire.movie-index',[
            'movies' => Movie::search('title', $this->search)->orderBy($this->sortColumn , $this->sortDirection)->paginate($this->perPage),
        ]);
    }
}
