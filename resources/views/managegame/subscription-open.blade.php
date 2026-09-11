@extends('front.gamefront')

@section('title', 'Lifetime Premium Store')

@section('Content')
<style>
    body {
        background-color: #0e141b;
        color: #e6e6e6;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
    }

    .store-container {
        padding: 60px 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .store-header {
        text-align: center;
        margin-bottom: 50px;
        position: relative;
    }

    .store-header h1 {
        font-size: 48px;
        margin-bottom: 15px;
        background: linear-gradient(45deg, #66c0f4, #4b9cd3);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
    }

    .store-header p {
        font-size: 20px;
        color: #8f98a0;
        max-width: 800px;
        margin: 0 auto 30px;
    }

    .premium-badge {
        display: inline-block;
        background: linear-gradient(135deg, #ffd700, #c5a000);
        color: #1a1a1a;
        padding: 8px 20px;
        border-radius: 30px;
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
    }

    .section-title {
        font-size: 32px;
        color: #66c0f4;
        margin: 60px 0 30px;
        position: relative;
        padding-bottom: 15px;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #66c0f4, transparent);
    }

    .game-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 30px;
    }

    .game-card {
        background-color: #16202d;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        position: relative;
        border: 1px solid #2a3f5a;
    }

    .game-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.4);
        border-color: #66c0f4;
    }

    .game-card img {
        width: 100%;
        height: 380px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .game-card:hover img {
        transform: scale(1.03);
    }

    .game-info {
        padding: 20px;
        background-color: #1a2a3a;
    }

    .game-title {
        font-size: 18px;
        font-weight: bold;
        color: #fff;
        margin-bottom: 8px;
    }

    .game-meta {
        display: flex;
        justify-content: space-between;
        color: #8f98a0;
        font-size: 14px;
    }

    .game-price {
        color: #66c0f4;
        font-weight: bold;
    }

    .owned-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background-color: rgba(102, 192, 244, 0.9);
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        z-index: 2;
    }

    .features-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin: 60px 0;
    }

    .feature-card {
        background-color: #1a2a3a;
        padding: 30px;
        border-radius: 10px;
        border-left: 4px solid #66c0f4;
    }

    .feature-card h3 {
        color: #66c0f4;
        margin-bottom: 15px;
        font-size: 20px;
    }

    .feature-card p {
        color: #b8b8b8;
    }

    .cta-section {
        text-align: center;
        margin: 80px 0 40px;
        padding: 40px;
        background: linear-gradient(135deg, rgba(26, 42, 58, 0.8), rgba(16, 30, 44, 0.9));
        border-radius: 12px;
        border: 1px solid #2a3f5a;
    }

    .cta-section h2 {
        font-size: 32px;
        margin-bottom: 20px;
        color: #fff;
    }

    .cta-section p {
        font-size: 18px;
        color: #8f98a0;
        max-width: 700px;
        margin: 0 auto 30px;
    }

    .cta-button {
        display: inline-block;
        background: linear-gradient(135deg, #66c0f4, #4b9cd3);
        color: #0e141b;
        padding: 15px 40px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 18px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 192, 244, 0.4);
    }

    .cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 192, 244, 0.6);
    }

    @media (max-width: 768px) {
        .store-header h1 {
            font-size: 36px;
        }
        
        .game-grid {
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        }
    }
</style>

<div class="store-container">
    <div class="store-header">
        <div class="premium-badge">LIFETIME MEMBER</div>
        <h1>Premium Game Vault</h1>
        <p>Unlock permanent access to an ever-growing collection of premium AAA titles. Your lifetime membership includes all current and future additions to our exclusive catalog.</p>
    </div>

    <h2 class="section-title">Your Lifetime Collection</h2>

    <div class="game-grid">
        <div class="game-card">
            <div class="owned-badge">OWNED</div>
            <img src="{{ asset('asset/imagies/hog.webp') }}" alt="Hogwarts Legacy">
            <div class="game-info">
                <div class="game-title">Hogwarts Legacy</div>
                <div class="game-meta">
                    <span>RPG</span>
                    <span class="game-price">FREE</span>
                </div>
            </div>
        </div>
        <div class="game-card">
            <div class="owned-badge">OWNED</div>
            <img src="{{ asset('asset/imagies/ws.webp') }}" alt="Red Dead Redemption 2">
            <div class="game-info">
                <div class="game-title">Red Dead Redemption 2</div>
                <div class="game-meta">
                    <span>Open World</span>
                    <span class="game-price">FREE</span>
                </div>
            </div>
        </div>
        <div class="game-card">
            <div class="owned-badge">OWNED</div>
            <img src="{{ asset('asset/imagies/mp.webp') }}" alt="Elden Ring">
            <div class="game-info">
                <div class="game-title">Elden Ring</div>
                <div class="game-meta">
                    <span>Action RPG</span>
                    <span class="game-price">FREE</span>
                </div>
            </div>
        </div>
        <div class="game-card">
            <div class="owned-badge">OWNED</div>
            <img src="{{ asset('asset/imagies/alan.webp') }}" alt="Alan Wake 2">
            <div class="game-info">
                <div class="game-title">Alan Wake II</div>
                <div class="game-meta">
                    <span>Survival Horror</span>
                    <span class="game-price">FREE</span>
                </div>
            </div>
        </div>
        <div class="game-card">
            <div class="owned-badge">OWNED</div>
            <img src="{{ asset('asset/imagies/sm.webp') }}" alt="Starfield">
            <div class="game-info">
                <div class="game-title">Starfield</div>
                <div class="game-meta">
                    <span>Sci-Fi RPG</span>
                    <span class="game-price">FREE</span>
                </div>
            </div>
        </div>
        <div class="game-card">
            <div class="owned-badge">OWNED</div>
            <img src="{{ asset('asset/imagies/cm.webp') }}" alt="Marvel Avengers">
            <div class="game-info">
                <div class="game-title">Marvel's Avengers</div>
                <div class="game-meta">
                    <span>Action-Adventure</span>
                    <span class="game-price">FREE</span>
                </div>
            </div>
        </div>
        <div class="game-card">
            <div class="owned-badge">OWNED</div>
            <img src="{{ asset('asset/imagies/sd.webp') }}" alt="Death Stranding">
            <div class="game-info">
                <div class="game-title">Death Stranding</div>
                <div class="game-meta">
                    <span>Action</span>
                    <span class="game-price">FREE</span>
                </div>
            </div>
        </div>
        <div class="game-card">
            <div class="owned-badge">OWNED</div>
            <img src="{{ asset('asset/imagies/sa.webp') }}" alt="Tekken 8">
            <div class="game-info">
                <div class="game-title">Tekken 8</div>
                <div class="game-meta">
                    <span>Fighting</span>
                    <span class="game-price">FREE</span>
                </div>
            </div>
        </div>
    </div>
   

   
@endsection