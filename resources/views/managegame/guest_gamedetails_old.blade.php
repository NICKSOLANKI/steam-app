@extends('front.gamefront')

@section('title', $game->title)

@section('Content')
<style>
    body {
        background: linear-gradient(to bottom, #1b2838 0%, #0e141b 100%);
        color: #c7d5e0;
        margin: 0;
        padding: 0;
    }

    .game-hero {
        position: relative;
        width: 100%;
        height: 500px;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3), #1b2838), url('{{ $game->imageUrl }}');
        background-size: cover;
        background-position: center;
        margin-bottom: 20px;
    }

    .hero-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 40px;
        background: linear-gradient(to top, rgba(27, 40, 56, 0.95), transparent);
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

    .container-steam {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .game-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-top: 20px;
    }
        }

        .gallery-strip {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 10px 0;
            justify-content: center;
        }

        .gallery-strip img {
            height: 120px;
            border-radius: 4px;
            border: 2px solid #2a475e;
            cursor: pointer;
            transition: transform 0.2s, border-color 0.2s;
        }

        .gallery-strip img:hover {
            transform: scale(1.03);
            border-color: #66c0f4;
        }

        .gallery-strip img.active {
            border-color: yellow;
        }

        .requirements .specs {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .requirements .column {
            flex: 1;
            min-width: 300px;
        }

        .requirements ul {
            list-style: none;
            padding-left: 0;
        }

        .requirements li {
            margin-bottom: 10px;
            color: #d6d6d6;
        }

        .platform-icons {
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .platform-icons span {
            margin-right: 15px;
            color: #c7d5e0;
        }
    </style>

    <div class="container mt-5">

        <!-- Game Info -->
        <div class="game-header">
            <img src="{{ $game->imageUrl }}" alt="{{ $game->title }}" class="game-poster">
            <div class="game-info">
                <h1>{{ $game->title }}</h1>
                <p><strong>Publisher:</strong> {{ $game->developer ?? '—' }}</p>
                @if($game->release_date)
                <p><strong>Release Date:</strong> {{ $game->release_date->format('F d, Y') }}</p>
                @endif
                <div class="tags">
                    @if(!empty($game->genre))
                        <span>{{ ucfirst($game->genre) }}</span>
                    @endif
                    @if(!empty($game->tags))
                        @foreach(explode(',', $game->tags) as $tag)
                            <span>{{ trim($tag) }}</span>
                        @endforeach
                    @endif
                </div>
                <div class="price-section">
                    @if($game->original_price && $game->original_price > $game->price)
                        <div class="discount-badge">-{{ round((($game->original_price - $game->price) / $game->original_price) * 100) }}%</div>
                        <div class="original-price">₹{{ number_format($game->original_price, 0) }}</div>
                    @endif
                    <div class="discounted-price">₹{{ number_format($game->price, 0) }}</div>
                </div>

                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="game_id" value="{{ $game->id }}">
                    <input type="hidden" name="game_title" value="{{ $game->title }}">
                    <input type="hidden" name="game_image" value="{{ $game->imageUrl }}">
                    <input type="hidden" name="price" value="{{ $game->price }}">
                    <button type="submit" class="btn-add">Add to Cart</button>
                </form>

                <div class="platform-icons mt-3">
                    <span>🖥️ Windows</span>
                    <span>🎮 Controller Supported</span>
                    <span>👤 Single Player</span>
                </div>
            </div>
        </div>

        <!-- Trailer -->
        @if(!empty($game->trailer_url))
        <div class="gallery">
            <h3>Trailer</h3>
            <div class="d-flex justify-content-center mb-4">
                <div style="width: 100%; max-width: 1200px;">
                    <video
                        width="100%"
                        height="650"
                        controls
                        style="border-radius: 12px; border: 3px solid #2a475e; box-shadow: 0 0 30px rgba(0,0,0,0.5);">
                        <source src="{{ $game->trailer_url }}" type="video/mp4">
                    </video>
                </div>
            </div>
        </div>
        @endif

        <!-- Gallery (from game banners if available) -->
        <div class="gallery">
            <h3>Game Screenshots</h3>
            @php
                $bannerImages = isset($banners) && count($banners) ? $banners->map(function($b){ return $b->image_path ? Storage::disk('public')->url($b->image_path) : asset('images/placeholder.jpg'); }) : collect();
            @endphp
            <div class="gallery-strip">
                @if($bannerImages->count())
                    @foreach($bannerImages as $idx => $img)
                        <img src="{{ $img }}" alt="Screenshot {{ $idx+1 }}" onclick="switchScreenshot(this)" class="{{ $idx === 0 ? 'active' : '' }}">
                    @endforeach
                @else
                    <img src="{{ $game->imageUrl }}" alt="{{ $game->title }}" class="active">
                @endif
            </div>
        </div>

        <!-- About Game -->
        <div class="description">
            <h2>About This Game</h2>
            <p>{{ $game->description }}</p>
        </div>

        <!-- System Requirements -->
        <div class="requirements mt-4">
            <h3>System Requirements</h3>
            <div class="specs">
                <div class="column">
                    <h5 style="color:#a4d007;">Minimum:</h5>
                    <div style="white-space:pre-wrap;">{{ $game->min_requirements ?? '—' }}</div>
                </div>
                <div class="column">
                    <h5 style="color:#66c0f4;">Recommended:</h5>
                    <div style="white-space:pre-wrap;">{{ $game->rec_requirements ?? '—' }}</div>
                </div>
            </div>
        </div>


        <!-- Review Display -->
        

    @endsection

    @section('js')
    <script>
        function switchScreenshot(el) {
            document.querySelectorAll('.gallery-strip img').forEach(img => img.classList.remove('active'));
            el.classList.add('active');
        }

        // Simple Review System (guard if form exists)
        const reviewForm = document.getElementById('reviewForm');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const input = document.getElementById('reviewInput');
                const text = input ? input.value.trim() : '';
                if (text !== '') {
                    const li = document.createElement('li');
                    li.style.marginBottom = '15px';
                    li.style.padding = '10px';
                    li.style.backgroundColor = '#1b2838';
                    li.style.border = '1px solid #66c0f4';
                    li.style.borderRadius = '5px';
                    li.innerHTML = `<strong>NICK SOLANKI:</strong> ${text}`;
                    const list = document.getElementById('reviewList');
                    if (list) list.prepend(li);
                    if (input) input.value = '';
                }
            });
        }
    </script>
    @endsection