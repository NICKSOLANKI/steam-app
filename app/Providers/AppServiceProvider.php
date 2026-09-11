<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Game;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Make $games available in the banner layout globally
        View::composer('layouts.banner', function ($view) {
            // Fetch all active games from DB
            $games = Game::active()->latest()->get();
            $view->with('games', $games);
        });
    }
}
