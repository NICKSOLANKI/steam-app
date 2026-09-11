@extends('front.SS')

@section('content')
<style>
    body {
        background: #1a1a1a;
    }
    
    .library-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px;
    }
    
    .library-header {
        background: linear-gradient(135deg, #1b2838 0%, #2a475e 100%);
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .back-btn {
        background: #2a475e;
        color: #66c0f4;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .back-btn:hover {
        background: #3d5a73;
        color: #ffffff;
        transform: translateY(-2px);
    }
    
    .library-header h2 {
        color: #ffffff;
        font-size: 32px;
        font-weight: 700;
        margin: 0;
    }
    
    .library-stats {
        color: #c7d5e0;
        font-size: 16px;
        margin-top: 10px;
    }
    
    .library-stats span {
        color: #66c0f4;
        font-weight: 600;
    }
    
    .games-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
    }
    
    .game-card {
        background: #1b2838;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s;
        border: 2px solid transparent;
    }
    
    .game-card:hover {
        transform: translateY(-5px);
        border-color: #66c0f4;
        box-shadow: 0 8px 24px rgba(102, 192, 244, 0.3);
    }
    
    .game-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .game-info {
        padding: 20px;
    }
    
    .game-title {
        color: #ffffff;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    .game-price {
        color: #66c0f4;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .game-date {
        color: #8f98a0;
        font-size: 14px;
    }
    
    .no-games {
        text-align: center;
        padding: 60px;
        background: #1b2838;
        border-radius: 8px;
        color: #8f98a0;
        font-size: 18px;
    }
    
    .user-badge {
        background: rgba(102, 192, 244, 0.2);
        color: #66c0f4;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
    }
</style>

<div class="library-container">
    <div class="library-header">
        <a href="{{ route('admin.library') }}" class="back-btn">← Back</a>
        <div>
            <h2>📚 {{ $user->name }}'s Library</h2>
            <div class="library-stats">
                <span class="user-badge">{{ $user->email }}</span>
                <span style="margin-left: 20px;">Total Games: <span>{{ $user->libraries->count() }}</span></span>
                <span style="margin-left: 20px;">Total Spent: <span>${{ number_format($user->libraries->sum('price'), 2) }}</span></span>
            </div>
        </div>
    </div>

    @if($user->libraries->isEmpty())
        <div class="no-games">
            <p>This user hasn't purchased any games yet.</p>
        </div>
    @else
        <div class="games-grid">
            @foreach($user->libraries as $library)
                <div class="game-card">
                    <img src="{{ $library->game_image }}" alt="{{ $library->game_title }}">
                    <div class="game-info">
                        <div class="game-title">{{ $library->game_title }}</div>
                        <div class="game-price">Price: ${{ number_format($library->price, 2) }}</div>
                        <div class="game-date">Purchased: {{ $library->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
