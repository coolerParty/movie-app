<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;

use App\Http\Livewire\MovieIndex;
use App\Http\Livewire\SerieIndex;
use App\Http\Livewire\SeasonIndex;
use App\Http\Livewire\EpisodeIndex;
use App\Http\Livewire\GenreIndex;
use App\Http\Livewire\CastIndex;
use App\Http\Livewire\TagIndex;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified','role:admin'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('/',[AdminController::class, 'index'])->name('index');
    Route::get('movies', MovieIndex::class)->name('movies.index');
    Route::get('series', SerieIndex::class)->name('series.index');
    Route::get('series/{serie}/seasons', SeasonIndex::class)->name('seasons.index');
    Route::get('series/{serie}/seasons/{season}/episode', EpisodeIndex::class)->name('episodes.index');
    Route::get('genres', GenreIndex::class)->name('genres.index');
    Route::get('casts', CastIndex::class)->name('casts.index');
    Route::get('tags', TagIndex::class)->name('tags.index');
});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
