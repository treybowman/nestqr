<?php

namespace App\Livewire\Listings;

use App\Jobs\ProcessListingPhotosJob;
use App\Models\Listing;
use App\Models\ListingPhoto;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Edit Listing')]
class EditListing extends Component
{
    use WithFileUploads;

    public Listing $listing;

    public string $address = '';
    public string $city = '';
    public string $state = '';
    public string $zip = '';
    public string $price = '';
    public string $beds = '';
    public string $baths = '';
    public string $sqft = '';
    public string $description = '';
    public string $status = 'active';
    public array $newPhotos = [];
    public Collection $existingPhotos;

    protected function rules(): array
    {
        return [
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:2'],
            'zip' => ['required', 'string', 'max:10'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'beds' => ['required', 'integer', 'min:0', 'max:99'],
            'baths' => ['required', 'numeric', 'min:0', 'max:99'],
            'sqft' => ['required', 'integer', 'min:0', 'max:999999'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', 'in:active,inactive,pending,sold'],
            'newPhotos' => ['nullable', 'array', 'max:10'],
            'newPhotos.*' => ['image', 'max:5120'], // 5MB per photo
        ];
    }

    protected array $messages = [
        'newPhotos.max' => 'You can upload a maximum of 10 new photos at a time.',
        'newPhotos.*.image' => 'Each file must be an image.',
        'newPhotos.*.max' => 'Each photo must be less than 5MB.',
    ];

    public function mount(Listing $listing): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        abort_unless($listing->user_id === $user->id, 403);

        $this->listing = $listing;
        $this->address = $listing->address;
        $this->city = $listing->city;
        $this->state = $listing->state;
        $this->zip = $listing->zip;
        $this->price = (string) $listing->price;
        $this->beds = (string) $listing->beds;
        $this->baths = (string) $listing->baths;
        $this->sqft = (string) $listing->sqft;
        $this->description = $listing->description ?? '';
        $this->status = $listing->status;
        $this->existingPhotos = $listing->photos;
    }

    public function updatedNewPhotos(): void
    {
        $this->validateOnly('newPhotos');
        $this->validateOnly('newPhotos.*');
    }

    public function removeExistingPhoto(int $photoId): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $photo = ListingPhoto::where('listing_id', $this->listing->id)->findOrFail($photoId);

        // Ensure the photo belongs to a listing owned by the user
        abort_unless($this->listing->user_id === $user->id, 403);

        $imageService = app(ImageService::class);
        $imageService->deleteFile($photo->file_path);
        if ($photo->thumbnail_path) {
            $imageService->deleteFile($photo->thumbnail_path);
        }

        $photo->delete();

        // Refresh existing photos
        $this->existingPhotos = $this->listing->photos()->get();
    }

    public function removeNewPhoto(int $index): void
    {
        array_splice($this->newPhotos, $index, 1);
        $this->newPhotos = array_values($this->newPhotos);
    }

    public function save(): void
    {
        $validated = $this->validate();

        /** @var \App\Models\User $user */
        $user = auth()->user();

        abort_unless($this->listing->user_id === $user->id, 403);

        $this->listing->update([
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zip' => $validated['zip'],
            'price' => $validated['price'],
            'beds' => $validated['beds'],
            'baths' => $validated['baths'],
            'sqft' => $validated['sqft'],
            'description' => $validated['description'] ?? '',
            'status' => $validated['status'],
        ]);

        // Process new photos
        if (! empty($this->newPhotos)) {
            $totalPhotos = $this->existingPhotos->count() + count($this->newPhotos);
            abort_unless($totalPhotos <= 20, 422, 'A listing can have a maximum of 20 photos.');

            $tempPaths = [];

            foreach ($this->newPhotos as $photo) {
                $tempPath = $photo->store('temp/photos', 'local');
                $tempPaths[] = storage_path('app/' . $tempPath);
            }

            ProcessListingPhotosJob::dispatch($this->listing->id, $tempPaths);
        }

        session()->flash('message', 'Listing updated successfully.');

        $this->redirect(route('listings.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.listings.edit-listing');
    }
}
