<section class="container mx-auto p-6 font-mono">
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			{{ __('Movies') }}
		</h2>
	</x-slot>
	{{-- generate genre start --}}
	<div class="w-full flex mb-4 p-2 justify-end">
		<form class="flex space-x-4 shadow bg-white rounded-md m-2 p-2">
			<div class="p-1 flex items-center">
				<label for="tmdbId" class="block text-sm font-medium text-gray-700 md:mr-4">Tmdb Id</label>
				<div class="relative rounded-md shadow-sm">
					<input wire:model="tmdbId" id="tmdbId" name="tmdb_id_g" class="px-3 py-2 border border-gray-300 rounded"
						placeholder="Tmdb Id" />
				</div>
			</div>
			<div class="p-1">
				<button type="button" wire:click="generateMovie"
					class="inline-flex items-center justify-center py-2 px-4 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-green-600 hover:bg-green-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-green-700 transition duration-150 ease-in-out disabled:opacity-50">
					<span>Generate</span>
				</button>
			</div>
		</form>
	</div>
	{{-- generate genre end --}}
	<div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
		{{-- search form start --}}
		<div class="w-full shadow p-5 bg-white">
			<div>
				<div class="flex justify-between">

					<div class="flex-1">
						<div class="relative">
							<div class="absolute flex items-center ml-2 h-full">
								<svg class="w-4 h-4 fill-current text-primary-gray-dark" viewBox="0 0 16 16" fill="none"
									xmlns="http://www.w3.org/2000/svg">
									<path
										d="M15.8898 15.0493L11.8588 11.0182C11.7869 10.9463 11.6932 10.9088 11.5932 10.9088H11.2713C12.3431 9.74952 12.9994 8.20272 12.9994 6.49968C12.9994 2.90923 10.0901 0 6.49968 0C2.90923 0 0 2.90923 0 6.49968C0 10.0901 2.90923 12.9994 6.49968 12.9994C8.20272 12.9994 9.74952 12.3431 10.9088 11.2744V11.5932C10.9088 11.6932 10.9495 11.7869 11.0182 11.8588L15.0493 15.8898C15.1961 16.0367 15.4336 16.0367 15.5805 15.8898L15.8898 15.5805C16.0367 15.4336 16.0367 15.1961 15.8898 15.0493ZM6.49968 11.9994C3.45921 11.9994 0.999951 9.54016 0.999951 6.49968C0.999951 3.45921 3.45921 0.999951 6.49968 0.999951C9.54016 0.999951 11.9994 3.45921 11.9994 6.49968C11.9994 9.54016 9.54016 11.9994 6.49968 11.9994Z">
									</path>
								</svg>
							</div>
							<input wire:model="search" type="text" placeholder="Search by title"
								class="px-8 py-3 w-full md:w-2/6 rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0 text-sm" />
						</div>
					</div>

					<div class="flex">
						<select wire:model="perPage"
							class="px-4 py-3 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0 text-sm">
							<option value="5">5 Per Page</option>
							<option value="10">10 Per page</option>
							<option value="15">15 Per Page</option>
						</select>
					</div>
				</div>
			</div>
		</div>

		{{-- search form end --}}
		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<tr
						class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
						<th class="px-4 py-3 cursor-pointer" wire:click="sortByColumn('title')">
							<div class="flex space-x-4 content-center">
								<span>Title</span>
								@if ($sortColumn == 'title' && $sortDirection == 'desc')
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-700" viewBox="0 0 20 20" fill="currentColor">
										<path fill-rule="evenodd"
											d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
											clip-rule="evenodd" />
									</svg>
								@elseif ($sortColumn == 'title' && $sortDirection == 'asc')
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-700" viewBox="0 0 20 20" fill="currentColor">
										<path fill-rule="evenodd"
											d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
											clip-rule="evenodd" />
									</svg>
								@endif
							</div>
						</th>
						<th class="px-4 py-3 cursor-pointer" wire:click="sortByColumn('rating')">
							<div class="flex space-x-4 content-center">
								<span>Rating</span>
								@if ($sortColumn == 'rating' && $sortDirection == 'desc')
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-700" viewBox="0 0 20 20" fill="currentColor">
										<path fill-rule="evenodd"
											d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
											clip-rule="evenodd" />
									</svg>
								@elseif ($sortColumn == 'rating' && $sortDirection == 'asc')
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-700" viewBox="0 0 20 20" fill="currentColor">
										<path fill-rule="evenodd"
											d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
											clip-rule="evenodd" />
									</svg>
								@endif
							</div>
						</th>
						<th class="px-4 py-3 cursor-pointer" wire:click="sortByColumn('visits')">
							<div class="flex space-x-4 content-center">
								<span>Visits</span>
								@if ($sortColumn == 'visits' && $sortDirection == 'desc')
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-700" viewBox="0 0 20 20" fill="currentColor">
										<path fill-rule="evenodd"
											d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
											clip-rule="evenodd" />
									</svg>
								@elseif ($sortColumn == 'visits' && $sortDirection == 'asc')
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-700" viewBox="0 0 20 20" fill="currentColor">
										<path fill-rule="evenodd"
											d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
											clip-rule="evenodd" />
									</svg>
								@endif
							</div>
						</th>
						<th class="px-4 py-3">Runtime</th>
						<th class="px-4 py-3">Published</th>
						<th class="px-4 py-3">Poster</th>
						<th class="px-4 py-3">Manage</th>
					</tr>
				</thead>
				<tbody class="bg-white">
					@forelse ($movies as $table_movie)
						<tr class="text-gray-700">
							<td class="px-4 py-3 text-ms border">
								{{ $table_movie->title }}
							</td>
							<td class="px-4 py-3 text-ms border">
								{{ $table_movie->rating }}
							</td>
							<td class="px-4 py-3 text-ms border">
								{{ $table_movie->visits }}
							</td>
							<td class="px-4 py-3 text-ms font-semibold border">
								{{ date('H:i', mktime(0, $table_movie->runtime)) }}
							</td>
							<td class="px-4 py-3 text-ms font-semibold border">
								@if ($table_movie->is_public)
									<span class="px-2 inline-flex text-xs leading-5 font-semibolic rounded-full bg-indigo-100 text-indigo 800">
										Published
									</span>
								@else
									<span class="px-2 inline-flex text-xs leading-5 font-semibolic rounded-full bg-red-100 text-red 800">
										Unpublished
									</span>
								@endif
							</td>
							<td class="px-4 py-3 text-ms font-semibold border">

								<img class="w-12 h-12 rounded" src="https://image.tmdb.org/t/p/w220_and_h330_face/{{ $table_movie->poster_path }}"
									alt="">
							</td>
							<td class="px-4 py-3 text-sm border">
								<x-m-button wire:click="showTrailerModal({{ $table_movie->id }})" class="bg-indigo-500 hover:bg-indigo-700 text-white text-xs">
									Trailer</x-m-button>
								<x-m-button wire:click="showEditModal({{ $table_movie->id }})" class="bg-green-600 hover:bg-green-500 text-white text-xs">
									Edit</x-m-button>
								<x-m-button wire:click="deleteMovie({{ $table_movie->id }})" class="bg-red-500 hover:bg-red-700 text-white text-xs">Delete
								</x-m-button>
							</td>
						</tr>
					@empty
						<tr class="text-gray-700">
							<td class="px-4 py-3 border">
								Empty
							</td>
							<td class="px-4 py-3 border"></td>
							<td class="px-4 py-3 border"></td>
							<td class="px-4 py-3 border"></td>
							<td class="px-4 py-3 border"></td>
						</tr>
					@endforelse
				</tbody>
			</table>
			<div class="m-2 p-2">
				{{ $movies->links() }}
			</div>
		</div>
	</div>
	{{-- Show-Movie-Modal Start --}}
	<x-jet-dialog-modal wire:model="showMovieModal">
		<x-slot name="title">Update Movie</x-slot>
		<x-slot name="content">
			<div class="mt-10 sm:mt-0">
				<div class="mt-5 md:mt-0 md:col-span-2">
					<form>
						<div class="shadow overflow-hidden sm:rounded-md">
							<div class="px-4 py-5 bg-white sm:p-6">
								<div class="flex flex-col">
									<label for="title" class="block text-sm font-medium text-gray-700">Title</label>
									<input wire:model="title" id="title" type="text" autocomplete="given-title"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('title')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col">
									<label for="runtime" class="block text-sm font-medium text-gray-700">Runtime</label>
									<input wire:model="runtime" id="runtime" type="text" autocomplete="given-runtime"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('runtime')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col">
									<label for="lang" class="block text-sm font-medium text-gray-700">Language</label>
									<input wire:model="lang" id="lang" type="text" autocomplete="given-lang"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('lang')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col">
									<label for="videoFormat" class="block text-sm font-medium text-gray-700">Format</label>
									<input wire:model="videoFormat" id="videoFormat" type="text" autocomplete="given-videoFormat"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('videoFormat')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col">
									<label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
									<input wire:model="rating" id="rating" type="text" autocomplete="given-rating"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('rating')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col">
									<label for="posterPath" class="block text-sm font-medium text-gray-700">Poster</label>
									<input wire:model="posterPath" id="posterPath" type="text" autocomplete="given-posterPath"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('posterPath')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col">
									<label for="backdropPath" class="block text-sm font-medium text-gray-700">Backdrop</label>
									<input wire:model="backdropPath" id="backdropPath" type="text" autocomplete="given-backdropPath"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('backdropPath')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col">
									<label for="overview" class="block text-sm font-medium text-gray-700">Overview</label>
									<input wire:model="overview" id="overview" type="text" autocomplete="given-overview"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('overview')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col">
									<div class="flex items-center px-2 py-6">
										<input wire:model="isPublic" id="isPublic" name="isPublic" type="checkbox"
											class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
										<label for="isPublic" class="ml-2 block text-sm text-gray-900"> Published </label>
									</div>
								</div>
							</div>

						</div>
					</form>
				</div>
			</div>

		</x-slot>
		<x-slot name="footer">
			<x-m-button wire:click="closeMovieModal" class="bg-gray-600 hover:bg-gray-800 text-white">Cancel</x-m-button>
			<x-m-button wire:click="updateMovie">Update</x-m-button>
		</x-slot>
	</x-jet-dialog-modal>
	{{-- Show-Movie-Modal End --}}
	{{-- Show-Trailer-Modal Start --}}
	<x-jet-dialog-modal wire:model="showTrailer">
		<x-slot name="title">Trailer Movie</x-slot>
		<x-slot name="content">
			@if($movie)
				@foreach ($movie->trailers as $trailer)
					{{ $trailer->name }},
					
				@endforeach
			@endif
			<div class="mt-10 sm:mt-0">
				<div class="mt-5 md:mt-0 md:col-span-2">
					<form>
						<div class="shadow overflow-hidden sm:rounded-md">
							<div class="px-4 py-5 bg-white sm:p-6">
								<div class="flex flex-col mb-4">
									<label for="trailerName" class="block text-sm font-medium text-gray-700">Name</label>
									<input wire:model="trailerName" id="trailerName" type="text" autocomplete="given-name"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('trailerName')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col mb-4">
									<label for="embedHtml" class="block text-sm font-medium text-gray-700">Embed Html</label>
									<textarea wire:model="embedHtml" id="embedHtml" type="text" autocomplete="given-embedHtml"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
									@error('embedHtml')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
							</div>

						</div>
					</form>
				</div>
			</div>
		</x-slot>
		<x-slot name="footer">
			<x-m-button wire:click="closeMovieModal" class="bg-gray-600 hover:bg-gray-800 text-white">Cancel</x-m-button>
			<x-m-button wire:click="addTrailer">Add Trailer</x-m-button>
		</x-slot>
	</x-jet-dialog-modal>
	{{-- Show-Trailer-Modal End --}}
</section>
