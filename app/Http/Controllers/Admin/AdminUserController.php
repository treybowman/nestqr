<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display a paginated list of all users.
     */
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->plan, function ($query, $plan) {
                $query->where('plan_tier', $plan);
            })
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    /**
     * Display detailed information about a specific user.
     */
    public function show(User $user): View
    {
        $user->load(['qrSlots.currentListing', 'listings.photos']);

        return view('admin.users.show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user's details.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'plan_tier' => ['required', 'string', Rule::in(['free', 'pro', 'unlimited', 'company'])],
            'is_admin' => ['boolean'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Delete the specified user and all associated data.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

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

            // Delete profile photo and custom logo
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

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Impersonate the specified user.
     *
     * Stores the current admin's ID in the session so we can
     * return to the admin account via stopImpersonating().
     */
    public function impersonate(User $user): RedirectResponse
    {
        // Prevent impersonating yourself
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot impersonate yourself.');
        }

        // Prevent impersonating other admins
        if ($user->is_admin) {
            return back()->with('error', 'You cannot impersonate another admin.');
        }

        session()->put('impersonating_from', Auth::id());

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('info', "You are now impersonating {$user->name}.");
    }

    /**
     * Stop impersonating and return to the admin account.
     */
    public function stopImpersonating(): RedirectResponse
    {
        $adminId = session()->pull('impersonating_from');

        if (! $adminId) {
            return redirect()->route('dashboard');
        }

        $admin = User::find($adminId);

        if (! $admin) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Your admin account no longer exists.');
        }

        Auth::login($admin);

        return redirect()->route('admin.dashboard')
            ->with('success', 'You have stopped impersonating.');
    }
}
