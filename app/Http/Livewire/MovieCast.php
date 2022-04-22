<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cast;

class MovieCast extends Component
{
    public $queryCast = '';
    public $movie;
    public $casts = [];

    Public function mount($movie)
    {
        $this->movie = $movie;
    }

    public function updatedQueryCast()
    {
        $this->casts = Cast::search('name',$this->queryCast)->get();
    }

    public function addCast($castId)
    {
        $cast = Cast::findOrFail($castId);
        $this->movie->casts()->attach($cast);
        $this->reset('queryCast');
        $this->emit('castAdded');
    }

    public function detachCast($castId)
    {
        $this->movie->casts()->detach($castId);
        $this->emit('castDetached');
    }

    public function render()
    {
        return view('livewire.movie-cast');
    }
}
