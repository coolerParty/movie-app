<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Episode;
use App\Models\Season;
use App\Models\Serie;
use Livewire\WithPagination;
use Illuminate\Support\Facades\HTTP;
use Illuminate\Support\Str;

class EpisodeIndex extends Component
{
    use WithPagination;

    public Serie $serie;
    public Season $season;

    protected $key = '5267a519dbe54ffbef5e4a2ede3f35b0';
    
    public $search = '';
    public $sort = 'asc';
    public $perPage = '5';

    public $name;
    public $episodeNumber;
    public $episodeId;
    public $overview;
    public $showEpisodeModal = false;

    protected $rules = [
        'name'          => 'required',
        'episodeNumber' => 'required',
        'overview'      => 'required',
    ];

    public function generateEpisode()
    {
        
        $newEpisode = HTTP::get('https://api.themoviedb.org/3/tv/'. $this->serie->tmdb_id .'/season/'. $this->season->season_number .'/episode/'. $this->episodeNumber .'?api_key=5267a519dbe54ffbef5e4a2ede3f35b0&language=en-US');

        if($newEpisode->ok())
        {

            $episode = Episode::where('tmdb_id',$newEpisode['id'])->first();
            if(!$episode)
            {
                Episode::create([
                    'tmdb_id'        => $newEpisode['id'],
                    'season_id'      => $this->season->id,
                    'name'           => $newEpisode['name'],
                    'slug'           => Str::slug($newEpisode['name']),
                    'episode_number' => $newEpisode['episode_number'],
                    'overview'       => $newEpisode['overview'],
                    'is_public'      => false,
                    'visits'         => 1,
                ]);
                
                $this->reset('episodeNumber');
                $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'New episode " ' . $newEpisode['name'] .' " Added successfully!']);
            }
            else
            {
                $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'episode " '. $episode->name.' " Exist!']);
            }

        }
        else
        {
            $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Api not Exist!']);
            $this->reset('episodeNumber');
        }
        
    }

    public function closeEpisodeModal()
    {
        $this->reset('episodeNumber','overview','name','episodeId','showEpisodeModal');
        $this->resetValidation();
    }

    public function showEditModal($id)
    {
        $this->episodeId = $id;
        $this->loadEpisode();
        $this->showEpisodeModal = true;
    }

    public function loadEpisode()
    {
        $episode             = Episode::findOrFail($this->episodeId);
        $this->name          = $episode->name;
        $this->episodeNumber = $episode->episode_number;
        $this->overview      = $episode->overview;
    }

    public function updateEpisode()
    {
        $this->validate();
        $episode = Episode::findOrFail($this->episodeId);
        $episode->update([
            'name'           => $this->name,
            'slug'           => Str::slug($this->name,),
            'episode_number' => $this->episodeNumber,
            'overview'       => $this->overview,
        ]);
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Episode " ' . $episode->name .' " Updated successfully!']);
        $this->reset('episodeNumber','overview','name','episodeId','showEpisodeModal');
    }

    public function deleteEpisode($id)
    {
        $e = Episode::findOrFail($id);
        $eName = $e->name;
        $e->delete();
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Episode " ' . $eName .' " has been deleted successfully!']);
        $this->reset('episodeNumber','overview','name','episodeId','showEpisodeModal');
    }

    public function resetFilters()
    {
        $this->reset(['search','sort','perPage']);
    }

    public function render()
    {
        return view('livewire.episode-index',[
            'episodes' => Episode::where('season_id', $this->season->id)->search('name','$this->search')->orderBy('name',$this->sort)->paginate($this->perPage)
        ]);
    }
}
