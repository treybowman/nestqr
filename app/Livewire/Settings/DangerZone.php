<?php

namespace App\Livewire\Settings;

use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Danger Zone')]
class DangerZone extends Component
{
    public bool $confirmDelete = false;
    public string $deleteConfirmation = '';

    public function deleteAccount(): void
    {
        if ($this->deleteConfirmation !== 'DELETE') {
            $this->addError('deleteConfirmation', 'Please type DELETE to confirm account deletion.');
            return;
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $imageService = app(ImageService::class);

        DB::transaction(function () use ($user, $imageService) {
            // Delete all listing photos from storage
            foreach ($user->listings as $listing) {
                foreach ($listing->photos as $photo) {
                    $imageService->deleteFile($photo->file_path);
                    if ($photo->thumbnail_path) {
                        $imageService->deleteFile($photo->thumbnail_path);
                    }
                }
                $listing->photos()->delete();
            }

            // Delete all QR code files from storage
            foreach ($user->qrSlots as $slot) {
                if ($slot->short_code) {
                    Storage::disk('public')->deleteDirectory("qr-codes/{$slot->short_code}");
                }
                $slot->scanAnalytics()->delete();
            }

            // Delete profile photo
            if ($user->photo_path) {
                $imageService->deleteFile($user->photo_path);
            }

            // Delete custom logo
            if ($user->custom_logo_path) {
                $imageService->deleteFile($user->custom_logo_path);
            }

            // Delete all user data
            $user->listings()->forceDelete();
            $user->qrSlots()->delete();
            $user->delete();
        });

        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.settings.danger-zone');
    }
}
