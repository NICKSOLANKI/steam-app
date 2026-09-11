<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\BannerSlider;
use Illuminate\Support\Collection;

class FrontController extends Controller
{
    // Homepage / Store page
    public function index()
    {
        $games = Game::active()->latest()->get();
        $featuredGames = Game::getFeaturedGames(3);

        return view('notlogin.index', [
            'featuredGames' => $featuredGames,
            'games' => $games,
        ]);
    }

    // Games list page for /store
    public function Games()
    {
        $games = Game::active()->latest()->get();
        return view('notlogin.index', [
            'games' => $games,
            'featuredGames' => Game::getFeaturedGames(3),
        ]);
    }

    // Genre filtered list
    public function genres($genre)
    {
        $filtered = Game::getGamesByGenre($genre, 1000);
        return view('notlogin.category', [
            'genre' => $genre,
            'games' => $filtered,
            'featuredGames' => Game::getFeaturedGames(3),
        ]);
    }

    // Simulated "Show Game"
    public function showgame(Request $request)
    {
        $log = [
            'time' => now()->toDateTimeString(),
            'game_id' => $request->input('game_id'),
        ];

        session()->put('last_clicked_game', $log);

        return response()->json(['status' => 'viewed']);
    }
    
    // API: Check for banner refresh
    public function checkBannerRefresh(Request $request)
    {
        try {
            // Get the last update time of banner sliders
            $lastBannerUpdate = BannerSlider::max('updated_at');
            $lastGameUpdate = Game::where('is_featured', true)->max('updated_at');
            
            // Compare with the last check time from client
            $clientLastCheck = $request->query('last_check');
            $hasSliderChanges = false;
            $hasNewGames = false;
            
            if ($clientLastCheck) {
                $clientTime = \Carbon\Carbon::parse($clientLastCheck);
                $hasSliderChanges = $lastBannerUpdate && $lastBannerUpdate->gt($clientTime);
                $hasNewGames = $lastGameUpdate && $lastGameUpdate->gt($clientTime);
            } else {
                // First time check
                $hasSliderChanges = true;
            }
            
            return response()->json([
                'hasSliderChanges' => $hasSliderChanges,
                'hasNewGames' => $hasNewGames,
                'lastUpdate' => now()->timestamp,
                'bannerCount' => BannerSlider::active()->count(),
                'featuredGamesCount' => Game::featured()->active()->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'hasSliderChanges' => false,
                'hasNewGames' => false,
                'error' => 'Failed to check for updates'
            ], 500);
        }
    }
    
    // API: Search games
    public function searchGames(Request $request)
    {
        $query = $request->get('q', '');
        $games = Game::active()
            ->where('title', 'LIKE', '%' . $query . '%')
            ->orWhere('genre', 'LIKE', '%' . $query . '%')
            ->orWhere('developer', 'LIKE', '%' . $query . '%')
            ->limit(10)
            ->get(['id', 'title', 'slug', 'genre', 'price', 'image_path']);
            
        return response()->json($games);
    }
}