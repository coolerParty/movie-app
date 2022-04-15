<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Genre;
use Livewire\WithPagination;
use Illuminate\Support\Facades\HTTP;
use Illuminate\Support\Str;

class GenreIndex extends Component
{
    use WithPagination;

    protected $key = '5267a519dbe54ffbef5e4a2ede3f35b0';

    public $tmdbId;
    public $title;
    public $genreId;

    public $showGenreModal = false;

    protected $rules = [
        'title' => 'required',
    ];


    public function generateGenre()
    {

        $newGenre = HTTP::get('https://api.themoviedb.org/3/genre/movie/list?api_key=5267a519dbe54ffbef5e4a2ede3f35b0&language=en-US')
        ->json();

        $result = [];

        if(!collect($newGenre['genres'])->contains('id',$this->tmdbId))
        {
            $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Genre TMDB ID Error! Please enter correct TMDB ID.']);
            return;            
        }

        $result = collect($newGenre['genres'])->where('id',$this->tmdbId);
         
        foreach($result as $key => $ngGenre){
            // ['id'=>$value['id'],'genre'=>$value['name']];   

            // $result = [];
            // foreach($newGenre['genres'] as $key => $value){
            //     $result[] = ['id'=>$value['id'],'genre'=>$value['name']];        
            // }

            $genre = Genre::where('tmdb_id',$ngGenre['id'])->first();
            if(!$genre)
            {
                Genre::create([
                    'tmdb_id'     => $ngGenre['id'],
                    'title'        => $ngGenre['name'],
                    'slug'        => Str::slug($ngGenre['name']),
                ]);
                
                $this->reset();
                $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'New genre " ' . $ngGenre['name'] .' " Added successfully!']);
            }
            else
            {
                $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Genre " '. $genre->title.' " Exist!']);
            }
        }

    }


    public function showEditModal($id)
    {
        $this->genreId = $id;
        $this->loadGenre();
        $this->showGenreModal = true;

    }


    public function loadGenre()
    {
        $genre       = Genre::findOrFail($this->genreId);
        $this->title = $genre->title;
    }


    public function updateGenre()
    {
        $this->validate();
        $genre = Genre::findOrFail($this->genreId);
        $genre->update([
            'title' => $this->title,
        ]);
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Genre " ' . $genre->title .' " Updated successfully!']);
        $this->reset();
    }


    public function closeGenreModal()
    {
        $this->reset();
        $this->resetValidation();
    }


    public function deleteGenre($id)
    {
        $g = Genre::findOrFail($id);
        $cTitle = $g->title;
        $g->delete();
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Genre " ' . $cTitle .' " has been deleted successfully!']);
        $this->reset();
    }


    public function render()
    {
        return view('livewire.genre-index',[
            'genres' => Genre::paginate(5)
        ]);
    }

    
}
