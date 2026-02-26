<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:check-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark users as inactive if they have not logged in for 30 days';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for inactive users...');

        // Find users who haven't been active for 30 days
        $inactiveUsers = User::where('last_active_at', '<', now()->subDays(30))
            ->where('is_active', true)
            ->get();

        if ($inactiveUsers->isEmpty()) {
            $this->info('No inactive users found.');
            return Command::SUCCESS;
        }

        $count = $inactiveUsers->count();
        
        // Mark them as inactive
        User::where('last_active_at', '<', now()->subDays(30))
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $this->info("Marked {$count} user(s) as inactive.");
        
        // Log the inactive users
        foreach ($inactiveUsers as $user) {
            $this->line("- {$user->name} ({$user->email}) - Last active: {$user->last_active_at}");
        }

        return Command::SUCCESS;
    }
}
