<?php

namespace App\Livewire\Settings;

use App\Services\ImageService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Branding Settings')]
class BrandingSettings extends Component
{
    use WithFileUploads;

    public $logo = null;
    public string $brandColor = '';
    public ?string $currentLogo = null;

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        abort_unless($user->hasCustomBranding(), 403, 'Custom branding is available on Pro plans and above.');

        $this->brandColor = $user->custom_brand_color ?? '#000000';
        $this->currentLogo = $user->logo_url;
    }

    public function save(): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        abort_unless($user->hasCustomBranding(), 403, 'Custom branding is available on Pro plans and above.');

        $this->validate([
            'logo' => ['nullable', 'image', 'max:2048', 'mimes:png,jpg,jpeg,svg'],
            'brandColor' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ], [
            'brandColor.regex' => 'Please enter a valid hex color code (e.g., #FF5733).',
        ]);

        $updateData = [
            'custom_brand_color' => $this->brandColor,
        ];

        // Handle logo upload
        if ($this->logo) {
            $imageService = app(ImageService::class);

            // Delete old logo if exists
            if ($user->custom_logo_path) {
                $imageService->deleteFile($user->custom_logo_path);
            }

            $updateData['custom_logo_path'] = $imageService->processLogo($this->logo, $user->id);
        }

        $user->update($updateData);

        $this->currentLogo = $user->fresh()->logo_url;
        $this->logo = null;

        session()->flash('message', 'Branding settings updated successfully.');
    }

    public function removeLogo(): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        abort_unless($user->hasCustomBranding(), 403);

        if ($user->custom_logo_path) {
            $imageService = app(ImageService::class);
            $imageService->deleteFile($user->custom_logo_path);

            $user->update(['custom_logo_path' => null]);

            $this->currentLogo = null;

            session()->flash('message', 'Logo removed successfully.');
        }
    }

    public function render()
    {
        return view('livewire.settings.branding-settings');
    }
}
