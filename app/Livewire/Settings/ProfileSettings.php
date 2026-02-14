<?php

namespace App\Livewire\Settings;

use App\Services\ImageService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Profile Settings')]
class ProfileSettings extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $bio = '';
    public $photo = null;
    public ?string $currentPhotoUrl = null;

    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->bio = $user->bio ?? '';
        $this->currentPhotoUrl = $user->photo_url;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'max:2048'], // 2MB max
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ];

        // Handle photo upload
        if ($this->photo) {
            $imageService = app(ImageService::class);

            // Delete old photo if exists
            if ($user->photo_path) {
                $imageService->deleteFile($user->photo_path);
            }

            $updateData['photo_path'] = $imageService->processProfilePhoto($this->photo, $user->id);
        }

        $user->update($updateData);

        $this->currentPhotoUrl = $user->fresh()->photo_url;
        $this->photo = null;

        session()->flash('message', 'Profile updated successfully.');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'currentPassword' => ['required', 'string'],
            'newPassword' => ['required', 'string', Password::defaults(), 'confirmed:newPasswordConfirmation'],
            'newPasswordConfirmation' => ['required', 'string'],
        ], [
            'newPassword.confirmed' => 'The new password confirmation does not match.',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (! Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'The current password is incorrect.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->currentPassword = '';
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';

        session()->flash('password-message', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.settings.profile-settings');
    }
}
