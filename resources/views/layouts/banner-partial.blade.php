<style>
    /* Banner Styles */
    .filter-section-container {
        background: #1a1a1a;
        padding: 20px;
        border-radius: 12px;
        margin: 40px auto 20px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.5);
        border: 1px solid #333;
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
        background: #2a2a2a;
        border: 1px solid #444;
        border-radius: 8px;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 16px;
        min-width: 200px;
    }

    .filter-select:focus {
        outline: none;
        border-color: #ff6b00;
        box-shadow: 0 0 0 2px rgba(255, 107, 0, 0.2);
    }

    .banner {
        height: 720px;
        overflow: hidden;
        position: relative;
        border-radius: 16px;
        box-shadow: 0 12px 36px rgba(0, 0, 0, 0.9);
        border: 2px solid #444;
        margin: 20px auto;
        width: 98%;
        max-width: 2000px;
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
        background: rgba(0, 0, 0, 0.75);
        padding: 10px 14px;
        border-left: 4px solid #ff4d00;
        border-radius: 6px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.6);
        z-index: 20;
    }

    .slider-discount .discount-old {
        text-decoration: line-through;
        color: #aaa;
        font-size: 14px;
        margin-right: 6px;
    }

    .slider-discount .discount-new {
        color: #ff4d00;
        font-weight: bold;
        font-size: 16px;
    }

    .carousel-control-prev, .carousel-control-next {
        width: 60px;
        height: 60px;
        background: rgba(30, 30, 30, 0.8);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        border: 1px solid rgba(255, 165, 0, 0.3);
        position: absolute;
        z-index: 100;
    }

    .carousel-control-prev { left: 20px; }
    .carousel-control-next { right: 20px; }

    .carousel-control-prev:hover, .carousel-control-next:hover {
        background: rgba(255, 165, 0, 0.5);
        transform: translateY(-50%) scale(1.1);
    }

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
        color: #fff;
        margin-bottom: 50px;
        font-size: 42px;
        font-weight: 800;
        text-shadow: 0 4px 12px rgba(0,0,0,0.9);
        letter-spacing: 1px;
        position: relative;
        padding-bottom: 20px;
        text-align: center;
    }

    .games-section h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 150px;
        height: 6px;
        background: linear-gradient(90deg, #ff4d00, #ff8c00);
        border-radius: 6px;
    }

    .games-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
        gap: 40px;
    }

    .game-card {
        background: #1a1a1a;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.4);
        border: 2px solid #444;
        box-shadow: 0 12px 24px rgba(0,0,0,0.5);
        position: relative;
    }

    .game-card:hover {
        transform: translateY(-15px) scale(1.03);
        box-shadow: 0 24px 48px rgba(255, 140, 0, 0.4);
        border-color: #ff6b00;
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
        background: rgba(0, 0, 0, 0.8);
        border-left: 4px solid #ff4d00;
        padding: 8px 12px;
        color: #fff;
        font-size: 14px;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        z-index: 10;
    }

    .discount-old { text-decoration: line-through; color: #aaa; font-size: 13px; }
    .discount-new { color: #ff4d00; font-weight: bold; font-size: 15px; }
    .no-results { grid-column: 1 / -1; text-align: center; padding: 60px; font-size: 24px; color: #777; }
    
    /* Enhanced Banner Styles */
    .slider-title {
        position: absolute;
        top: 30px;
        left: 40px;
        z-index: 20;
    }
    
    .slider-title h3 {
        color: #fff;
        font-size: 2.5rem;
        font-weight: 800;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 2px;
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
        background-color: #ff6b00;
        border-color: #ff6b00;
        transform: scale(1.2);
    }
    
    .carousel-indicators button:hover {
        border-color: #ff6b00;
        background-color: rgba(255, 107, 0, 0.5);
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
</style>

@php
use App\Models\Game;
use App\Models\BannerSlider;

// Get slider games from banner_sliders table
$sliderGames = BannerSlider::getBannerSliders();

// Get all active games for the grid (excluding slider games)
$allGames = Game::where('is_active', true)->get();
$sliderGameSlugs = collect($sliderGames)->pluck('slug');
$gridGames = $allGames->reject(function($game) use ($sliderGameSlugs) {
    return $sliderGameSlugs->contains($game->slug);
});
@endphp

<div class="container mt-5">
    <div class="banner">
        <div id="bannerSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">
                @if(count($sliderGames) > 0)
                    @foreach($sliderGames as $index => $game)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <a href="{{ url('/game/' . $game['slug']) }}">
                                <img src="{{ $game['image'] }}" alt="{{ $game['title'] }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                                <div class="slider-discount">
                                    <span class="discount-old">₹{{ number_format($game['old_price']) }}</span>
                                    <span class="discount-new">₹{{ number_format($game['new_price']) }}</span>
                                </div>
                                <div class="slider-title">
                                    <h3>{{ $game['title'] }}</h3>
                                </div>
                            </a>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const priceFilter = document.getElementById('priceFilter');
    const genreFilter = document.getElementById('genreFilter');
    const gamesGrid = document.getElementById('gamesGrid');
    const gameCards = gamesGrid.querySelectorAll('.game-card');
    const sliderSlugs = @json(collect($sliderGames)->pluck('slug'));

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
                // Smoothly reload the banner section
                console.log('Banner changes detected, refreshing...');
                lastBannerCheck = Date.now();
                window.location.reload();
            }
        })
        .catch(error => {
            console.log('Banner refresh check failed:', error);
        });
    }, 120000); // Check every 2 minutes
    
    // Preload next carousel images for better performance
    const carousel = document.getElementById('bannerSlider');
    if (carousel) {
        carousel.addEventListener('slide.bs.carousel', function (event) {
            const nextSlide = event.relatedTarget;
            const img = nextSlide.querySelector('img');
            if (img && img.loading === 'lazy') {
                img.loading = 'eager';
            }
        });
    }
});
</script>
