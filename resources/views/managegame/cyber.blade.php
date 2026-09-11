@extends('front.gamefront')

@section('title', 'Cyberpunk 2077 | Night City Adventure')

@section('Content')
<style>
    :root {
        --primary: #ff2a6d;
        --secondary: #05d9e8;
        --accent: #00ff9d;
        --dark-bg: #0d0c1d;
        --card-bg: #161b33;
        --text-main: #d1f7ff;
        --text-secondary: #a7b8c6;
    }

    body {
        background-color: var(--dark-bg);
        color: var(--text-main);
        font-family: 'Segoe UI', sans-serif;
        line-height: 1.6;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Game Header */
    .game-header {
        display: flex;
        gap: 30px;
        margin-bottom: 40px;
        flex-wrap: wrap;
    }

    .game-poster {
        width: 100%;
        max-width: 350px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .game-info {
        flex: 1;
        min-width: 300px;
    }

    .game-title {
        color: var(--primary);
        font-size: 2.5rem;
        margin-bottom: 15px;
    }

    .publisher {
        color: var(--secondary);
        font-size: 1.1rem;
        margin-bottom: 5px;
    }

    .release-date {
        color: var(--text-secondary);
        margin-bottom: 20px;
    }

    .tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 25px;
    }

    .tags span {
        background-color: var(--card-bg);
        padding: 5px 12px;
        border-radius: 20px;
        color: var(--secondary);
        font-size: 0.9rem;
    }

    /* Price Section */
    

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 5px;
        font-weight: bold;
        transition: background 0.3s;
    }

    .btn-primary:hover {
        background-color: #e00060;
    }

    .btn-secondary {
        background-color: transparent;
        color: var(--secondary);
        border: 1px solid var(--secondary);
        padding: 10px 20px;
        border-radius: 5px;
        transition: all 0.3s;
    }

    .btn-secondary:hover {
        background-color: rgba(5, 217, 232, 0.1);
    }

    /* Platform Icons */
    .platform-icons {
        margin-top: 25px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .platform-icon {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    /* Content Sections */
    .content-section {
        margin: 50px 0;
    }

    .section-title {
        color: var(--primary);
        font-size: 1.8rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--secondary);
    }

    /* Trailer */
    .trailer-container {
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 30px;
    }

    .trailer-container iframe {
        width: 100%;
        height: 500px;
        border: none;
    }

    /* Screenshots */
    .gallery-strip {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding: 10px 0;
    }

    .gallery-strip img {
        height: 120px;
        border-radius: 5px;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .gallery-strip img:hover {
        transform: scale(1.05);
    }

    /* About Game */
    .description {
        background-color: var(--card-bg);
        padding: 20px;
        border-radius: 8px;
        margin: 30px 0;
    }

    /* Features */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .feature-card {
        background-color: var(--card-bg);
        padding: 20px;
        border-radius: 8px;
        border-top: 3px solid var(--secondary);
    }

    .feature-title {
        color: var(--secondary);
        margin-bottom: 10px;
    }

    /* System Requirements */
    .specs-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .specs-card {
        background-color: var(--card-bg);
        padding: 20px;
        border-radius: 8px;
    }

    .specs-title {
        color: var(--accent);
        margin-bottom: 15px;
        text-align: center;
    }

    .specs-list {
        list-style: none;
        padding: 0;
    }

    .specs-list li {
        margin-bottom: 10px;
    }

    .spec-name {
        font-weight: bold;
        color: var(--text-main);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .game-header {
            flex-direction: column;
        }
        
        .trailer-container iframe {
            height: 300px;
        }
        
        .game-title {
            font-size: 2rem;
        }
    }

    @media (max-width: 480px) {
        .action-buttons {
            flex-direction: column;
        }
        
        .btn-primary, .btn-secondary {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="container">
    <!-- Game Info -->
    <div class="game-header">
        <img src="{{ asset('asset/imagies/cyber2.jpg') }}" alt="Cyberpunk 2077" class="game-poster">
        <div class="game-info">
            <h1 class="game-title">CYBERPUNK 2077</h1>
            <p class="publisher">CD PROJEKT RED</p>
            <p class="release-date">PURCHASED  : 28/07/2025</p>
            
            <div class="tags">
                <span>OPEN WORLD</span>
                <span>RPG</span>
                <span>FUTURISTIC</span>
                <span>STORY RICH</span>
            </div>
            
            
            <div class="action-buttons">
                <button class="btn-primary">
                    PLAY NOW
                </button>
             
            </div>
            
            <div class="platform-icons">
                <div class="platform-icon">
                    <i class="fas fa-windows"></i>
                    <span>Windows</span>
                </div>
                <div class="platform-icon">
                    <i class="fas fa-gamepad"></i>
                    <span>Optiomise For Controller</span>
                </div>
                <div class="platform-icon">
                    <i class="fas fa-user"></i>
                    <span>Single Player</span>
                </div>
            </div>
        </div>
    </div>

    
    <!-- About Game -->
    <div class="content-section">
        <h2 class="section-title">ABOUT THE GAME</h2>
        
        <div class="description">
            <p>
                Cyberpunk 2077 is an open-world, action-adventure RPG set in Night City, 
                a dangerous megalopolis obsessed with power, glamour, and body modification. 
                Play as V, a mercenary outlaw going after a one-of-a-kind implant that is 
                the key to immortality.
            </p>
            <p>
                Customize your character's cyberware, skillset and playstyle, and explore 
                a vast city where your choices shape the story and world around you.
            </p>
        </div>
    </div>

   

@endsection

@section('js')
<script>
    // Simple screenshot viewer
    document.querySelectorAll('.gallery-strip img').forEach(img => {
        img.addEventListener('click', function() {
            // You could implement a lightbox here if needed
            console.log('Showing full size image: ' + this.src);
        });
    });
</script>
@endsection