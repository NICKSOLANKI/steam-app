@extends('front.gamefront')

@section('title', 'Library')

@section('Content')
<style>
    body {
        background-color: #171a21;
        color: #c7d5e0;
        font-family: Arial, Helvetica, sans-serif;
    }

    .library-container {
        display: flex;
        max-width: 1700px;
        margin: 0 auto;
        height: calc(100vh - 40px);
        padding: 20px;
        gap: 20px;
    }

    /* Sidebar */
    .sidebar {
        width: 280px;
        background-color: #1b2838;
        border-radius: 4px;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .sidebar h3 {
        font-size: 16px;
        margin-bottom: 10px;
        color: #ffffff;
    }

    .search-box {
        background-color: #0e141b;
        padding: 6px 10px;
        border-radius: 4px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        border: 1px solid #2f3e50;
    }

    .search-box input {
        width: 100%;
        background: transparent;
        border: none;
        outline: none;
        color: #c7d5e0;
        font-size: 13px;
    }

    .game-list {
        list-style: none;
        padding: 0;
        margin: 0;
        flex-grow: 1;
    }

    .game-list li {
        padding: 6px 8px;
        font-size: 13px;
        color: #c7d5e0;
        cursor: pointer;
        border-radius: 3px;
    }

    .game-list li:hover,
    .game-list li.active {
        background-color: #2a475e;
        color: #66c0f4;
    }

    /* Main Content */
    .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .library-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 5px 0 15px;
        border-bottom: 1px solid #2f3e50;
        margin-bottom: 20px;
    }

    .library-header h2 {
        font-size: 15px;
        margin: 0;
        color: #c7d5e0;
        text-transform: uppercase;
        font-weight: bold;
    }

    .library-header select {
        background-color: #1b2838;
        color: #c7d5e0;
        border: 1px solid #2f3e50;
        padding: 4px 8px;
        font-size: 13px;
        border-radius: 3px;
    }

    /* Game Grid */
    .games-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    /* Game Card */
    .game-card {
        background-color: #1b2838;
        border-radius: 3px;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
        cursor: pointer;
        display: flex;
        flex-direction: column;
    }

    .game-card:hover {
        transform: scale(1.02);
        box-shadow: 0 6px 16px rgba(0,0,0,0.6);
    }

    .game-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        background-color: #0e141b;
        border-radius: 2px 2px 0 0;
        display: block;
    }

    .game-info {
        padding: 10px;
        background-color: #1b2838;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .game-title {
        font-size: 14px;
        font-weight: bold;
        color: #ffffff;
        margin-bottom: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .game-price {
        font-size: 12px;
        color: #66c0f4;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .game-meta {
        font-size: 11px;
        color: #8f98a0;
        margin-bottom: 10px;
    }

    /* Download Button */
    .btn-download {
        display: block;
        width: 100%;
        padding: 10px 12px;
        background: linear-gradient(to bottom, #5c7e10 5%, #3e590a 95%);
        border: 1px solid #5c7e10;
        border-radius: 2px;
        color: #ffffff;
        font-size: 12px;
        font-weight: bold;
        text-align: center;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.2s;
        text-decoration: none;
        margin-top: auto;
    }

    .btn-download:hover {
        background: linear-gradient(to bottom, #6d9313 5%, #4a6a0c 95%);
        box-shadow: 0 0 10px rgba(163, 207, 6, 0.3);
        border-color: #6d9313;
    }

    .btn-download.downloading {
        background: linear-gradient(to bottom, #417a9b 5%, #2e5c78 95%);
        border-color: #417a9b;
        position: relative;
        overflow: hidden;
    }

    .btn-download.downloading::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: slide 1.5s infinite;
    }

    @keyframes slide {
        100% { left: 100%; }
    }

    .btn-download.installed {
        background: linear-gradient(to bottom, #2a475e, #1b2838);
        border-color: #3d4450;
        cursor: default;
    }

    .btn-installed-label {
        font-size: 11px;
        color: #a3cf06;
        margin-bottom: 4px;
    }

    .card-progress {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid #3d4450;
        display: none;
    }

    .card-progress.active {
        display: block;
    }

    .card-progress-bar {
        height: 6px;
        background: #0e141b;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 4px;
    }

    .card-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #417a9b, #66c0f4);
        border-radius: 3px;
        transition: width 0.5s;
    }

    .card-progress-info {
        display: flex;
        justify-content: space-between;
        font-size: 10px;
        color: #8f98a0;
    }

    .card-progress-info .speed {
        color: #a3cf06;
    }

    /* Badge */
    .game-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background-color: #ff5a5f;
        color: #fff;
        font-size: 11px;
        font-weight: bold;
        padding: 2px 6px;
        border-radius: 3px;
        text-transform: uppercase;
    }

    /* Empty State */
    .no-games {
        text-align: center;
        margin-top: 50px;
        font-size: 16px;
        color: #888;
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #1b2838;
    }
    ::-webkit-scrollbar-thumb {
        background: #2f3e50;
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #3e5a70;
    }
</style>

<div class="library-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Games</h3>
        <div class="search-box">
            <input type="text" placeholder="Search">
        </div>
        <ul class="game-list">
            <li class="active">-- ALL</li>
            @foreach($libraryItems as $item)
                <li>{{ $item->game_title }}</li>
            @endforeach
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="library-header">
            <h2>All</h2>
            <select>
                <option>Alphabetical</option>
            </select>
        </div>

        @if($libraryItems->isEmpty())
            <div class="no-games">
                <p>Your library is empty. Buy some games from the store!</p>
            </div>
        @else
            <div class="games-grid">
                @foreach($libraryItems as $item)
                    <div class="game-card" data-library-id="{{ $item->id }}">
                        <div class="game-badge">Owned</div>
                        <img src="{{ $item->game_image }}" alt="{{ $item->game_title }}"
                             onerror="this.src='https://via.placeholder.com/300x150/1b2838/66c0f4?text={{ urlencode($item->game_title) }}'">
                        <div class="game-info">
                            <div class="game-title" title="{{ $item->game_title }}">{{ $item->game_title }}</div>
                            <div class="game-price">Purchased: ${{ number_format($item->price, 2) }}</div>
                            <div class="game-meta">Added: {{ $item->created_at->format('M d, Y') }}</div>
                            <div class="card-progress" id="cardProgress-{{ $item->id }}">
                                <div class="card-progress-bar">
                                    <div class="card-progress-fill" id="cardFill-{{ $item->id }}" style="width: 0%"></div>
                                </div>
                                <div class="card-progress-info">
                                    <span id="cardPct-{{ $item->id }}">0%</span>
                                    <span class="speed" id="cardSpeed-{{ $item->id }}">0 MB/s</span>
                                </div>
                            </div>
                            <button class="btn-download" id="dlBtn-{{ $item->id }}" onclick="startDownload({{ $item->id }}, this)">
                                ⬇ Install
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    const libraryDownloads = {};

    function startDownload(libraryId, btn) {
        if (btn.classList.contains('downloading') || btn.classList.contains('installed')) return;

        if (btn.classList.contains('view-details')) {
            window.location.href = btn.dataset.href;
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '⏳ Preparing...';

        $.post('/downloads/store', {
            _token: '{{ csrf_token() }}',
            library_id: libraryId
        }, function(response) {
            const downloadId = response.download?.id || response.download_id;
            if (response.redirect) {
                window.location.href = response.redirect;
                return;
            }
            if (downloadId) {
                window.location.href = appUrl('/downloads/' + downloadId);
                return;
            }
            alert(response.message || 'Could not start download.');
            btn.disabled = false;
            btn.innerHTML = '⬇ Install';
        }).fail(function() {
            alert('Error starting download. Please try again.');
            btn.disabled = false;
            btn.innerHTML = '⬇ Install';
        });
    }

    function setupCardProgress(libraryId, downloadId, btn) {
        if (!downloadId) return;

        btn.classList.remove('installed');
        btn.classList.add('downloading');
        btn.innerHTML = '⬇ Downloading...';
        btn.disabled = false;
        btn.onclick = function() { window.location.href = appUrl('/downloads/' + downloadId); };

        document.getElementById(`cardProgress-${libraryId}`).classList.add('active');

        if (libraryDownloads[libraryId]) clearInterval(libraryDownloads[libraryId]);

        libraryDownloads[libraryId] = setInterval(() => {
            $.post('/downloads/' + downloadId + '/progress', {
                _token: '{{ csrf_token() }}'
            }, function(response) {
                const d = response.download;
                document.getElementById(`cardFill-${libraryId}`).style.width = d.progress + '%';
                document.getElementById(`cardPct-${libraryId}`).textContent = parseFloat(d.progress).toFixed(1) + '%';
                document.getElementById(`cardSpeed-${libraryId}`).textContent = parseFloat(d.current_speed).toFixed(1) + ' MB/s';

                if (d.status === 'completed') {
                    clearInterval(libraryDownloads[libraryId]);
                    delete libraryDownloads[libraryId];
                    btn.classList.remove('downloading');
                    btn.classList.add('installed', 'view-details');
                    btn.innerHTML = '▶ Play Now';
                    btn.dataset.href = appUrl('/downloads/' + downloadId);
                    document.getElementById(`cardPct-${libraryId}`).textContent = '✓ Ready';
                    document.getElementById(`cardSpeed-${libraryId}`).textContent = 'Installed';
                }
            });
        }, 2000);
    }

    $(document).ready(function() {
        $.get('/downloads/api/recent', function(response) {
            const downloads = response.downloads || [];
            downloads.forEach(d => {
                if (['downloading', 'paused', 'queued'].includes(d.status)) {
                    const card = document.querySelector(`.game-card[data-library-id="${d.library_id}"]`);
                    if (card) {
                        const btn = card.querySelector('.btn-download');
                        setupCardProgress(d.library_id, d.id, btn);
                    }
                } else if (d.status === 'completed') {
                    const card = document.querySelector(`.game-card[data-library-id="${d.library_id}"]`);
                    if (card) {
                        const btn = card.querySelector('.btn-download');
                        btn.classList.add('installed', 'view-details');
                        btn.innerHTML = '▶ Play Now';
                        btn.dataset.href = appUrl('/downloads/' + d.id);
                        btn.onclick = function() { window.location.href = this.dataset.href; };
                        document.getElementById(`cardProgress-${d.library_id}`)?.classList.add('active');
                        document.getElementById(`cardPct-${d.library_id}`) && (document.getElementById(`cardPct-${d.library_id}`).textContent = '✓ Installed');
                        document.getElementById(`cardSpeed-${d.library_id}`) && (document.getElementById(`cardSpeed-${d.library_id}`).textContent = '100%');
                        document.getElementById(`cardFill-${d.library_id}`) && (document.getElementById(`cardFill-${d.library_id}`).style.width = '100%');
                    }
                }
            });
        });
    });
</script>
<script>
    // Search functionality
    document.querySelector('.search-box input').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const gameCards = document.querySelectorAll('.game-card');
        const gameListItems = document.querySelectorAll('.game-list li:not(:first-child)');
        
        gameCards.forEach(card => {
            const title = card.querySelector('.game-title').textContent.toLowerCase();
            card.style.display = title.includes(searchTerm) ? 'flex' : 'none';
        });

        gameListItems.forEach(item => {
            const title = item.textContent.toLowerCase();
            item.style.display = title.includes(searchTerm) ? 'block' : 'none';
        });
    });

    // Sidebar game filter
    document.querySelectorAll('.game-list li').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.game-list li').forEach(li => li.classList.remove('active'));
            this.classList.add('active');
            
            const gameName = this.textContent.trim();
            const gameCards = document.querySelectorAll('.game-card');
            
            if (gameName === '-- ALL') {
                gameCards.forEach(card => card.style.display = 'flex');
            } else {
                gameCards.forEach(card => {
                    const title = card.querySelector('.game-title').textContent.trim();
                    card.style.display = title === gameName ? 'flex' : 'none';
                });
            }
        });
    });
</script>
@endsection
