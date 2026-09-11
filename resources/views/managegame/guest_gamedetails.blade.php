@extends('front.gamefront')

@section('title', $game->title)

@section('Content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #1b2838;
        color: #c7d5e0;
        font-family: 'Motiva Sans', Arial, Helvetica, sans-serif;
    }

    /* Hero Section */
    .game-hero {
        position: relative;
        width: 100%;
        height: 450px;
        background: url('{{ $game->imageUrl }}') center center / cover no-repeat;
        margin: 0;
        display: flex;
        align-items: flex-end;
    }

    .game-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.4) 50%, rgba(27, 40, 56, 0.95) 100%);
    }

    .hero-content {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        padding: 40px 60px;
    }

    .game-title-hero {
        font-size: 3rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0 0 15px 0;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
    }

    .game-meta {
        display: flex;
        gap: 15px;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .meta-tag {
        background: rgba(103, 193, 245, 0.15);
        padding: 6px 14px;
        border-radius: 3px;
        font-size: 0.85rem;
        color: #67c1f5;
        border: 1px solid rgba(103, 193, 245, 0.3);
    }

    .hero-actions {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-add-cart {
        background: linear-gradient(135deg, #5c7e10 0%, #4c6b22 100%);
        color: #beee11;
        border: none;
        padding: 15px 45px;
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 3px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-add-cart:hover {
        background: linear-gradient(135deg, #6fae12 0%, #5c7e10 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(92, 126, 16, 0.6);
    }

    .price-box {
        background: rgba(0, 0, 0, 0.6);
        padding: 15px 30px;
        border-radius: 3px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .discount-percent {
        background: #4c6b22;
        color: #beee11;
        padding: 10px 15px;
        font-size: 1.5rem;
        font-weight: 700;
        border-radius: 3px;
    }

    .price-info {
        display: flex;
        flex-direction: column;
    }

    .original-price {
        text-decoration: line-through;
        color: #8f98a0;
        font-size: 0.9rem;
    }

    .current-price {
        color: #beee11;
        font-size: 1.8rem;
        font-weight: 700;
    }

    /* Main Content Area */
    .game-content-wrapper {
        max-width: 1600px;
        margin: 0 auto;
        padding: 30px 60px;
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
    }

    .main-content {
        min-width: 0;
    }

    /* Media Carousel Section */
    .media-carousel {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 4px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .main-media-display {
        position: relative;
        width: 100%;
        aspect-ratio: 16/9;
        background: #000;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 15px;
    }

    .main-media-display img,
    .main-media-display video {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .media-thumbnails {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 8px;
    }

    .media-thumb {
        position: relative;
        aspect-ratio: 16/9;
        border-radius: 3px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s ease;
    }

    .media-thumb:hover {
        border-color: #67c1f5;
        transform: scale(1.05);
    }

    .media-thumb.active {
        border-color: #ffffff;
    }

    .media-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .media-thumb.video::after {
        content: '▶';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 2rem;
        color: #ffffff;
        text-shadow: 0 2px 8px rgba(0,0,0,0.8);
    }

    /* About Section */
    .about-section {
        background: rgba(0, 0, 0, 0.3);
        padding: 25px;
        border-radius: 4px;
        margin-bottom: 25px;
    }

    .section-title {
        color: #ffffff;
        font-size: 1.3rem;
        margin-bottom: 15px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .about-section p {
        color: #c7d5e0;
        line-height: 1.8;
        font-size: 0.95rem;
    }

    /* Features Grid */
    .features-section {
        background: rgba(0, 0, 0, 0.3);
        padding: 25px;
        border-radius: 4px;
        margin-bottom: 25px;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: rgba(103, 193, 245, 0.1);
        border-radius: 3px;
        border: 1px solid rgba(103, 193, 245, 0.2);
    }

    .feature-item i {
        color: #67c1f5;
        font-size: 1.2rem;
    }

    .feature-item span {
        color: #c7d5e0;
        font-size: 0.9rem;
    }

    /* System Requirements */
    .requirements-section {
        background: rgba(0, 0, 0, 0.3);
        padding: 25px;
        border-radius: 4px;
        margin-bottom: 25px;
    }

    .requirements-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .req-column {
        background: rgba(0, 0, 0, 0.2);
        padding: 20px;
        border-radius: 4px;
    }

    .req-column h4 {
        color: #67c1f5;
        font-size: 1.1rem;
        margin-bottom: 15px;
        text-transform: uppercase;
    }

    .req-column p {
        color: #c7d5e0;
        white-space: pre-wrap;
        line-height: 1.7;
        font-size: 0.9rem;
    }

    /* Reviews Section */
    .reviews-section {
        background: rgba(0, 0, 0, 0.3);
        padding: 25px;
        border-radius: 4px;
        margin-bottom: 25px;
    }

    .review-summary {
        display: flex;
        gap: 30px;
        margin-bottom: 20px;
        padding: 20px;
        background: rgba(92, 126, 16, 0.1);
        border-radius: 4px;
        border-left: 4px solid #5c7e10;
    }

    .review-score {
        text-align: center;
    }

    .review-percentage {
        font-size: 3rem;
        font-weight: 700;
        color: #beee11;
    }

    .review-label {
        color: #c7d5e0;
        font-size: 0.9rem;
        margin-top: 5px;
    }

    .review-bars {
        flex: 1;
    }

    .review-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .review-bar-label {
        width: 80px;
        color: #c7d5e0;
        font-size: 0.85rem;
    }

    .review-bar-fill {
        flex: 1;
        height: 8px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        overflow: hidden;
    }

    .review-bar-inner {
        height: 100%;
        background: linear-gradient(90deg, #5c7e10, #beee11);
        border-radius: 4px;
    }

    /* Sidebar */
    .sidebar-content {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 4px;
        padding: 20px;
        position: sticky;
        top: 20px;
    }

    .sidebar-section {
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .sidebar-title {
        color: #8f98a0;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .sidebar-value {
        color: #ffffff;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .sidebar-link {
        color: #67c1f5;
        text-decoration: none;
        transition: color 0.2s;
    }

    .sidebar-link:hover {
        color: #ffffff;
    }

    .platform-icons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .platform-icon {
        background: rgba(103, 193, 245, 0.1);
        padding: 8px 12px;
        border-radius: 3px;
        font-size: 0.85rem;
        color: #67c1f5;
        border: 1px solid rgba(103, 193, 245, 0.2);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .languages-list {
        color: #c7d5e0;
        font-size: 0.85rem;
        line-height: 1.6;
    }

    /* Achievements Preview */
    .achievements-preview {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-top: 10px;
    }

    .achievement-icon {
        aspect-ratio: 1;
        background: rgba(103, 193, 245, 0.1);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(103, 193, 245, 0.2);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .achievement-icon:hover {
        background: rgba(103, 193, 245, 0.2);
        transform: scale(1.1);
    }

    .achievement-icon i {
        color: #67c1f5;
        font-size: 1.5rem;
    }

    /* Popular Tags */
    .tags-section {
        background: rgba(0, 0, 0, 0.3);
        padding: 25px;
        border-radius: 4px;
        margin-bottom: 25px;
    }

    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .game-tag {
        background: rgba(103, 193, 245, 0.15);
        padding: 8px 16px;
        border-radius: 3px;
        font-size: 0.85rem;
        color: #67c1f5;
        border: 1px solid rgba(103, 193, 245, 0.3);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .game-tag:hover {
        background: rgba(103, 193, 245, 0.3);
        transform: translateY(-2px);
    }

    /* Similar Games */
    .similar-games-section {
        background: rgba(0, 0, 0, 0.3);
        padding: 25px;
        border-radius: 4px;
        margin-bottom: 25px;
    }

    .similar-games-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .similar-game-card {
        background: rgba(0, 0, 0, 0.4);
        border-radius: 4px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .similar-game-card:hover {
        transform: translateY(-5px);
        border-color: #67c1f5;
        box-shadow: 0 8px 24px rgba(103, 193, 245, 0.3);
    }

    .similar-game-card img {
        width: 100%;
        aspect-ratio: 16/9;
        object-fit: cover;
    }

    .similar-game-info {
        padding: 12px;
    }

    .similar-game-title {
        color: #ffffff;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .similar-game-price {
        color: #beee11;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* DLC Section */
    .dlc-section {
        background: rgba(0, 0, 0, 0.3);
        padding: 25px;
        border-radius: 4px;
        margin-bottom: 25px;
    }

    .dlc-item {
        display: flex;
        gap: 15px;
        padding: 15px;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 4px;
        margin-bottom: 10px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .dlc-item:hover {
        background: rgba(0, 0, 0, 0.5);
        border-color: #67c1f5;
    }

    .dlc-image {
        width: 120px;
        aspect-ratio: 16/9;
        object-fit: cover;
        border-radius: 3px;
    }

    .dlc-info {
        flex: 1;
    }

    .dlc-title {
        color: #ffffff;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .dlc-description {
        color: #8f98a0;
        font-size: 0.85rem;
        margin-bottom: 10px;
    }

    .dlc-price {
        color: #beee11;
        font-size: 1.1rem;
        font-weight: 700;
    }

    /* Community Hub */
    .community-section {
        background: rgba(0, 0, 0, 0.3);
        padding: 25px;
        border-radius: 4px;
        margin-bottom: 25px;
    }

    .community-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-top: 15px;
    }

    .community-stat {
        background: rgba(103, 193, 245, 0.1);
        padding: 20px;
        border-radius: 4px;
        text-align: center;
        border: 1px solid rgba(103, 193, 245, 0.2);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .community-stat:hover {
        background: rgba(103, 193, 245, 0.2);
        transform: translateY(-3px);
    }

    .community-stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #67c1f5;
        margin-bottom: 5px;
    }

    .community-stat-label {
        color: #c7d5e0;
        font-size: 0.85rem;
    }

    /* Legal Info */
    .legal-section {
        background: rgba(0, 0, 0, 0.2);
        padding: 20px;
        border-radius: 4px;
        margin-top: 25px;
    }

    .legal-text {
        color: #8f98a0;
        font-size: 0.75rem;
        line-height: 1.6;
    }

    /* Share Buttons */
    .share-section {
        margin-top: 15px;
    }

    .share-buttons {
        display: flex;
        gap: 10px;
    }

    .share-btn {
        background: rgba(103, 193, 245, 0.1);
        color: #67c1f5;
        border: 1px solid rgba(103, 193, 245, 0.3);
        padding: 10px 20px;
        border-radius: 3px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .share-btn:hover {
        background: rgba(103, 193, 245, 0.3);
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .game-content-wrapper {
            grid-template-columns: 1fr;
            padding: 20px 30px;
        }

        .sidebar-content {
            position: static;
        }

        .requirements-grid {
            grid-template-columns: 1fr;
        }

        .media-thumbnails {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        }

        .community-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .similar-games-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .community-stats {
            grid-template-columns: 1fr;
        }

        .similar-games-grid {
            grid-template-columns: 1fr;
        }

        .share-buttons {
            flex-direction: column;
        }

        .share-btn {
            width: 100%;
            justify-content: center;
        }
        .game-hero {
            height: 350px;
        }

        .hero-content {
            padding: 20px 30px;
        }

        .game-title-hero {
            font-size: 2rem;
        }

        .btn-add-cart {
            padding: 12px 30px;
            font-size: 1rem;
        }

        .game-content-wrapper {
            padding: 15px 20px;
        }

        .features-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Hero Section -->
<div class="game-hero">
    <div class="hero-content">
        <h1 class="game-title-hero">{{ $game->title }}</h1>
        
        <div class="game-meta">
            @if(!empty($game->genre))
                <span class="meta-tag">{{ ucfirst($game->genre) }}</span>
            @endif
            @if(!empty($game->tags))
                @foreach(explode(',', $game->tags) as $tag)
                    <span class="meta-tag">{{ trim($tag) }}</span>
                @endforeach
            @endif
            @if($game->release_date)
                <span class="meta-tag">{{ $game->release_date->format('Y') }}</span>
            @endif
        </div>

        <div class="hero-actions">
            <form action="{{ route('cart.add') }}" method="POST" style="margin: 0;">
                @csrf
                <input type="hidden" name="game_id" value="{{ $game->id }}">
                <input type="hidden" name="game_title" value="{{ $game->title }}">
                <input type="hidden" name="game_image" value="{{ $game->imageUrl }}">
                <input type="hidden" name="price" value="{{ $game->price }}">
                <button type="submit" class="btn-add-cart">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </form>

            <div class="price-box">
                @if($game->original_price && $game->original_price > $game->price)
                    <div class="discount-percent">-{{ round((($game->original_price - $game->price) / $game->original_price) * 100) }}%</div>
                @endif
                <div class="price-info">
                    @if($game->original_price && $game->original_price > $game->price)
                        <span class="original-price">₹{{ number_format($game->original_price, 0) }}</span>
                    @endif
                    <span class="current-price">{{ $game->price == 0 ? 'Free to Play' : '₹' . number_format($game->price, 0) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="game-content-wrapper">
    <!-- Left Column -->
    <div class="main-content">
        <!-- Media Carousel -->
        @php
            $bannerImages = isset($banners) && count($banners) ? $banners->map(function($b){ return $b->image_path ? Storage::disk('public')->url($b->image_path) : asset('images/placeholder.jpg'); }) : collect();
        @endphp
        
        <div class="media-carousel">
            <div class="main-media-display" id="mainMediaDisplay">
                @if(!empty($game->trailer_url))
                    <video controls id="mainVideo">
                        <source src="{{ $game->trailer_url }}" type="video/mp4">
                    </video>
                @else
                    <img src="{{ $game->imageUrl }}" alt="{{ $game->title }}" id="mainImage">
                @endif
            </div>

            <div class="media-thumbnails">
                @if(!empty($game->trailer_url))
                    <div class="media-thumb video active" onclick="showMedia('video', '{{ $game->trailer_url }}', this)">
                        <img src="{{ $game->imageUrl }}" alt="Trailer">
                    </div>
                @endif
                
                @if($bannerImages->count() > 0)
                    @foreach($bannerImages as $idx => $img)
                        <div class="media-thumb" onclick="showMedia('image', '{{ $img }}', this)">
                            <img src="{{ $img }}" alt="Screenshot {{ $idx+1 }}">
                        </div>
                    @endforeach
                @else
                    <div class="media-thumb {{ empty($game->trailer_url) ? 'active' : '' }}" onclick="showMedia('image', '{{ $game->imageUrl }}', this)">
                        <img src="{{ $game->imageUrl }}" alt="{{ $game->title }}">
                    </div>
                @endif
            </div>
        </div>

        <!-- About Section -->
        <div class="about-section">
            <h2 class="section-title">About This Game</h2>
            <p>{{ $game->description }}</p>
        </div>

        <!-- Features -->
        <div class="features-section">
            <h3 class="section-title">Features</h3>
            <div class="features-grid">
                <div class="feature-item">
                    <i class="fas fa-user"></i>
                    <span>Single Player</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-gamepad"></i>
                    <span>Full Controller Support</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-trophy"></i>
                    <span>Steam Achievements</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-cloud"></i>
                    <span>Steam Cloud</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-language"></i>
                    <span>Multi-language</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-desktop"></i>
                    <span>4K Ultra HD</span>
                </div>
            </div>
        </div>

        <!-- System Requirements -->
        <div class="requirements-section">
            <h3 class="section-title">System Requirements</h3>
            <div class="requirements-grid">
                <div class="req-column">
                    <h4>Minimum</h4>
                    <p>{{ $game->min_requirements ?? "OS: Windows 7/8/10 (64-bit)\nProcessor: Intel Core i5-2500K\nMemory: 8 GB RAM\nGraphics: NVIDIA GTX 660\nStorage: 50 GB available space" }}</p>
                </div>
                <div class="req-column">
                    <h4>Recommended</h4>
                    <p>{{ $game->rec_requirements ?? "OS: Windows 10 (64-bit)\nProcessor: Intel Core i7-4770K\nMemory: 16 GB RAM\nGraphics: NVIDIA GTX 1060\nStorage: 50 GB available space" }}</p>
                </div>
            </div>
        </div>

        <!-- Reviews Summary -->
        <div class="reviews-section">
            <h3 class="section-title">Reviews</h3>
            <div class="review-summary">
                <div class="review-score">
                    <div class="review-percentage">92%</div>
                    <div class="review-label">Positive Reviews</div>
                </div>
                <div class="review-bars">
                    <div class="review-bar">
                        <span class="review-bar-label">Positive</span>
                        <div class="review-bar-fill">
                            <div class="review-bar-inner" style="width: 92%"></div>
                        </div>
                    </div>
                    <div class="review-bar">
                        <span class="review-bar-label">Negative</span>
                        <div class="review-bar-fill">
                            <div class="review-bar-inner" style="width: 8%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Tags -->
        <div class="tags-section">
            <h3 class="section-title">Popular Tags</h3>
            <div class="tags-container">
                @if(!empty($game->tags))
                    @foreach(explode(',', $game->tags) as $tag)
                        <span class="game-tag">{{ trim($tag) }}</span>
                    @endforeach
                @endif
                <span class="game-tag">{{ ucfirst($game->genre ?? 'Action') }}</span>
                <span class="game-tag">Adventure</span>
                <span class="game-tag">Open World</span>
                <span class="game-tag">Story Rich</span>
                <span class="game-tag">Atmospheric</span>
                <span class="game-tag">Great Soundtrack</span>
                <span class="game-tag">Singleplayer</span>
                <span class="game-tag">Immersive</span>
            </div>
        </div>

        <!-- DLC & Add-ons -->
        <div class="dlc-section">
            <h3 class="section-title">Downloadable Content</h3>
            <div class="dlc-item">
                <img src="{{ $game->imageUrl }}" alt="DLC" class="dlc-image">
                <div class="dlc-info">
                    <div class="dlc-title">{{ $game->title }} - Season Pass</div>
                    <div class="dlc-description">Get access to all future DLC content and expansions</div>
                    <div class="dlc-price">₹{{ number_format($game->price * 0.5, 0) }}</div>
                </div>
            </div>
            <div class="dlc-item">
                <img src="{{ $game->imageUrl }}" alt="DLC" class="dlc-image">
                <div class="dlc-info">
                    <div class="dlc-title">{{ $game->title }} - Deluxe Edition Upgrade</div>
                    <div class="dlc-description">Includes exclusive weapons, skins, and digital artbook</div>
                    <div class="dlc-price">₹{{ number_format($game->price * 0.3, 0) }}</div>
                </div>
            </div>
        </div>

        <!-- Community Hub -->
        <div class="community-section">
            <h3 class="section-title">Community Hub</h3>
            <div class="community-stats">
                <div class="community-stat">
                    <div class="community-stat-value">15.2K</div>
                    <div class="community-stat-label">Discussions</div>
                </div>
                <div class="community-stat">
                    <div class="community-stat-value">8.5K</div>
                    <div class="community-stat-label">Screenshots</div>
                </div>
                <div class="community-stat">
                    <div class="community-stat-value">3.2K</div>
                    <div class="community-stat-label">Artwork</div>
                </div>
                <div class="community-stat">
                    <div class="community-stat-value">1.8K</div>
                    <div class="community-stat-label">Guides</div>
                </div>
            </div>
        </div>

        <!-- Similar Games -->
        @if(isset($relatedGames) && $relatedGames->count() > 0)
        <div class="similar-games-section">
            <h3 class="section-title">More Like This</h3>
            <div class="similar-games-grid">
                @foreach($relatedGames as $relatedGame)
                <a href="{{ url('/game/' . $relatedGame->slug) }}" class="similar-game-card">
                    <img src="{{ $relatedGame->image_url }}" alt="{{ $relatedGame->title }}">
                    <div class="similar-game-info">
                        <div class="similar-game-title">{{ $relatedGame->title }}</div>
                        <div class="similar-game-price">₹{{ number_format($relatedGame->price, 0) }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Legal Information -->
        <div class="legal-section">
            <p class="legal-text">
                © {{ date('Y') }} {{ $game->developer ?? 'Game Developer' }}. All rights reserved. All trademarks are property of their respective owners. 
                {{ $game->title }} and related logos are trademarks or registered trademarks of {{ $game->developer ?? 'the developer' }}.
            </p>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="sidebar-content">
        <div class="sidebar-section">
            <div class="sidebar-title">Developer</div>
            <div class="sidebar-value">
                <a href="#" class="sidebar-link">{{ $game->developer ?? 'Unknown Developer' }}</a>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Publisher</div>
            <div class="sidebar-value">
                <a href="#" class="sidebar-link">{{ $game->developer ?? 'Unknown Publisher' }}</a>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Release Date</div>
            <div class="sidebar-value">{{ $game->release_date ? $game->release_date->format('M d, Y') : 'Coming Soon' }}</div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Platform</div>
            <div class="platform-icons">
                <span class="platform-icon"><i class="fab fa-windows"></i> Windows</span>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Genre</div>
            <div class="sidebar-value">{{ ucfirst($game->genre ?? 'Action') }}</div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Languages</div>
            <div class="languages-list">
                English, French, German, Spanish, Japanese, Korean, Chinese (Simplified), Russian
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Achievements</div>
            <div class="achievements-preview">
                <div class="achievement-icon" title="Master Explorer"><i class="fas fa-star"></i></div>
                <div class="achievement-icon" title="Champion"><i class="fas fa-medal"></i></div>
                <div class="achievement-icon" title="King of the Hill"><i class="fas fa-crown"></i></div>
                <div class="achievement-icon" title="Treasure Hunter"><i class="fas fa-gem"></i></div>
                <div class="achievement-icon" title="Speed Demon"><i class="fas fa-fire"></i></div>
                <div class="achievement-icon" title="Lightning Strike"><i class="fas fa-bolt"></i></div>
            </div>
            <div class="sidebar-value" style="margin-top: 10px; font-size: 0.85rem;">
                <a href="#" class="sidebar-link">View all 50 achievements</a>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Share</div>
            <div class="share-section">
                <div class="share-buttons">
                    <button class="share-btn" onclick="shareGame('facebook')">
                        <i class="fab fa-facebook"></i> Share
                    </button>
                    <button class="share-btn" onclick="shareGame('twitter')">
                        <i class="fab fa-twitter"></i> Tweet
                    </button>
                </div>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Report</div>
            <div class="sidebar-value">
                <a href="#" class="sidebar-link">Report this product</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
function showMedia(type, src, element) {
    const mainDisplay = document.getElementById('mainMediaDisplay');
    
    // Remove active class from all thumbnails
    document.querySelectorAll('.media-thumb').forEach(thumb => thumb.classList.remove('active'));
    element.classList.add('active');
    
    if (type === 'video') {
        mainDisplay.innerHTML = `<video controls id="mainVideo" autoplay><source src="${src}" type="video/mp4"></video>`;
    } else {
        mainDisplay.innerHTML = `<img src="${src}" alt="Game Screenshot" id="mainImage">`;
    }
}

function shareGame(platform) {
    const gameTitle = "{{ $game->title }}";
    const gameUrl = window.location.href;
    
    if (platform === 'facebook') {
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(gameUrl)}`, '_blank');
    } else if (platform === 'twitter') {
        window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent('Check out ' + gameTitle + '!')}&url=${encodeURIComponent(gameUrl)}`, '_blank');
    }
}

// Add smooth scroll for internal links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Achievement hover tooltips
document.querySelectorAll('.achievement-icon').forEach(icon => {
    icon.addEventListener('mouseenter', function() {
        const title = this.getAttribute('title');
        if (title) {
            const tooltip = document.createElement('div');
            tooltip.className = 'achievement-tooltip';
            tooltip.textContent = title;
            tooltip.style.cssText = 'position: absolute; background: rgba(0,0,0,0.9); color: #fff; padding: 5px 10px; border-radius: 3px; font-size: 0.8rem; pointer-events: none; z-index: 1000;';
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
            
            this._tooltip = tooltip;
        }
    });
    
    icon.addEventListener('mouseleave', function() {
        if (this._tooltip) {
            this._tooltip.remove();
            this._tooltip = null;
        }
    });
});
</script>
@endsection
