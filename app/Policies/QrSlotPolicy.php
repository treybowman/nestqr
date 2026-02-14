<?php

namespace App\Policies;

use App\Models\QrSlot;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QrSlotPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any QR slots.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the QR slot.
     */
    public function view(User $user, QrSlot $qrSlot): bool
    {
        return $user->id === $qrSlot->user_id;
    }

    /**
     * Determine whether the user can create QR slots.
     */
    public function create(User $user): bool
    {
        return $user->canCreateQrSlot();
    }

    /**
     * Determine whether the user can update the QR slot.
     */
    public function update(User $user, QrSlot $qrSlot): bool
    {
        return $user->id === $qrSlot->user_id;
    }

    /**
     * Determine whether the user can delete the QR slot.
     */
    public function delete(User $user, QrSlot $qrSlot): bool
    {
        return $user->id === $qrSlot->user_id;
    }
}
