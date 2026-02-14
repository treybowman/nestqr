<?php

namespace App\Jobs;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
    ) {
        $this->onQueue('emails');
    }

    public function handle(): void
    {
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));

        Log::info("Welcome email sent to [{$this->user->email}].");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Failed to send welcome email to [{$this->user->email}]: {$exception->getMessage()}");
    }
}
