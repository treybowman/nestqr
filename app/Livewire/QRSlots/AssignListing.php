<?php

namespace App\Livewire\QrSlots;

use App\Models\Listing;
use App\Models\QrSlot;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class AssignListing extends Component
{
    public QrSlot $slot;
    public ?int $selectedListingId = null;
    public Collection $availableListings;

    protected function rules(): array
    {
        return [
            'selectedListingId' => ['required', 'exists:listings,id'],
        ];
    }

    protected array $messages = [
        'selectedListingId.required' => 'Please select a listing to assign.',
        'selectedListingId.exists' => 'The selected listing is invalid.',
    ];

    public function mount(QrSlot $slot): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Ensure the user owns this slot
        abort_unless($slot->user_id === $user->id, 403);

        $this->slot = $slot;

        // Load unassigned active listings for this user
        $this->availableListings = Listing::where('user_id', $user->id)
            ->active()
            ->where(function ($query) {
                $query->whereNull('qr_slot_id')
                    ->orWhere('qr_slot_id', $this->slot->id);
            })
            ->orderBy('address')
            ->get();
    }

    public function assign(): void
    {
        $this->validate();

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $listing = Listing::where('user_id', $user->id)
            ->findOrFail($this->selectedListingId);

        // Unassign any previous listing from this slot
        if ($this->slot->isAssigned() && $this->slot->current_listing_id !== $listing->id) {
            $currentListing = Listing::find($this->slot->current_listing_id);
            $currentListing?->unassignFromQrSlot();
        }

        // Assign the new listing
        $listing->assignToQrSlot($this->slot);
        $this->slot->refresh();

        session()->flash('message', 'Listing assigned successfully.');

        // Redirect to the public listing page
        $this->redirect($this->slot->getPublicUrl(), navigate: true);
    }

    public function render()
    {
        return view('livewire.qr-slots.assign-listing');
    }
}
