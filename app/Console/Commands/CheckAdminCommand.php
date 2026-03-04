<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check admin users in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admins = User::where('is_admin', true)->get(['email', 'first_name', 'last_name', 'is_admin']);
        
        $this->info("Admin users found: " . $admins->count());
        
        foreach ($admins as $admin) {
            $this->line("Email: {$admin->email}, Name: {$admin->first_name} {$admin->last_name}, Is Admin: " . ($admin->is_admin ? 'Yes' : 'No'));
        }
        
        return 0;
    }
}
