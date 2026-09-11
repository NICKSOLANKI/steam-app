<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// API endpoint to check for new games and banner slider changes (for auto-refresh)
Route::get('/banner-refresh', function() {
    $lastCheck = session('last_banner_check', 0);
    $newGamesCount = \App\Models\Game::where('is_active', true)
        ->where('created_at', '>', date('Y-m-d H:i:s', $lastCheck))
        ->count();
    
    $sliderChangesCount = \App\Models\BannerSlider::where('updated_at', '>', date('Y-m-d H:i:s', $lastCheck))
        ->count();
    
    session(['last_banner_check' => time()]);
    
    return response()->json([
        'hasNewGames' => $newGamesCount > 0,
        'hasSliderChanges' => $sliderChangesCount > 0,
        'newGamesCount' => $newGamesCount,
        'sliderChangesCount' => $sliderChangesCount,
        'timestamp' => time()
    ]);
});
