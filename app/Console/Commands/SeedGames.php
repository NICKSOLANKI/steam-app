<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Database\Seeders\GamesSeeder;

class SeedGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You can run it with: php artisan db:seed-games
     *
     * @var string
     */
    protected $signature = 'db:seed-games {--fresh : Clear the games table before seeding}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the games table safely, avoiding duplicate slugs';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // If --fresh option is used, truncate the table first
        if ($this->option('fresh')) {
            DB::table('games')->truncate();
            $this->info('Games table truncated.');
        }

        // Call the GamesSeeder
        $this->call(GamesSeeder::class);

        $this->info('Games table seeded successfully.');
        return 0;
    }
}
