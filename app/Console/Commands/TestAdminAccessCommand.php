<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestAdminAccessCommand extends Command
{
    protected $signature = 'test:admin-access {email}';
    protected $description = 'Test if a user has admin access';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User not found: {$email}");
            return 1;
        }
        
        $this->info("User: {$user->email}");
        $this->info("Is Admin: " . ($user->is_admin ? 'Yes' : 'No'));
        $this->info("isAdmin() method: " . ($user->isAdmin() ? 'Yes' : 'No'));
        $this->info("User Status: " . ($user->userStatus->name ?? 'None'));
        $this->info("Email Verified: " . ($user->email_verified_at ? 'Yes' : 'No'));
        
        return 0;
    }
}
