@extends('front.gamefront')

@section('title', $game->title)

@section('Content')
<style>
    body {
        background: #1b2838;
        color: #c7d5e0;
        margin: 0;
        padding: 0;
    }

    /* Hero Section - Full Width Like Steam */
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
        background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.3) 50%, rgba(27, 40, 56, 0.95) 100%);
    }

    .hero-overlay {
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
        margin: 0 0 10px 0;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
    }

    .game-meta {
        display: flex;
        gap: 20px;
        align-items: center;
        margin-bottom: 20px;
    }

    .game-meta span {
        background: rgba(103, 193, 245, 0.2);
        padding: 5px 12px;
        border-radius: 3px;
        font-size: 0.9rem;
        color: #67c1f5;
    }

    .hero-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .btn-add-cart {
        background: linear-gradient(135deg, #5c7e10 0%, #4c6b22 100%);
        color: #beee11;
        border: none;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 3px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-add-cart:hover {
        background: linear-gradient(135deg, #6fae12 0%, #5c7e10 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(92, 126, 16, 0.6);
    }

    .price-box {
        background: rgba(0, 0, 0, 0.5);
        padding: 15px 25px;
        border-radius: 3px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .discount-percent {
        background: #4c6b22;
        color: #beee11;
        padding: 8px 12px;
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

    /* Content Layout - Full Width */
    .container-steam {
        max-width: 100%;
        margin: 0;
        padding: 0;
    }

    .game-content {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 0;
        max-width: 1600px;
        margin: 0 auto;
        padding: 20px 60px;
    }

    .main-content {
        background: transparent;
        padding-right: 30px;
    }

    .sidebar-content {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 4px;
        padding: 20px;
    }

    /* Screenshots Section */
    .screenshots-section {
        background: rgba(0, 0, 0, 0.2);
        padding: 20px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .screenshots-section h3 {
        color: #ffffff;
        font-size: 1.2rem;
        margin: 0 0 15px 0;
        text-transform: uppercase;
        font-weight: 600;
    }

    .screenshots-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-top: 15px;
    }

    .screenshot-item {
        position: relative;
        overflow: hidden;
        border-radius: 3px;
        cursor: pointer;
        transition: all 0.2s ease;
        aspect-ratio: 16/9;
    }

    .screenshot-item:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 16px rgba(103, 193, 245, 0.4);
    }

    .screenshot-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* About Section */
    .about-section {
        background: rgba(0, 0, 0, 0.2);
        padding: 20px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .about-section h2 {
        color: #ffffff;
        font-size: 1.2rem;
        margin-bottom: 15px;
        text-transform: uppercase;
        font-weight: 600;
    }

    .about-section p {
        color: #c7d5e0;
        line-height: 1.8;
        font-size: 0.95rem;
    }

    /* System Requirements */
    .requirements-section {
        background: rgba(0, 0, 0, 0.2);
        padding: 20px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .requirements-section h3 {
        color: #ffffff;
        font-size: 1.2rem;
        margin-bottom: 15px;
        text-transform: uppercase;
        font-weight: 600;
    }

    .requirements-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .req-column h4 {
        color: #67c1f5;
        font-size: 1.1rem;
        margin-bottom: 10px;
    }

    .req-column p {
        color: #c7d5e0;
        white-space: pre-wrap;
        line-height: 1.6;
    }

    /* Sidebar Styles */
    .sidebar-section {
        margin-bottom: 20px;
    }

    .sidebar-section h4 {
        color: #ffffff;
        font-size: 1.1rem;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #2a475e;
    }

    .sidebar-section p {
        color: #c7d5e0;
        font-size: 0.9rem;
        margin: 5px 0;
    }

    .platform-icons {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }

    .platform-icons span {
        background: rgba(103, 193, 245, 0.1);
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 0.85rem;
        color: #67c1f5;
    }

    @media (max-width: 1200px) {
        .game-content {
            grid-template-columns: 1fr;
            padding: 20px 30px;
        }
        
        .main-content {
            padding-right: 0;
        }
        
        .requirements-grid {
            grid-template-columns: 1fr;
        }
        
        .screenshots-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .game-hero {
            height: 400px;
        }
        
        .hero-overlay {
            padding: 20px 30px;
        }
        
        .game-title-hero {
            font-size: 2rem;
        }
        
        .btn-add-cart {
            padding: 12px 30px;
            font-size: 1rem;
        }
        
        .game-content {
            padding: 15px 20px;
        }
        
        .screenshots-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Hero Section -->
<div class="game-hero">
    <div class="hero-overlay">
        <h1 class="game-title-hero">{{ $game->title }}</h1>
        
        <div class="game-meta">
            @if(!empty($game->genre))
                <span>{{ ucfirst($game->genre) }}</span>
            @endif
            @if(!empty($game->tags))
                @foreach(explode(',', $game->tags) as $tag)
                    <span>{{ trim($tag) }}</span>
                @endforeach
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
                    <span class="current-price">₹{{ number_format($game->price, 0) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="game-content">
        <!-- Left Column -->
        <div class="main-content">
            <!-- Screenshots -->
            @php
                $bannerImages = isset($banners) && count($banners) ? $banners->map(function($b){ return $b->image_path ? Storage::disk('public')->url($b->image_path) : asset('images/placeholder.jpg'); }) : collect();
            @endphp
            
            @if($bannerImages->count() > 0)
            <div class="screenshots-section">
                <h3>Screenshots</h3>
                <div class="screenshots-grid">
                    @foreach($bannerImages as $idx => $img)
                        <div class="screenshot-item">
                            <img src="{{ $img }}" alt="Screenshot {{ $idx+1 }}">
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- About -->
            <div class="about-section">
                <h2>About This Game</h2>
                <p>{{ $game->description }}</p>
            </div>

            <!-- System Requirements -->
            <div class="requirements-section">
                <h3>System Requirements</h3>
                <div class="requirements-grid">
                    <div class="req-column">
                        <h4>Minimum:</h4>
                        <p>{{ $game->min_requirements ?? 'Not specified' }}</p>
                    </div>
                    <div class="req-column">
                        <h4>Recommended:</h4>
                        <p>{{ $game->rec_requirements ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="sidebar-content">
            <div class="sidebar-section">
                <h4>Developer</h4>
                <p>{{ $game->developer ?? 'Unknown' }}</p>
            </div>

            <div class="sidebar-section">
                <h4>Release Date</h4>
                <p>{{ $game->release_date ? $game->release_date->format('M d, Y') : 'TBA' }}</p>
            </div>

            <div class="sidebar-section">
                <h4>Platform</h4>
                <div class="platform-icons">
                    <span><i class="fab fa-windows"></i> Windows</span>
                </div>
            </div>

            <div class="sidebar-section">
                <h4>Features</h4>
                <div class="platform-icons" style="flex-direction: column; align-items: flex-start;">
                    <span><i class="fas fa-gamepad"></i> Controller Support</span>
                    <span><i class="fas fa-user"></i> Single Player</span>
                </div>
            </div>
        </div>
    </div>

@endsection
