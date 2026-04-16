<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\User;
use App\Services\ImageService;
use App\Mail\AdminUserMail;
use App\Jobs\GenerateQRCodeJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

        $subscription = $user->subscription();

        return view('admin.users.show', [
            'user'         => $user,
            'subscription' => $subscription,
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

        $original = $user->only(['name', 'email', 'plan_tier', 'is_admin']);
        $user->update($validated);

        $changes = array_filter(array_map(function ($key) use ($original, $validated) {
            if (isset($validated[$key]) && $original[$key] != $validated[$key]) {
                return "{$key}: {$original[$key]} → {$validated[$key]}";
            }
            return null;
        }, array_keys($original)));

        AdminAuditLog::record(
            'update_user',
            "Updated user {$user->name} ({$user->email})" . ($changes ? ': ' . implode(', ', $changes) : ''),
            'User',
            $user->id,
            ['changes' => array_values($changes)]
        );

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

        AdminAuditLog::record(
            'delete_user',
            "Deleted user {$user->name} ({$user->email})",
            'User',
            $user->id
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Regenerate all QR code images for a user.
     */
    public function regenerateQrCodes(User $user): RedirectResponse
    {
        $user->load('qrSlots.user');

        try {
            foreach ($user->qrSlots as $slot) {
                GenerateQRCodeJob::dispatchSync($slot);
            }
        } catch (\Throwable $e) {
            return back()->with('error', "QR regeneration failed: " . $e->getMessage());
        }

        return back()->with('success', "Regenerated {$user->qrSlots->count()} QR code(s) for {$user->name}.");
    }

    /**
     * Send a one-off email to a user from the admin panel.
     */
    public function sendEmail(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body'    => ['required', 'string', 'max:5000'],
        ]);

        Mail::to($user->email)->send(new AdminUserMail(
            user: $user,
            subject: $validated['subject'],
            body: $validated['body'],
            fromName: Auth::user()->name,
        ));

        AdminAuditLog::record(
            'send_email',
            "Sent email to {$user->name} ({$user->email}): {$validated['subject']}",
            'User',
            $user->id
        );

        return back()->with('success', "Email sent to {$user->name}.");
    }

    /**
     * Export filtered users as CSV.
     */
    public function export(Request $request): Response
    {
        $users = User::query()
            ->withCount(['qrSlots', 'listings'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->plan, function ($query, $plan) {
                $query->where('plan_tier', $plan);
            })
            ->latest()
            ->get();

        $csv = "Name,Email,Phone,Plan,QR Codes,Listings,Admin,Joined\n";
        foreach ($users as $user) {
            $csv .= implode(',', [
                '"' . str_replace('"', '""', $user->name) . '"',
                '"' . str_replace('"', '""', $user->email) . '"',
                '"' . str_replace('"', '""', $user->phone ?? '') . '"',
                $user->plan_tier,
                $user->qr_slots_count,
                $user->listings_count,
                $user->is_admin ? 'Yes' : 'No',
                $user->created_at->format('Y-m-d'),
            ]) . "\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }

    /**
     * Impersonate the specified user.
     *
     * Issues a short-lived token so the caller can open the user's
     * session in a new tab without losing their own admin session.
     */
    public function impersonate(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot impersonate yourself.');
        }

        if ($user->is_admin) {
            return back()->with('error', 'You cannot impersonate another admin.');
        }

        session()->put('impersonating_from', Auth::id());

        AdminAuditLog::record(
            'impersonate',
            "Impersonated user {$user->name} ({$user->email})",
            'User',
            $user->id
        );

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
