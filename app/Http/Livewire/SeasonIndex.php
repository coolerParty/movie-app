<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Season;
use Livewire\WithPagination;
use Illuminate\Support\Facades\HTTP;
use Illuminate\Support\Str;

class SeasonIndex extends Component
{
    use WithPagination;

    protected $key = '5267a519dbe54ffbef5e4a2ede3f35b0';
    
    public $search = '';
    public $sort = 'asc';
    public $perPage = '5';

    public $tmdbId;
    public $name;
    public $seasonNumber;
    public $posterPath;
    public $seasonId;
    
    public $showSeasonModal = false;
    
    protected $rules = [
        'name'        => 'required',
        'seasonNumber' => 'required',
        'posterPath' => 'required',
    ];

    public function closeSeasonModal()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function showEditModal($id)
    {
        $this->seasonId = $id;
        $this->loadSeason();
        $this->showSeasonModal = true;
    }

    public function loadSeason()
    {
        $season             = Season::findOrFail($this->seasonId);
        $this->name         = $season->name;
        $this->seasonNumber = $season->season_number;
        $this->posterPath   = $season->poster_path;
    }

    public function updateSeason()
    {
        $this->validate();
        $season = Season::findOrFail($this->seasonId);
        $season->update([
            'name'         => $this->name,
            'slug'         => Str::slug($this->name,),
            'season_number' => $this->seasonNumber,
            'poster_path'  => $this->posterPath,
        ]);
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Season " ' . $season->name .' " Updated successfully!']);
        $this->reset();
    }

    public function deleteSeason($id)
    {
        $s = Season::findOrFail($id);
        $sName = $s->name;
        $s->delete();
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Season " ' . $sName .' " has been deleted successfully!']);
        $this->reset();
    }

    public function resetFilters()
    {
        $this->reset(['search','sort','perPage']);
    }

    public function render()
    {
        return view('livewire.season-index',[
            'seasons' => Season::paginate(5),
        ]);
    }
}
