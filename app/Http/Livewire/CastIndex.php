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

    public $search = '';
    public $sort = 'asc';
    public $perPage = '5';

    protected $key = '5267a519dbe54ffbef5e4a2ede3f35b0';

    public $castTMDBId;
    public $castName;
    public $castPosterPath;
    public $castId;

    public $showCastModal = false;

    protected $rules = [
        'castName'       => 'required',
        'castPosterPath' => 'required',
    ];

    public function generateCast()
    {
        $newCast = HTTP::get('https://api.themoviedb.org/3/person/'. $this->castTMDBId .'?api_key=5267a519dbe54ffbef5e4a2ede3f35b0')
        ->json();
        $cast = Cast::where('tmdb_id',$newCast['id'])->first();
        if(!$cast)
        {
            Cast::create([
                'tmdb_id'     => $newCast['id'],
                'name'        => $newCast['name'],
                'slug'        => Str::slug($newCast['name']),
                'poster_path' => $newCast['profile_path'],
            ]);
            $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'New cast " ' . $newCast['name'] .' " Added successfully!']);
        }
        else
        {
            $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Cast " '. $cast->name.' " Exist!']);
        }
    }

    public function showEditModal($id)
    {
        $this->castId = $id;
        $this->loadCast();
        $this->showCastModal = true;

    }

    public function loadCast()
    {
        $cast                 = Cast::findOrFail($this->castId);
        $this->castName       = $cast->name;
        $this->castPosterPath = $cast->poster_path;
    }

    public function updateCast()
    {
        $this->validate();
        $cast = Cast::findOrFail($this->castId);
        $cast->update([
            'name'        => $this->castName,
            'poster_path' => $this->castPosterPath,
        ]);
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'New cast " ' . $cast->name .' " Updated successfully!']);
        $this->reset();
    }

    public function closeCastModal()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function deleteCast($id)
    {
        $c = Cast::findOrFail($id);
        $cName = $c->name;
        $c->delete();
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Cast " ' . $cName .' " has been deleted successfully!']);
        $this->reset();
    }

    public function resetFilters()
    {
        $this->reset(['search','sort','perPage']);
    }

    public function render()
    {
        return view('livewire.cast-index',[
            'casts' => Cast::search('name', $this->search)->orderby('name', $this->sort)->paginate($this->perPage)
        ]);
    }
}
