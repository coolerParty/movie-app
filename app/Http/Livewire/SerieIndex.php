<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Serie;
use Livewire\WithPagination;
use Illuminate\Support\Facades\HTTP;
use Illuminate\Support\Str;

class SerieIndex extends Component
{
    use WithPagination;

    protected $key = '5267a519dbe54ffbef5e4a2ede3f35b0';
    
    public $search = '';
    public $sort = 'asc';
    public $perPage = '5';

    public $tmdbId;
    public $name;
    public $serieId;
    
    public $showSerieModal = false;
    
    protected $rules = [
        'name' => 'required',
    ];

    public function generateSerie()
    {

        $newSerie = HTTP::get('https://api.themoviedb.org/3/tv/'.$this->tmdbId.'?api_key=5267a519dbe54ffbef5e4a2ede3f35b0&language=en-US')
        ->json();

            $serie = Serie::where('tmdb_id',$newSerie['id'])->first();
            if(!$serie)
            {
                Serie::create([
                    'tmdb_id'     => $newSerie['id'],
                    'name'        => $newSerie['name'],
                    'slug'        => Str::slug($newSerie['name']),
                    'created_year'=> $newSerie['first_air_date'],
                    'poster_path' => $newSerie['poster_path'],
                ]);
                
                $this->reset();
                $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'New serie " ' . $newSerie['name'] .' " Added successfully!']);
            }
            else
            {
                $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Serie " '. $serie->name.' " Exist!']);
            }
        

    }

    public function closeSerieModal()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function showEditModal($id)
    {
        $this->showSerieModal = true;
    }

    public function updateSeries()
    {

    }

    public function deleteSerie($id)
    {

    }

    public function resetFilters()
    {
        $this->reset(['search','sort','perPage']);
    }

    public function render()
    {
        return view('livewire.serie-index',[
            'series' => Serie::search('name', $this->search)->orderBy('name', $this->sort)->paginate($this->perPage),
        ]);
    }
}
