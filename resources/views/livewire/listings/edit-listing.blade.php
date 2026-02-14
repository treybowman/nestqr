<div class="max-w-4xl mx-auto">
    <!-- Back Link -->
    <a href="{{ route('listings.index') }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition mb-6">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Back to Listings
    </a>

    <form wire:submit="save">
        <!-- Property Details -->
        <div class="card p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Property Details</h3>

            <div class="space-y-5">
                <!-- Address -->
                <div>
                    <label for="address" class="label">Address</label>
                    <input wire:model="address" type="text" id="address" class="input-field" placeholder="123 Main Street">
                    @error('address') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- City / State / Zip -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="city" class="label">City</label>
                        <input wire:model="city" type="text" id="city" class="input-field" placeholder="Atlanta">
                        @error('city') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="state" class="label">State</label>
                        <input wire:model="state" type="text" id="state" class="input-field" placeholder="GA" maxlength="2">
                        @error('state') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="zip" class="label">ZIP Code</label>
                        <input wire:model="zip" type="text" id="zip" class="input-field" placeholder="30039">
                        @error('zip') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Price / Beds / Baths / Sqft -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <label for="price" class="label">Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">$</span>
                            <input wire:model="price" type="number" id="price" class="input-field pl-7" placeholder="450000" step="1">
                        </div>
                        @error('price') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="beds" class="label">Beds</label>
                        <input wire:model="beds" type="number" id="beds" class="input-field" placeholder="3" min="0">
                        @error('beds') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="baths" class="label">Baths</label>
                        <input wire:model="baths" type="number" id="baths" class="input-field" placeholder="2" min="0" step="0.5">
                        @error('baths') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="sqft" class="label">Sq Ft</label>
                        <input wire:model="sqft" type="number" id="sqft" class="input-field" placeholder="2000" min="0">
                        @error('sqft') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="label">Description</label>
                    <textarea wire:model="description" id="description" rows="4" class="input-field" placeholder="Describe the property features, neighborhood, and highlights..."></textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="label">Status</label>
                    <select wire:model="status" id="status" class="input-field">
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="sold">Sold</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Existing Photos -->
        <div class="card p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Current Photos</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Click the remove button to delete an existing photo.</p>

            @if($existingPhotos->count() > 0)
                <div class="grid grid-cols-3 sm:grid-cols-5 gap-3">
                    @foreach($existingPhotos as $photo)
                        <div class="relative group aspect-square rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700" wire:key="existing-photo-{{ $photo->id }}">
                            <img src="{{ $photo->thumbnail_url }}" alt="Listing photo" class="w-full h-full object-cover">
                            @if($loop->first)
                                <span class="absolute top-1 left-1 bg-primary-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">PRIMARY</span>
                            @endif
                            <button
                                type="button"
                                wire:click="removeExistingPhoto({{ $photo->id }})"
                                wire:confirm="Are you sure you want to remove this photo?"
                                class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition hover:bg-red-600"
                            >
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-4">No photos uploaded yet.</p>
            @endif
        </div>

        <!-- Add New Photos -->
        <div class="card p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Add New Photos</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Upload additional photos (up to 10 at a time). Maximum 20 photos total per listing.</p>

            @error('newPhotos') <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg text-sm text-red-700 dark:text-red-300">{{ $message }}</div> @enderror
            @error('newPhotos.*') <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg text-sm text-red-700 dark:text-red-300">{{ $message }}</div> @enderror

            <!-- Drop Zone -->
            <div
                x-data="{ dragging: false }"
                x-on:dragover.prevent="dragging = true"
                x-on:dragleave.prevent="dragging = false"
                x-on:drop.prevent="dragging = false"
                class="relative border-2 border-dashed rounded-xl p-8 text-center transition"
                :class="dragging ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/10' : 'border-gray-300 dark:border-gray-600'"
            >
                <svg class="w-10 h-10 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-semibold text-primary-600 dark:text-primary-400">Click to upload</span> or drag and drop
                </p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">PNG, JPG, JPEG up to 5MB each</p>
                <input
                    wire:model="newPhotos"
                    type="file"
                    multiple
                    accept="image/*"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                >
            </div>

            <!-- Loading indicator -->
            <div wire:loading wire:target="newPhotos" class="mt-4 flex items-center justify-center">
                <svg class="w-5 h-5 animate-spin text-primary-500 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                <span class="text-sm text-gray-500">Uploading photos...</span>
            </div>

            <!-- New Photo Previews -->
            @if(count($newPhotos) > 0)
                <div class="grid grid-cols-3 sm:grid-cols-5 gap-3 mt-4">
                    @foreach($newPhotos as $index => $photo)
                        <div class="relative group aspect-square rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                            <img src="{{ $photo->temporaryUrl() }}" alt="New preview" class="w-full h-full object-cover">
                            <span class="absolute top-1 left-1 bg-blue-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">NEW</span>
                            <button
                                type="button"
                                wire:click="removeNewPhoto({{ $index }})"
                                class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition hover:bg-red-600"
                            >
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('listings.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" wire:loading.attr="disabled" class="btn-primary">
                <svg wire:loading wire:target="save" class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                Update Listing
            </button>
        </div>
    </form>
</div>
