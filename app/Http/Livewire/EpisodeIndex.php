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

    public $episodeNumber;


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

    public function render()
    {
        return view('livewire.episode-index',[
            'episodes' => Episode::paginate(5)
        ]);
    }
}
