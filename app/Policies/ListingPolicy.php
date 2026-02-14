<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any listings.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the listing.
     */
    public function view(User $user, Listing $listing): bool
    {
        return $user->id === $listing->user_id;
    }

    /**
     * Determine whether the user can create listings.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the listing.
     */
    public function update(User $user, Listing $listing): bool
    {
        return $user->id === $listing->user_id;
    }

    /**
     * Determine whether the user can delete the listing.
     */
    public function delete(User $user, Listing $listing): bool
    {
        return $user->id === $listing->user_id;
    }
}
