<?php

namespace App\Livewire\QRSlots;

use App\Jobs\GenerateQRCodeJob;
use App\Models\Icon;
use App\Models\Listing;
use App\Models\QrSlot;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('QR Slots')]
class QrSlotManager extends Component
{
    use WithPagination;

    public bool $showCreateModal = false;
    public ?int $selectedIconId = null;
    public ?int $editingSlotId = null;

    public function mount(): void
    {
        //
    }

    #[Computed]
    public function availableIcons(): \Illuminate\Database\Eloquent\Collection
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return Icon::active()
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (Icon $icon) => $user->canAccessIcon($icon))
            ->values();
    }

    public function createSlot(): void
    {
        $this->validate([
            'selectedIconId' => ['required', 'exists:icons,id'],
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        abort_unless($user->canCreateQrSlot(), 403, 'You have reached the maximum number of QR slots for your plan.');

        $icon = Icon::findOrFail($this->selectedIconId);
        abort_unless($user->canAccessIcon($icon), 403, 'You do not have access to this icon on your current plan.');

        $slot = QrSlot::create([
            'user_id' => $user->id,
            'icon_id' => $icon->id,
            'icon_locked_at' => now(),
        ]);

        GenerateQRCodeJob::dispatch($slot);

        $this->showCreateModal = false;
        $this->selectedIconId = null;
        $this->resetPage();

        session()->flash('message', 'QR slot created successfully. Your QR code is being generated.');
    }

    public function deleteSlot(int $id): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $slot = QrSlot::where('user_id', $user->id)->findOrFail($id);

        // Unassign the listing first if one is assigned
        if ($slot->isAssigned()) {
            $this->unassignListing($slot->id);
        }

        // Clean up QR code files from storage
        if ($slot->short_code) {
            $directory = "qr-codes/{$slot->short_code}";
            Storage::disk('public')->deleteDirectory($directory);
        }

        // Delete associated scan analytics
        $slot->scanAnalytics()->delete();

        $slot->delete();

        session()->flash('message', 'QR slot deleted successfully.');
    }

    public function assignListing(int $slotId, int $listingId): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $slot = QrSlot::where('user_id', $user->id)->findOrFail($slotId);
        $listing = Listing::where('user_id', $user->id)->findOrFail($listingId);

        // Unassign any currently assigned listing from this slot
        if ($slot->isAssigned()) {
            $currentListing = Listing::find($slot->current_listing_id);
            $currentListing?->update(['qr_slot_id' => null]);
        }

        $listing->assignToQrSlot($slot);

        session()->flash('message', 'Listing assigned to QR slot successfully.');
    }

    public function unassignListing(int $slotId): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $slot = QrSlot::where('user_id', $user->id)->findOrFail($slotId);

        if ($slot->isAssigned()) {
            $listing = Listing::find($slot->current_listing_id);
            $listing?->unassignFromQrSlot();
        }

        session()->flash('message', 'Listing unassigned from QR slot.');
    }

    public function changeIcon(int $slotId, int $iconId): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $slot = QrSlot::where('user_id', $user->id)->findOrFail($slotId);

        if (! $slot->canChangeIcon()) {
            session()->flash('error', 'This icon is locked and cannot be changed yet. Icons are locked for ' . config('nestqr.icon_lock_hours', 24) . ' hours after assignment.');
            return;
        }

        $icon = Icon::findOrFail($iconId);
        abort_unless($user->canAccessIcon($icon), 403, 'You do not have access to this icon on your current plan.');

        $slot->update([
            'icon_id' => $icon->id,
            'icon_locked_at' => now(),
        ]);

        // Regenerate QR code with new icon
        GenerateQRCodeJob::dispatch($slot->fresh());

        session()->flash('message', 'Icon changed successfully. Your QR code is being regenerated.');
    }

    public function render()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $slots = QrSlot::where('user_id', $user->id)
            ->with(['icon', 'currentListing'])
            ->latest()
            ->paginate(12);

        $unassignedListings = Listing::where('user_id', $user->id)
            ->active()
            ->whereNull('qr_slot_id')
            ->get();

        return view('livewire.qr-slots.qr-slot-manager', [
            'slots' => $slots,
            'unassignedListings' => $unassignedListings,
        ]);
    }
}
