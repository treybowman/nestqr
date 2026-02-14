<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $user->delete();

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

        $admin = User::findOrFail($adminId);

        Auth::login($admin);

        return redirect()->route('admin.dashboard')
            ->with('success', 'You have stopped impersonating.');
    }
}
