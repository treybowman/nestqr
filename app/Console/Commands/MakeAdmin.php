<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{
    protected $signature = 'user:make-admin {email : The email address of the user}';

    protected $description = 'Grant admin privileges to a user by email';

    public function handle(): int
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("No user found with email: {$email}");
            return self::FAILURE;
        }

        if ($user->is_admin) {
            $this->warn("{$user->name} ({$email}) is already an admin.");
            return self::SUCCESS;
        }

        $user->update(['is_admin' => true]);

        $this->info("{$user->name} ({$email}) is now an admin.");

        return self::SUCCESS;
    }
}
