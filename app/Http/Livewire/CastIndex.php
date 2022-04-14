<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cast;
use Livewire\WithPagination;
use Illuminate\Support\Facades\HTTP;
use Illuminate\Support\Str;

class CastIndex extends Component
{
    use WithPagination;

    protected $key = '5267a519dbe54ffbef5e4a2ede3f35b0';

    public $castTMDBId;
    public $castName;
    public $castPosterPath;

    public function generateCast()
    {
        $newCast = HTTP::get('https://api.themoviedb.org/3/person/10859?api_key=5267a519dbe54ffbef5e4a2ede3f35b0')
        ->json();

        Cast::create([
            'tmdb_id'     => $newCast['id'],
            'name'        => $newCast['name'],
            'slug'        => Str::slug($newCast['name']),
            'poster_path' => $newCast['profile_path'],
        ]);
        
    }

    public function render()
    {
        return view('livewire.cast-index',[
            'casts' => Cast::paginate(5)
        ]);
    }
}
