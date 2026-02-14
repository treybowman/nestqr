<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Custom email verification notification for NestQR.
 *
 * Extends Laravel's built-in VerifyEmail notification.
 * Breeze handles the primary email verification flow; this class
 * serves as an extension point for future customization (e.g.,
 * custom views, additional channels, or branding).
 */
class VerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onQueue('emails');
    }
}
