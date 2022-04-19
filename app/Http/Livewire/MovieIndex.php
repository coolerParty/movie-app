<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Movie;
use Livewire\WithPagination;

class MovieIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sort = 'asc';
    public $perPage = '5';

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
