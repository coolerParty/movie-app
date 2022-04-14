<section class="container mx-auto p-6 font-mono">
	<div class="w-full flex mb-4 p-2 justify-end">
		<x-jet-button wire:click="showCreateModal">Create Tag</x-jet-button>
	</div>
	<div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<tr
						class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
						<th class="px-4 py-3">Title</th>
						<th class="px-4 py-3">Date</th>
						<th class="px-4 py-3">Rating</th>
						<th class="px-4 py-3">Public</th>
						<th class="px-4 py-3">Manage</th>
					</tr>
				</thead>
				<tbody class="bg-white">
					<tr class="text-gray-700">
						<td class="px-4 py-3 border">
							Title Here
						</td>
						<td class="px-4 py-3 text-ms font-semibold border">Date Here</td>
						<td class="px-4 py-3 text-xs border">
							Rating Here
						</td>
						<td class="px-4 py-3 text-sm border">Public</td>
						<td class="px-4 py-3 text-sm border">
							Edit/Delete
						</td>
					</tr>

				</tbody>
			</table>
		</div>
	</div>
	<x-jet-dialog-modal wire:model="showTagModal">
		<x-slot name="title">Create Tag</x-slot>
		<x-slot name="content">

			<div class="mt-10 sm:mt-0">
				<div class="mt-5 md:mt-0 md:col-span-2">
					<form>
						<div class="shadow overflow-hidden sm:rounded-md">
							<div class="px-4 py-5 bg-white sm:p-6">
								<div class="grid grid-cols-6 gap-6">
									<div class="col-span-6 sm:col-span-3">
										<label for="first-name" class="block text-sm font-medium text-gray-700">Tag name</label>
										<input wire:model="tagName" type="text" autocomplete="given-name"
											class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
									</div>
								</div>
							</div>

						</div>
					</form>
				</div>
			</div>

		</x-slot>
		<x-slot name="footer">
			<x-jet-button wire:click="closeTagModal">Cancel</x-jet-button>
			<button wire:click="createTag"
				class="ml-2 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
		</x-slot>
	</x-jet-dialog-modal>
</section>
