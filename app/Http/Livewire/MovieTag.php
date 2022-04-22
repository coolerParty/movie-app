<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Tag;

class MovieTag extends Component
{
    public $queryTag = '';
    public $movie;
    public $tags = [];

    Public function mount($movie)
    {
        $this->movie = $movie;
    }

    public function updatedQueryTag()
    {
        $this->tags = Tag::search('tag_name',$this->queryTag)->get();
    }

    public function addTag($tagId)
    {
        $tag = Tag::findOrFail($tagId);
        $this->movie->tags()->attach($tag);
        $this->reset('queryTag');
        $this->emit('tagAdded');
    }

    public function detachTag($tagId)
    {
        $this->movie->tags()->detach($tagId);
        $this->emit('tagDetached');
    }

    public function render()
    {
        return view('livewire.movie-tag');
    }
}
