<?php

namespace App\Livewire\Listings;

use App\Jobs\ProcessListingPhotosJob;
use App\Models\Listing;
use App\Services\ImageService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Create Listing')]
class CreateListing extends Component
{
    use WithFileUploads;

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
    public array $photos = [];

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
            'photos' => ['nullable', 'array', 'max:10'],
            'photos.*' => ['image', 'max:5120'], // 5MB per photo
        ];
    }

    protected array $messages = [
        'photos.max' => 'You can upload a maximum of 10 photos.',
        'photos.*.image' => 'Each file must be an image.',
        'photos.*.max' => 'Each photo must be less than 5MB.',
    ];

    public function updatedPhotos(): void
    {
        $this->validateOnly('photos');
        $this->validateOnly('photos.*');
    }

    public function removePhoto(int $index): void
    {
        array_splice($this->photos, $index, 1);
        $this->photos = array_values($this->photos);
    }

    public function save(): void
    {
        $validated = $this->validate();

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $listing = Listing::create([
            'user_id' => $user->id,
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

        // Process photos
        if (! empty($this->photos)) {
            $tempPaths = [];

            foreach ($this->photos as $photo) {
                // Store the uploaded file to a temporary location for the job
                $tempPath = $photo->store('temp/photos', 'local');
                $tempPaths[] = storage_path('app/' . $tempPath);
            }

            ProcessListingPhotosJob::dispatch($listing->id, $tempPaths);
        }

        session()->flash('message', 'Listing created successfully.');

        $this->redirect(route('listings.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.listings.create-listing');
    }
}
