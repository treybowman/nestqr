<?php

namespace App\Livewire\Listings;

use App\Models\Listing;
use App\Models\QrSlot;
use App\Services\ImageService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Listings')]
class ListingManager extends Component
{
    use WithPagination, WithFileUploads;

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = 'all';

    #[Url]
    public string $sortBy = 'created_at';

    #[Url]
    public string $sortDirection = 'desc';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    #[Computed]
    public function listings(): LengthAwarePaginator
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $allowedSortColumns = ['address', 'city', 'price', 'status', 'created_at'];
        $sortColumn = in_array($this->sortBy, $allowedSortColumns) ? $this->sortBy : 'created_at';
        $sortDir = in_array($this->sortDirection, ['asc', 'desc']) ? $this->sortDirection : 'desc';

        return Listing::where('user_id', $user->id)
            ->with(['photos', 'qrSlot'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('address', 'like', "%{$this->search}%")
                        ->orWhere('city', 'like', "%{$this->search}%")
                        ->orWhere('state', 'like', "%{$this->search}%")
                        ->orWhere('zip', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($sortColumn, $sortDir)
            ->paginate(15);
    }

    public function deleteListing(int $id): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $listing = Listing::where('user_id', $user->id)->findOrFail($id);

        // Unassign from QR slot if assigned
        if ($listing->qr_slot_id) {
            $listing->unassignFromQrSlot();
        }

        // Clean up photos from storage
        $imageService = app(ImageService::class);
        foreach ($listing->photos as $photo) {
            $imageService->deleteFile($photo->file_path);
            if ($photo->thumbnail_path) {
                $imageService->deleteFile($photo->thumbnail_path);
            }
        }

        // Soft delete the listing (photos relationship will cascade or be handled)
        $listing->photos()->delete();
        $listing->delete();

        session()->flash('message', 'Listing deleted successfully.');
    }

    public function updateStatus(int $id, string $status): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $allowedStatuses = ['active', 'inactive', 'pending', 'sold'];
        abort_unless(in_array($status, $allowedStatuses), 422, 'Invalid status.');

        $listing = Listing::where('user_id', $user->id)->findOrFail($id);
        $listing->update(['status' => $status]);

        session()->flash('message', 'Listing status updated to ' . ucfirst($status) . '.');
    }

    public function render()
    {
        return view('livewire.listings.listing-manager', [
            'listings' => $this->listings,
        ]);
    }
}
