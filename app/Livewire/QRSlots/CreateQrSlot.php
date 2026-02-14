<?php

namespace App\Livewire\QRSlots;

use App\Jobs\GenerateQRCodeJob;
use App\Models\Icon;
use App\Models\QrSlot;
use Illuminate\Support\Collection;
use Livewire\Component;

class CreateQrSlot extends Component
{
    public ?int $selectedIconId = null;
    public Collection $icons;

    protected function rules(): array
    {
        return [
            'selectedIconId' => ['required', 'exists:icons,id'],
        ];
    }

    protected array $messages = [
        'selectedIconId.required' => 'Please select an icon for your QR code.',
        'selectedIconId.exists' => 'The selected icon is invalid.',
    ];

    public function mount(): void
    {
        $this->icons = Icon::active()
            ->orderBy('tier')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('tier');
    }

    public function create(): void
    {
        $this->validate();

        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Check plan limits
        abort_unless(
            $user->canCreateQrSlot(),
            403,
            'You have reached the maximum number of QR slots for your plan. Please upgrade to create more.'
        );

        // Verify icon access
        $icon = Icon::findOrFail($this->selectedIconId);
        abort_unless(
            $user->canAccessIcon($icon),
            403,
            'You do not have access to this icon on your current plan.'
        );

        // Create the QR slot (short_code auto-generated via model boot)
        $slot = QrSlot::create([
            'user_id' => $user->id,
            'icon_id' => $icon->id,
            'icon_locked_at' => now(),
        ]);

        // Dispatch QR code generation job
        GenerateQRCodeJob::dispatch($slot);

        // Emit event to parent component
        $this->dispatch('qr-slot-created');

        // Close modal
        $this->dispatch('close-modal');

        session()->flash('message', 'QR slot created successfully. Your QR code is being generated.');
    }

    public function render()
    {
        return view('livewire.qr-slots.create-qr-slot');
    }
}
