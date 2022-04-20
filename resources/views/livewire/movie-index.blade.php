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
						<th class="px-4 py-3 cursor-pointer" wire:click="sortByColumn('title')">Title</th>
						<th class="px-4 py-3 cursor-pointer" wire:click="sortByColumn('rating')">Rating</th>
						<th class="px-4 py-3 cursor-pointer" wire:click="sortByColumn('visits')">Visits</th>
						<th class="px-4 py-3">Runtime</th>
						<th class="px-4 py-3">Published</th>
						<th class="px-4 py-3">Poster</th>
						<th class="px-4 py-3">Manage</th>
					</tr>
				</thead>
				<tbody class="bg-white">
					@forelse ($movies as $movie)
						<tr class="text-gray-700">
							<td class="px-4 py-3 border">
								{{ $movie->title }}
							</td>
							<td class="px-4 py-3 border">
								{{ $movie->rating }}
							</td>
							<td class="px-4 py-3 border">
								{{ $movie->visits }}
							</td>
							<td class="px-4 py-3 text-ms font-semibold border">
								{{ date('H:i' , mktime(0, $movie->runtime)) }}
							</td>
							<td class="px-4 py-3 text-ms font-semibold border">
								@if ($movie->is_public)
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

								<img class="w-12 h-12 rounded" src="https://image.tmdb.org/t/p/w220_and_h330_face/{{ $movie->poster_path }}" alt="">
							</td>
							<td class="px-4 py-3 text-sm border">
								<x-m-button wire:click="showEditModal({{ $movie->id }})" class="bg-green-500 hover:bg-green-700 text-white">
									Edit</x-m-button>
								<x-m-button wire:click="deleteMovie({{ $movie->id }})" class="bg-red-500 hover:bg-red-700 text-white">Delete
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
	<x-jet-dialog-modal wire:model="showMovieModal">
		<x-slot name="title">Update Movie</x-slot>
		<x-slot name="content">

			<div class="mt-10 sm:mt-0">
				<div class="mt-5 md:mt-0 md:col-span-2">
					<form>
						<div class="shadow overflow-hidden sm:rounded-md">
							<div class="px-4 py-5 bg-white sm:p-6">
								<div class="flex flex-col mb-4">
									<label for="title" class="block text-sm font-medium text-gray-700">Title</label>
									<input wire:model="title" id="title" type="text" autocomplete="given-title"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('title')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col mb-4">
									<label for="runtime" class="block text-sm font-medium text-gray-700">Runtime</label>
									<input wire:model="runtime" id="runtime" type="text" autocomplete="given-runtime"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('runtime')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col mb-4">
									<label for="lang" class="block text-sm font-medium text-gray-700">Language</label>
									<input wire:model="lang" id="lang" type="text" autocomplete="given-lang"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('lang')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col mb-4">
									<label for="videoFormat" class="block text-sm font-medium text-gray-700">Format</label>
									<input wire:model="videoFormat" id="videoFormat" type="text" autocomplete="given-videoFormat"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('videoFormat')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col mb-4">
									<label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
									<input wire:model="rating" id="rating" type="text" autocomplete="given-rating"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('rating')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col mb-4">
									<label for="posterPath" class="block text-sm font-medium text-gray-700">Poster</label>
									<input wire:model="posterPath" id="posterPath" type="text" autocomplete="given-posterPath"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('posterPath')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col mb-4">
									<label for="backdropPath" class="block text-sm font-medium text-gray-700">Backdrop</label>
									<input wire:model="backdropPath" id="backdropPath" type="text" autocomplete="given-backdropPath"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('backdropPath')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col mb-4">
									<label for="overview" class="block text-sm font-medium text-gray-700">Overview</label>
									<input wire:model="overview" id="overview" type="text" autocomplete="given-overview"
										class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									@error('overview')
										<span class="text-red-500 text-sm">{{ $message }}</span>
									@enderror
								</div>
								<div class="flex flex-col mb-4">
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
</section>
