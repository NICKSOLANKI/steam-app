<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CommunityChatController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\DownloadController;

// -------------------------
// Guest Routes
// -------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// -------------------------
// Public Routes
// -------------------------
Route::get('/', function () {
    return view('notlogin.index');
})->name('home');

Route::get('/test-images', function () {
    $game = \App\Models\Game::first();
    $banner = \App\Models\BannerSlider::first();
    
    return response()->json([
        'game_image_url' => $game->image_url,
        'game_image_path' => $game->image_path,
        'banner_image_url' => \App\Models\BannerSlider::getBannerSliders()[0]['image'] ?? 'No banner',
        'banner_image_path' => $banner->image_path ?? 'No banner',
        'storage_link_exists' => public_path('storage'),
        'image_file_exists' => file_exists(public_path('storage/' . $game->image_path)),
    ]);
});

Route::get('/store', [FrontController::class, 'Games'])->name('notlogin.index');
Route::get('/guest', [FrontController::class, 'Games'])->name('notlogin.guest');
Route::get('/community', function () {
    if (Auth::check()) {
        return redirect()->route('discord.index');
    }
    return app(CommunityController::class)->index();
})->name('notlogin.community');
Route::get('/category/{genre}', [FrontController::class, 'genres'])->name('category');
Route::post('/game/view', [FrontController::class, 'showgame'])->name('game.view');

// Game detail routes using slug for public
Route::get('/game/{slug}', [GameController::class, 'show'])->name('game.details');

// -------------------------
// Authenticated User Routes
// -------------------------
Route::middleware('auth')->group(function () {

    // Library
    Route::get('/library', [LibraryController::class, 'index'])->name('library');
    Route::get('/library/modal', [LibraryController::class, 'modal']);

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::post('/update-username', [ProfileController::class, 'updateUsername'])->name('profile.updateUsername');
        Route::post('/update-avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar');
        Route::post('/update-background', [ProfileController::class, 'updateBackground'])->name('profile.updateBackground');
        Route::post('/update-mini-profile', [ProfileController::class, 'updateMiniProfile'])->name('profile.updateMiniProfile');
    });

    // Cart
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add', [CartController::class, 'add'])->name('cart.add');
        Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/buy/{id}', [CartController::class, 'buy'])->name('cart.buy');
        Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    });

    Route::get('/support', fn() => view('managegame.support'))->name('support');

    // Subscription
    Route::prefix('subscription')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('subscription.index');
        Route::get('/open', [SubscriptionController::class, 'open'])->name('subscription.open');
        Route::post('/purchase', [SubscriptionController::class, 'purchase'])->name('subscription.purchase');
    });

    // Community Chat
    Route::get('/community-chat', [CommunityChatController::class, 'index'])->name('community.chat');
    Route::get('/community-chat/fetch', [CommunityChatController::class, 'fetch'])->name('community.chat.fetch');
    Route::post('/community-chat/send', [CommunityChatController::class, 'send'])->name('community.chat.send');

    // Discord-like Community
    Route::prefix('discord')->name('discord.')->group(function () {
        Route::get('/', [DiscordController::class, 'index'])->name('index');
        Route::post('/servers', [DiscordController::class, 'createServer'])->name('create-server');
        Route::post('/servers/{serverId}/join', [DiscordController::class, 'joinServer'])->name('join-server');
        Route::post('/servers/{serverId}/leave', [DiscordController::class, 'leaveServer'])->name('leave-server');
        Route::get('/servers/{serverId}', [DiscordController::class, 'getServer'])->name('get-server');
        Route::post('/servers/{serverId}/channels', [DiscordController::class, 'createChannel'])->name('create-channel');
        Route::post('/channels/{channelId}/join-voice', [DiscordController::class, 'joinVoiceChannel'])->name('join-voice');
        Route::post('/channels/{channelId}/leave-voice', [DiscordController::class, 'leaveVoiceChannel'])->name('leave-voice');
        Route::post('/channels/{channelId}/messages', [DiscordController::class, 'sendMessage'])->name('send-message');
        Route::get('/channels/{channelId}/messages', [DiscordController::class, 'getMessages'])->name('get-messages');
        Route::put('/channels/{channelId}/voice-session', [DiscordController::class, 'updateVoiceSession'])->name('update-voice-session');
        Route::get('/channels/{channelId}/voice-users', [DiscordController::class, 'getVoiceChannelUsers'])->name('get-voice-users');
        Route::post('/channels/{channelId}/signal', [DiscordController::class, 'sendSignal'])->name('send-signal');
        Route::get('/channels/{channelId}/signals', [DiscordController::class, 'getSignals'])->name('get-signals');
        Route::get('/channels/{channelId}/signal/{userId}', [DiscordController::class, 'getSignalInfo'])->name('get-signal-info');
    });

    // Downloads - Steam-like download system
    Route::prefix('downloads')->name('downloads.')->group(function () {
        Route::get('/', [DownloadController::class, 'index'])->name('index');
        Route::post('/store', [DownloadController::class, 'store'])->name('store');
        Route::get('/api/count', [DownloadController::class, 'activeCount'])->name('count');
        Route::get('/api/recent', [DownloadController::class, 'recent'])->name('recent');
        Route::get('/{id}/file', [DownloadController::class, 'file'])->name('file');
        Route::get('/{id}', [DownloadController::class, 'show'])->name('show');
        Route::post('/{id}/progress', [DownloadController::class, 'updateProgress'])->name('progress');
        Route::post('/{id}/pause', [DownloadController::class, 'pause'])->name('pause');
        Route::post('/{id}/resume', [DownloadController::class, 'resume'])->name('resume');
        Route::post('/{id}/cancel', [DownloadController::class, 'cancel'])->name('cancel');
    });
});

// -------------------------
// Logout
// -------------------------
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// -------------------------
// Admin Authentication
// -------------------------
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// -------------------------
// Admin Protected Routes
// -------------------------
Route::prefix('admin')->middleware(['admin.auth'])->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Library management
    Route::get('/library', [App\Http\Controllers\Admin\LibraryController::class, 'index'])->name('library');
    Route::get('/library/{user}', [App\Http\Controllers\Admin\LibraryController::class, 'showUserLibrary'])->name('library.user');

    // Products & Settings
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

    // Admin Users CRUD
    Route::post('/users/save', [AdminController::class, 'saveUser'])->name('users.save');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.destroy');

    // Admin Games CRUD (RESTful endpoints)
    Route::prefix('games')->group(function () {
        Route::get('/', [GameController::class, 'adminIndexAlt'])->name('index'); // view page
        Route::get('/{game}/edit', [GameController::class, 'edit'])->name('edit'); // fetch data
        Route::post('/', [GameController::class, 'store'])->name('store'); // add new
        Route::match(['put', 'patch'], '/{game}', [GameController::class, 'update'])->name('update'); // update
        Route::delete('/{game}', [GameController::class, 'destroy'])->name('destroy'); // delete

        // Per-game banner (gallery) management
        Route::get('/{game}/banners', [GameController::class, 'bannersForGame'])->name('banners.index');
        Route::post('/{game}/banners', [GameController::class, 'storeBanner'])->name('banners.store');
    });

    // Admin Banner Sliders Management
    Route::prefix('banner-sliders')->group(function () {
        Route::post('/', [AdminController::class, 'storeBannerSlider'])->name('banner-sliders.store');
        Route::put('/{slider}', [AdminController::class, 'updateBannerSlider'])->name('banner-sliders.update');
        Route::delete('/{slider}', [AdminController::class, 'destroyBannerSlider'])->name('banner-sliders.destroy');
    });

    // BannerSlider item operations
    Route::delete('/banners/{banner}', [GameController::class, 'destroyBanner'])->name('banners.destroy');
    Route::put('/banners/{banner}/toggle', [GameController::class, 'toggleBannerStatus'])->name('banners.toggle');

    // Admin Games Dashboard (custom page)
    Route::get('/games-dashboard', function () {
        return view('pr'); // your pr.blade.php
    })->name('games.dashboard');

    // Admin Profile Management
    Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::post('/password/change', [AdminController::class, 'changePassword'])->name('password.change');

    // Admin Subscription Plans
    Route::post('/subscription-plans', [AdminController::class, 'storeSubscriptionPlan'])->name('subscription-plans.store');
    Route::put('/subscription-plans/{subscriptionPlan}', [AdminController::class, 'updateSubscriptionPlan'])->name('subscription-plans.update');
    Route::delete('/subscription-plans/{subscriptionPlan}', [AdminController::class, 'destroySubscriptionPlan'])->name('subscription-plans.destroy');

    // Admin Subscriptions
    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');
    Route::post('/subscriptions/{id}/update', [SubscriptionController::class, 'adminUpdate'])->name('subscriptions.update');
    Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
});

// -------------------------
// Email Verification & Wait
// -------------------------
Route::get('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify.email');
Route::get('/verification/wait', function (Request $request) {
    $email = $request->email ?? session('email');
    return view('auth.verification-wait', compact('email'));
})->name('verification.wait');

// -------------------------
// Forgot Password & OTP
// -------------------------
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot.password');
Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('forgot.password.post');

Route::get('/forgot-password/otp', [AuthController::class, 'showOtpPage'])->name('forgot.password.otp');
Route::post('/forgot-password/otp/send', [AuthController::class, 'handleOtp'])->name('forgot.password.otp.send');
Route::get('/forgot-password/otp/verify/{email}', [AuthController::class, 'showOtpVerify'])->name('forgot.password.otp.verify');
Route::post('/forgot-password/otp/verify', [AuthController::class, 'verifyOtp'])->name('forgot.password.otp.verify.post');

Route::get('/forgot-password/old', [AuthController::class, 'showOldPassPage'])->name('forgot.password.old');
Route::post('/forgot-password/old', [AuthController::class, 'handleOldPass'])->name('forgot.password.old.post');

Route::get('/forgot-password/new-pass/{email}', [AuthController::class, 'showNewPassPage'])->name('forgot.password.newpass');
Route::post('/forgot-password/new-pass', [AuthController::class, 'handleNewPass'])->name('forgot.password.newpass.post');

// -------------------------
// Community extra endpoints
// -------------------------
Route::post('/community/send', [CommunityController::class, 'send']);
Route::get('/community/fetch', [CommunityController::class, 'fetch']);

// -------------------------
// API-like AJAX endpoints (optional for extension)
// -------------------------
Route::prefix('api')->group(function () {
    Route::get('/games/search', [FrontController::class, 'searchGames']);
    Route::get('/users/search', [AdminController::class, 'searchUsers']);
    Route::get('/subscriptions/filter', [SubscriptionController::class, 'filterSubscriptions']);
    Route::get('/banner-refresh', [FrontController::class, 'checkBannerRefresh']);
});


// -------------------------
// Fallback Route
// -------------------------
Route::fallback(function () {
    return view('notlogin.index');
});

// -------------------------
// Extra Enhancements Added
// -------------------------
// 1. Grouped admin game routes clearly
// 2. Added optional API-like endpoints for AJAX/search
// 3. Preserved all guest, public, auth, admin, subscription, community, and fallback routes
// 4. Added comments for maintainability and clarity
// 5. Fully compatible with Laravel 10+ conventions