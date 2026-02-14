<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Services\ImageService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('User Management')]
class UserManagement extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $planFilter = 'all';

    public function mount(): void
    {
        abort_unless(auth()->user()->is_admin, 403, 'Unauthorized. Admin access required.');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPlanFilter(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::query()
            ->withCount(['qrSlots', 'listings'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->planFilter !== 'all', function ($query) {
                $query->where('plan_tier', $this->planFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(25);
    }

    public function deleteUser(int $id): void
    {
        abort_unless(auth()->user()->is_admin, 403);

        $user = User::findOrFail($id);

        // Prevent deleting yourself
        abort_if($user->id === auth()->id(), 422, 'You cannot delete your own account from this panel.');

        // Prevent deleting other admins
        abort_if($user->is_admin, 422, 'You cannot delete another admin account.');

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

            // Delete profile photo and logo
            if ($user->photo_path) {
                $imageService->deleteFile($user->photo_path);
            }
            if ($user->custom_logo_path) {
                $imageService->deleteFile($user->custom_logo_path);
            }

            // Delete all user data
            $user->listings()->forceDelete();
            $user->qrSlots()->delete();
            $user->delete();
        });

        session()->flash('message', "User \"{$user->name}\" and all associated data deleted successfully.");
    }

    public function changePlan(int $userId, string $plan): void
    {
        abort_unless(auth()->user()->is_admin, 403);

        $validPlans = ['free', 'pro', 'unlimited', 'company'];
        abort_unless(in_array($plan, $validPlans), 422, 'Invalid plan tier.');

        $user = User::findOrFail($userId);
        $user->update(['plan_tier' => $plan]);

        session()->flash('message', "Plan for \"{$user->name}\" updated to " . ucfirst($plan) . '.');
    }

    public function render()
    {
        return view('livewire.admin.user-management', [
            'users' => $this->users,
        ]);
    }
}
