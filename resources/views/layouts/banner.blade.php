<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Store</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome CSS for carousel arrow icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    


    <style>
        body {
            background: #1b2838;
            color: #c7d5e0;
            font-family: 'Motiva Sans', Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Navigation Bar Styles */
        .navbar {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.8);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand:hover {
            color: #ffffff !important;
            transform: scale(1.05);
            transition: all 0.3s ease;
        }

        .nav-link {
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 4px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: rgba(103, 193, 245, 0.2);
            color: #ffffff !important;
            transform: translateY(-2px);
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgba(103, 193, 245, 0.3) 0%, transparent 100%);
            color: #ffffff !important;
        }

        .dropdown-menu {
            background: linear-gradient(135deg, #1b2838 0%, #2a475e 100%);
            border: 1px solid #417a9b;
        }

        .dropdown-item {
            color: #c7d5e0;
        }

        .dropdown-item:hover {
            background: rgba(103, 193, 245, 0.2);
            color: #ffffff;
        }

        /* Filter Section */
        .filter-section-container {
            background: linear-gradient(90deg, #1b2838 0%, #2a475e 100%);
            padding: 20px;
            border-radius: 8px;
            margin: 40px auto 20px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.8);
            border: 1px solid #417a9b;
            max-width: 2000px;
            width: 98%;
        }

        .filter-section {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filter-select {
            padding: 12px 20px;
            background: #32465a;
            border: 1px solid #417a9b;
            border-radius: 4px;
            color: #c7d5e0;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
            min-width: 200px;
        }

        .filter-select:hover {
            background: #3d5a73;
            border-color: #67c1f5;
        }

        .filter-select:focus {
            outline: none;
            border-color: #67c1f5;
            box-shadow: 0 0 8px rgba(103, 193, 245, 0.4);
        }

        .banner {
            height: 720px;
            overflow: hidden;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.9);
            border: 1px solid #2a475e;
            margin: 20px auto;
            width: 98%;
            max-width: 2000px;
            background: #0e141b;
        }

        .carousel-inner {
            height: 100%;
            border-radius: 16px;
            overflow: hidden;
        }

        .carousel-item {
            height: 100%;
            width: 100%;
            position: relative;
            transition: transform 0.8s cubic-bezier(0.22, 0.61, 0.36, 1);
        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center center;
            background-color: #000;
            image-rendering: optimizeQuality;
        }

        .slider-discount {
            position: absolute;
            bottom: 30px;
            left: 40px;
            background: linear-gradient(135deg, rgba(27, 40, 56, 0.95) 0%, rgba(42, 71, 94, 0.95) 100%);
            padding: 12px 16px;
            border-left: 4px solid #67c1f5;
            border-radius: 4px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.8);
            z-index: 20;
        }

        .slider-discount .discount-old {
            text-decoration: line-through;
            color: #8f98a0;
            font-size: 14px;
            margin-right: 8px;
        }

        .slider-discount .discount-new {
            color: #beee11;
            font-weight: bold;
            font-size: 18px;
        }

        .carousel-control-prev, .carousel-control-next {
            width: 60px;
            height: 60px;
            background: rgba(27, 40, 56, 0.9);
            border-radius: 4px;
            top: 50%;
            transform: translateY(-50%);
            border: 1px solid rgba(103, 193, 245, 0.3);
            position: absolute;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .carousel-control-prev { left: 20px; }
        .carousel-control-next { right: 20px; }

        .carousel-control-prev:hover, .carousel-control-next:hover {
            background: rgba(103, 193, 245, 0.3);
            border-color: #67c1f5;
            transform: translateY(-50%) scale(1.05);
        }

        /* Fix custom icons */
        .carousel-control-prev-icon::before {
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            content: "\f104";
            font-size: 2rem;
            color: white;
        }

        .carousel-control-next-icon::before {
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            content: "\f105";
            font-size: 2rem;
            color: white;
        }

        .games-section {
            margin: 40px auto;
            padding: 40px 0;
            width: 98%;
            max-width: 2000px;
        }

        .games-section h2 {
            color: #ffffff;
            margin-bottom: 50px;
            font-size: 42px;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(0,0,0,0.8);
            letter-spacing: 0.5px;
            position: relative;
            padding-bottom: 20px;
            text-align: center;
            text-transform: uppercase;
        }

        .games-section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 4px;
            background: linear-gradient(90deg, #06BFFF, #2D73FF);
            border-radius: 2px;
        }

        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
            gap: 40px;
        }

        .game-card {
            background: #1b2838;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #2a475e;
            box-shadow: 0 4px 16px rgba(0,0,0,0.6);
            position: relative;
        }

        .game-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(103, 193, 245, 0.3);
            border-color: #67c1f5;
            z-index: 20;
        }

        .game-card img {
            width: 100%;
            height: 480px;
            object-fit: cover;
            object-position: center;
            display: block;
            transition: transform 0.6s ease;
        }

        .game-card:hover img {
            transform: scale(1.1);
        }

        .game-info { padding: 20px; }

        .game-title { font-size: 20px; font-weight: 600; margin-bottom: 10px; color: #fff; }

        .game-price { display: flex; align-items: center; gap: 10px; }

        .discount-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            background: linear-gradient(135deg, #4c6b22 0%, #5c7e10 100%);
            padding: 8px 12px;
            color: #beee11;
            font-size: 14px;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.6);
            z-index: 10;
            font-weight: 600;
        }

        .discount-old { 
            text-decoration: line-through; 
            color: #8f98a0; 
            font-size: 13px; 
            margin-right: 6px;
        }
        .discount-new { 
            color: #beee11; 
            font-weight: bold; 
            font-size: 15px; 
        }

        .no-results { grid-column: 1 / -1; text-align: center; padding: 60px; font-size: 24px; color: #777; }

        .container { max-width: 2100px; padding: 0 50px; }
        
        /* Enhanced Banner Styles */
        .slider-title {
            position: absolute;
            top: 30px;
            left: 40px;
            z-index: 20;
            background: linear-gradient(90deg, rgba(27, 40, 56, 0.8) 0%, transparent 100%);
            padding: 15px 30px 15px 20px;
            border-radius: 4px;
        }
        
        .slider-title h3 {
            color: #ffffff;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 12px rgba(0,0,0,0.9);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .no-banners-message {
            width: 100%;
            height: 720px;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
        }
        
        .carousel-indicators {
            bottom: 20px;
            margin-bottom: 0;
        }
        
        .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.5);
            background-color: transparent;
            margin: 0 6px;
            transition: all 0.3s ease;
        }
        
        .carousel-indicators button.active {
            background-color: #67c1f5;
            border-color: #67c1f5;
            transform: scale(1.2);
        }
        
        .carousel-indicators button:hover {
            border-color: #67c1f5;
            background-color: rgba(103, 193, 245, 0.5);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .slider-title h3 {
                font-size: 1.8rem;
            }
            
            .slider-discount {
                bottom: 15px;
                left: 20px;
                padding: 8px 12px;
            }
            
            .slider-title {
                top: 20px;
                left: 20px;
            }
        }

        /* Sale Banner */
        .sale-banner-container {
            width: 98%;
            max-width: 2000px;
            margin: 0 auto 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.6);
            background: linear-gradient(135deg, #1b2838 0%, #2a475e 50%, #1b2838 100%);
            border: 1px solid #417a9b;
            position: relative;
            animation: bannerGlow 3s ease-in-out infinite;
        }

        @keyframes bannerGlow {
            0%, 100% { box-shadow: 0 4px 16px rgba(0, 0, 0, 0.6); }
            50% { box-shadow: 0 4px 24px rgba(103, 193, 245, 0.4); }
        }

        .sale-banner-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 40px;
            position: relative;
        }

        .sale-banner-left {
            flex: 1;
        }

        .sale-title {
            font-size: 3rem;
            font-weight: 800;
            color: #ffffff;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 20px rgba(103, 193, 245, 0.5);
            animation: titlePulse 2s ease-in-out infinite;
        }

        @keyframes titlePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .sale-subtitle {
            font-size: 1.2rem;
            color: #c7d5e0;
            margin: 10px 0 0 0;
        }

        .sale-banner-right {
            text-align: right;
        }

        .sale-badge {
            background: linear-gradient(135deg, #4c6b22 0%, #5c7e10 100%);
            color: #beee11;
            font-size: 2rem;
            font-weight: 800;
            padding: 15px 30px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 4px 16px rgba(92, 126, 16, 0.6);
            animation: badgeBounce 1.5s ease-in-out infinite;
        }

        @keyframes badgeBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .sale-date {
            color: #67c1f5;
            font-size: 1rem;
            margin: 10px 0 0 0;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .sale-banner-content {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }
            
            .sale-title {
                font-size: 2rem;
            }
            
            .sale-banner-right {
                text-align: center;
                margin-top: 20px;
            }
            
            .sale-badge {
                font-size: 1.5rem;
                padding: 10px 20px;
            }
        }

        /* Responsive styles omitted for brevity - keep same as your original */
    </style>
</head>
<body>

<!-- Steam-style Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, #1b2838 0%, #2a475e 100%); border-bottom: 2px solid #417a9b;">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}" style="color: #67c1f5; font-weight: 700; font-size: 1.8rem; text-transform: uppercase; letter-spacing: 1px;">
            <i class="fas fa-gamepad"></i> STEAM STORE
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}" style="color: #c7d5e0; font-weight: 500;">
                        <i class="fas fa-home"></i> Store
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('notlogin.community') }}" style="color: #c7d5e0; font-weight: 500;">
                        <i class="fas fa-users"></i> Community
                    </a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('library') }}" style="color: #c7d5e0; font-weight: 500;">
                        <i class="fas fa-book"></i> Library
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.show') }}" style="color: #c7d5e0; font-weight: 500;">
                        <i class="fas fa-user"></i> Profile
                    </a>
                </li>
                @if(auth()->user()->is_admin)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}" style="color: #beee11; font-weight: 600;">
                        <i class="fas fa-cog"></i> Admin
                    </a>
                </li>
                @endif
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}" style="color: #67c1f5; font-weight: 600;">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

@php
use App\Models\Game;
use App\Models\BannerSlider;

// Get slider games from banner_sliders table (dynamic from admin)
$sliderGames = BannerSlider::getBannerSliders();

// Get all active games for the grid (excluding slider games)
$allGames = Game::where('is_active', true)->orderBy('is_featured', 'desc')->get();
$sliderGameSlugs = collect($sliderGames)->pluck('slug');
$gridGames = $allGames->reject(function($game) use ($sliderGameSlugs) {
    return $sliderGameSlugs->contains($game->slug);
});
@endphp

<div class="container mt-5">
    <!-- Autumn Sale Banner -->
    <div class="sale-banner-container">
        <div class="sale-banner-content">
            <div class="sale-banner-left">
                <h2 class="sale-title">🍂 AUTUMN SALE</h2>
                <p class="sale-subtitle">Massive discounts on thousands of games!</p>
            </div>
            <div class="sale-banner-right">
                <div class="sale-badge">UP TO 90% OFF</div>
                <p class="sale-date">Limited Time Offer</p>
            </div>
        </div>
    </div>

    <div class="banner">
        <div id="bannerSlider" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @if(count($sliderGames) > 0)
                @foreach($sliderGames as $index => $game)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" onclick="window.location.href='{{ url('/game/' . $game['slug']) }}'" style="cursor: pointer;">
                        <img src="{{ $game['image'] }}" alt="{{ $game['title'] }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                        <div class="slider-discount">
                            <span class="discount-old">₹{{ number_format($game['old_price']) }}</span>
                            <span class="discount-new">₹{{ number_format($game['new_price']) }}</span>
                        </div>
                        <div class="slider-title">
                            <h3>{{ $game['title'] }}</h3>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="carousel-item active">
                    <div class="no-banners-message">
                        <div class="text-center py-5">
                            <i class="fas fa-images fa-4x text-muted mb-4"></i>
                            <h3 class="text-white mb-3">No Banner Sliders Available</h3>
                            <p class="text-muted">Admin can add banner sliders from the admin panel</p>
                        </div>
                    </div>
                </div>
            @endif
            </div>
            @if(count($sliderGames) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#bannerSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#bannerSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
                
                <!-- Carousel indicators -->
                <div class="carousel-indicators">
                    @foreach($sliderGames as $index => $game)
                        <button type="button" data-bs-target="#bannerSlider" data-bs-slide-to="{{ $index }}" 
                                class="{{ $index === 0 ? 'active' : '' }}" 
                                aria-current="{{ $index === 0 ? 'true' : 'false' }}" 
                                aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="filter-section-container">
        <div class="row">
            <div class="col-12">
                <div class="filter-section">
                    <select class="filter-select" id="priceFilter">
                        <option value="all">All Prices</option>
                        <option value="free">Free</option>
                        <option value="under1000">Under ₹1000</option>
                        <option value="1000-2000">₹1000 - ₹2000</option>
                        <option value="over2000">Over ₹2000</option>
                    </select>
                    <select class="filter-select" id="genreFilter">
                        <option value="all">All Genres</option>
                        <option value="action">Action</option>
                        <option value="adventure">Adventure</option>
                        <option value="rpg">RPG</option>
                        <option value="shooter">Shooter</option>
                        <option value="racing">Racing</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="games-section">
        <h2>Featured Games</h2>
        <div class="games-grid" id="gamesGrid">
            @foreach($gridGames as $game)
                <div class="game-card" data-title="{{ strtolower($game->title) }}" data-price="{{ $game->price }}" data-genre="{{ strtolower($game->genre) }}">
                    <a href="{{ url('/game/' . $game->slug) }}">
                        <img src="{{ $game->image_url }}" alt="{{ $game->title }}">
                        <div class="discount-badge">
                            <span class="discount-old">₹{{ $game->original_price ?: $game->price }}</span>
                            <span class="discount-new">{{ $game->price == 0 ? 'Free' : '₹'.$game->price }}</span>
                        </div>
                        <div class="game-info">
                            <div class="game-title">{{ $game->title }}</div>
                            <div class="game-price">
                                <span class="discount-new">{{ $game->price == 0 ? 'Free' : '₹'.$game->price }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const priceFilter = document.getElementById('priceFilter');
    const genreFilter = document.getElementById('genreFilter');
    const gamesGrid = document.getElementById('gamesGrid');
    const gameCards = gamesGrid.querySelectorAll('.game-card');
    const sliderSlugs = @json(collect($sliderGames)->pluck('slug'));
    
    // Auto-refresh banner every 2 minutes to show new slider changes
    let lastBannerCheck = Date.now();
    setInterval(function() {
        fetch('/api/banner-refresh', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok');
        })
        .then(data => {
            if (data.hasSliderChanges && data.lastUpdate > lastBannerCheck) {
                console.log('Banner changes detected, refreshing...');
                lastBannerCheck = Date.now();
                window.location.reload();
            }
        })
        .catch(error => {
            console.log('Banner refresh check failed:', error);
        });
    }, 120000); // Check every 2 minutes

    function filterGames() {
        const priceValue = priceFilter.value;
        const genreValue = genreFilter.value;
        let hasResults = false;

        gameCards.forEach(card => {
            const title = card.dataset.title.toLowerCase();
            const price = parseInt(card.dataset.price);
            const genre = card.dataset.genre.toLowerCase();

            if (sliderSlugs.includes(title)) {
                card.style.display = 'none';
                return;
            }

            let matchesPrice = true;
            if (priceValue === 'free') matchesPrice = price === 0;
            else if (priceValue === 'under1000') matchesPrice = price > 0 && price < 1000;
            else if (priceValue === '1000-2000') matchesPrice = price >= 1000 && price <= 2000;
            else if (priceValue === 'over2000') matchesPrice = price > 2000;

            const matchesGenre = genreValue === 'all' || genre === genreValue;

            if (matchesPrice && matchesGenre) {
                card.style.display = 'block';
                hasResults = true;
            } else {
                card.style.display = 'none';
            }
        });

        const noResults = document.getElementById('noResults');
        if (!hasResults) {
            if (!noResults) {
                const noResultsDiv = document.createElement('div');
                noResultsDiv.id = 'noResults';
                noResultsDiv.className = 'no-results';
                noResultsDiv.textContent = 'No games found matching your criteria.';
                gamesGrid.appendChild(noResultsDiv);
            }
        } else if (noResults) {
            noResults.remove();
        }
    }

    priceFilter.addEventListener('change', filterGames);
    genreFilter.addEventListener('change', filterGames);
    filterGames();
});
</script>
</body>
</html>
