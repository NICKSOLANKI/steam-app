@extends('front.gamefront')

@section('title', 'Downloads')

@section('Content')
<style>
    body {
        background-color: #1b2838;
        color: #c7d5e0;
        font-family: "Motiva Sans", Arial, Helvetica, sans-serif;
    }

    .downloads-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #3d4450;
    }

    .page-title {
        font-size: 28px;
        color: #ffffff;
        font-weight: 300;
        letter-spacing: 1px;
    }

    .downloads-stats {
        display: flex;
        gap: 20px;
    }

    .stat-item {
        background: linear-gradient(to right, #1b2838, #2a475e);
        padding: 10px 20px;
        border-radius: 3px;
        border: 1px solid #3d4450;
    }

    .stat-label {
        font-size: 11px;
        color: #8f98a0;
        text-transform: uppercase;
    }

    .stat-value {
        font-size: 18px;
        color: #66c0f4;
        font-weight: bold;
    }

    .downloads-tabs {
        display: flex;
        gap: 5px;
        margin-bottom: 25px;
        border-bottom: 1px solid #3d4450;
    }

    .tab-btn {
        padding: 12px 24px;
        background: none;
        border: none;
        color: #8f98a0;
        cursor: pointer;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
    }

    .tab-btn:hover {
        color: #ffffff;
    }

    .tab-btn.active {
        color: #66c0f4;
        border-bottom-color: #66c0f4;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .download-card {
        background: linear-gradient(to bottom, #163a50, #1b2838);
        border: 1px solid #3d4450;
        border-radius: 4px;
        padding: 20px;
        margin-bottom: 15px;
        display: flex;
        gap: 20px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .download-card:hover {
        border-color: #66c0f4;
        box-shadow: 0 0 20px rgba(102, 192, 244, 0.2);
    }

    .download-card.downloading::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, transparent, #66c0f4, transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        100% { left: 100%; }
    }

    .download-image {
        width: 220px;
        height: 90px;
        flex-shrink: 0;
        border-radius: 3px;
        overflow: hidden;
        background-color: #0e141b;
    }

    .download-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .download-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
    }

    .download-title {
        font-size: 18px;
        color: #ffffff;
        margin-bottom: 10px;
        font-weight: 500;
    }

    .progress-wrapper {
        margin-bottom: 10px;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
        font-size: 12px;
    }

    .progress-label {
        color: #8f98a0;
    }

    .progress-percent {
        color: #66c0f4;
        font-weight: bold;
    }

    .progress-bar-outer {
        height: 10px;
        background-color: #0e141b;
        border-radius: 5px;
        overflow: hidden;
        position: relative;
    }

    .progress-bar-inner {
        height: 100%;
        background: linear-gradient(90deg, #417a9b, #66c0f4);
        border-radius: 5px;
        position: relative;
        transition: width 0.5s ease;
    }

    .progress-bar-inner::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: progressShine 1.5s infinite;
    }

    @keyframes progressShine {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .download-details {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        font-size: 12px;
        color: #8f98a0;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .detail-icon {
        color: #66c0f4;
    }

    .detail-value {
        color: #c7d5e0;
    }

    .speed-value {
        color: #a3cf06;
        font-weight: bold;
    }

    .download-actions {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .action-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
        transition: all 0.2s;
        min-width: 100px;
    }

    .btn-pause {
        background: linear-gradient(to bottom, #c9a227, #a0801f);
        color: #ffffff;
    }

    .btn-pause:hover {
        background: linear-gradient(to bottom, #dab42d, #b58d22);
    }

    .btn-resume {
        background: linear-gradient(to bottom, #5c7e10, #3e590a);
        color: #ffffff;
    }

    .btn-resume:hover {
        background: linear-gradient(to bottom, #6d9313, #4a6a0c);
    }

    .btn-cancel {
        background: linear-gradient(to bottom, #7a2e2e, #542020);
        color: #ffffff;
    }

    .btn-cancel:hover {
        background: linear-gradient(to bottom, #933636, #632626);
    }

    .btn-view {
        background: linear-gradient(to bottom, #417a9b, #2e5c78);
        color: #ffffff;
    }

    .btn-view:hover {
        background: linear-gradient(to bottom, #4e93b8, #366d8e);
    }

    .btn-retry {
        background: linear-gradient(to bottom, #755c1e, #544316);
        color: #ffffff;
    }

    .btn-retry:hover {
        background: linear-gradient(to bottom, #8a6d22, #655119);
    }

    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 11px;
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .status-downloading {
        background-color: rgba(102, 192, 244, 0.2);
        color: #66c0f4;
        border: 1px solid #66c0f4;
    }

    .status-paused {
        background-color: rgba(201, 162, 39, 0.2);
        color: #c9a227;
        border: 1px solid #c9a227;
    }

    .status-completed {
        background-color: rgba(163, 207, 6, 0.2);
        color: #a3cf06;
        border: 1px solid #a3cf06;
    }

    .status-queued {
        background-color: rgba(143, 152, 160, 0.2);
        color: #8f98a0;
        border: 1px solid #8f98a0;
    }

    .status-cancelled {
        background-color: rgba(122, 46, 46, 0.2);
        color: #e55050;
        border: 1px solid #7a2e2e;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #8f98a0;
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 24px;
        color: #ffffff;
        margin-bottom: 10px;
    }

    .empty-state p {
        font-size: 14px;
    }

    .time-estimate {
        color: #66c0f4;
    }
</style>

<div class="downloads-container">
    <div class="page-header">
        <h1 class="page-title">DOWNLOADS</h1>
        <div class="downloads-stats">
            <div class="stat-item">
                <div class="stat-label">Active</div>
                <div class="stat-value" id="statActive">{{ $activeCount }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Total</div>
                <div class="stat-value" id="statTotal">{{ $downloads->count() }}</div>
            </div>
        </div>
    </div>

    <div class="downloads-tabs">
        <button class="tab-btn active" data-tab="active">Active Downloads</button>
        <button class="tab-btn" data-tab="completed">Completed</button>
        <button class="tab-btn" data-tab="all">All Downloads</button>
    </div>

    <div class="tab-content active" id="tab-active">
        @php
            $activeDownloads = $downloads->whereIn('status', ['queued', 'downloading', 'paused']);
        @endphp
        @if($activeDownloads->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">📥</div>
                <h3>No Active Downloads</h3>
                <p>Your active downloads will appear here. Visit your Library to start downloading games.</p>
            </div>
        @else
            @foreach($activeDownloads as $download)
                <div class="download-card downloading" data-download-id="{{ $download->id }}">
                    <div class="download-image">
                        <img src="{{ $download->game_image }}" alt="{{ $download->game_title }}" onerror="this.src='https://via.placeholder.com/220x90/1b2838/66c0f4?text={{ urlencode($download->game_title) }}'">
                    </div>
                    <div class="download-info">
                        <div class="download-title">
                            {{ $download->game_title }}
                            <span class="status-badge status-{{ $download->status }}">{{ $download->status }}</span>
                        </div>
                        <div class="progress-wrapper">
                            <div class="progress-header">
                                <span class="progress-label">Downloading...</span>
                                <span class="progress-percent">{{ $download->progress }}%</span>
                            </div>
                            <div class="progress-bar-outer">
                                <div class="progress-bar-inner" style="width: {{ $download->progress }}%"></div>
                            </div>
                        </div>
                        <div class="download-details">
                            <div class="detail-item">
                                <span class="detail-icon">📦</span>
                                <span class="detail-value downloaded-size">{{ number_format($download->downloaded_size, 2) }} MB</span>
                                <span> / </span>
                                <span class="detail-value total-size">{{ number_format($download->total_size, 2) }} MB</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-icon">⚡</span>
                                <span class="speed-value current-speed">{{ $download->current_speed }} MB/s</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-icon">🚀</span>
                                <span>Peak: </span>
                                <span class="detail-value peak-speed">{{ $download->peak_speed }} MB/s</span>
                            </div>
                            <div class="detail-item time-estimate-item" style="display:none;">
                                <span class="detail-icon">⏱️</span>
                                <span>ETA: </span>
                                <span class="time-estimate"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-icon">📂</span>
                                <span class="detail-value">{{ $download->install_path }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="download-actions">
                        @if($download->status === 'downloading')
                            <button class="action-btn btn-pause" onclick="togglePause({{ $download->id }}, 'pause')">⏸ Pause</button>
                        @elseif($download->status === 'paused' || $download->status === 'queued')
                            <button class="action-btn btn-resume" onclick="togglePause({{ $download->id }}, 'resume')">▶ Resume</button>
                        @endif
                        <button class="action-btn btn-cancel" onclick="cancelDownload({{ $download->id }})">✕ Cancel</button>
                        <a href="{{ route('downloads.show', $download->id) }}" class="action-btn btn-view" style="text-align:center; text-decoration:none;">🔍 Details</a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="tab-content" id="tab-completed">
        @php
            $completedDownloads = $downloads->where('status', 'completed');
        @endphp
        @if($completedDownloads->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">✅</div>
                <h3>No Completed Downloads</h3>
                <p>Your completed downloads will appear here.</p>
            </div>
        @else
            @foreach($completedDownloads as $download)
                <div class="download-card" data-download-id="{{ $download->id }}">
                    <div class="download-image">
                        <img src="{{ $download->game_image }}" alt="{{ $download->game_title }}" onerror="this.src='https://via.placeholder.com/220x90/1b2838/66c0f4?text={{ urlencode($download->game_title) }}'">
                    </div>
                    <div class="download-info">
                        <div class="download-title">
                            {{ $download->game_title }}
                            <span class="status-badge status-completed">✓ Installed</span>
                        </div>
                        <div class="progress-wrapper">
                            <div class="progress-header">
                                <span class="progress-label">Complete</span>
                                <span class="progress-percent">100%</span>
                            </div>
                            <div class="progress-bar-outer">
                                <div class="progress-bar-inner" style="width: 100%; background: linear-gradient(90deg, #5c7e10, #a3cf06);"></div>
                            </div>
                        </div>
                        <div class="download-details">
                            <div class="detail-item">
                                <span class="detail-icon">📦</span>
                                <span class="detail-value">{{ number_format($download->total_size, 2) }} GB</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-icon">🚀</span>
                                <span>Peak: </span>
                                <span class="detail-value">{{ $download->peak_speed }} MB/s</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-icon">📅</span>
                                <span class="detail-value">{{ $download->completed_at ? $download->completed_at->format('M d, Y H:i') : 'N/A' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-icon">📂</span>
                                <span class="detail-value">{{ $download->install_path }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="download-actions">
                        <button class="action-btn btn-resume" onclick="playGame('{{ $download->game_title }}')">▶ Play</button>
                        <a href="{{ route('downloads.show', $download->id) }}" class="action-btn btn-view" style="text-align:center; text-decoration:none;">🔍 Details</a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="tab-content" id="tab-all">
        @if($downloads->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <h3>No Downloads Found</h3>
                <p>Your download history will appear here.</p>
            </div>
        @else
            @foreach($downloads as $download)
                <div class="download-card" data-download-id="{{ $download->id }}">
                    <div class="download-image">
                        <img src="{{ $download->game_image }}" alt="{{ $download->game_title }}" onerror="this.src='https://via.placeholder.com/220x90/1b2838/66c0f4?text={{ urlencode($download->game_title) }}'">
                    </div>
                    <div class="download-info">
                        <div class="download-title">
                            {{ $download->game_title }}
                            <span class="status-badge status-{{ $download->status }}">
                                @if($download->status === 'completed') ✓ Installed
                                @elseif($download->status === 'cancelled') Cancelled
                                @else {{ ucfirst($download->status) }}
                                @endif
                            </span>
                        </div>
                        <div class="progress-wrapper">
                            <div class="progress-header">
                                <span class="progress-label">Progress</span>
                                <span class="progress-percent">{{ $download->progress }}%</span>
                            </div>
                            <div class="progress-bar-outer">
                                <div class="progress-bar-inner" style="width: {{ $download->progress }}%; {{ $download->status === 'completed' ? 'background: linear-gradient(90deg, #5c7e10, #a3cf06);' : '' }} {{ $download->status === 'cancelled' ? 'background: linear-gradient(90deg, #7a2e2e, #a14040);' : '' }}"></div>
                            </div>
                        </div>
                        <div class="download-details">
                            <div class="detail-item">
                                <span class="detail-icon">📦</span>
                                <span class="detail-value">{{ number_format($download->downloaded_size, 2) }} / {{ number_format($download->total_size, 2) }} GB</span>
                            </div>
                            @if($download->status === 'downloading')
                            <div class="detail-item">
                                <span class="detail-icon">⚡</span>
                                <span class="speed-value">{{ $download->current_speed }} MB/s</span>
                            </div>
                            @endif
                            <div class="detail-item">
                                <span class="detail-icon">📅</span>
                                <span class="detail-value">{{ $download->created_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="download-actions">
                        @if($download->status === 'downloading')
                            <button class="action-btn btn-pause" onclick="togglePause({{ $download->id }}, 'pause')">⏸ Pause</button>
                        @elseif($download->status === 'paused')
                            <button class="action-btn btn-resume" onclick="togglePause({{ $download->id }}, 'resume')">▶ Resume</button>
                        @elseif($download->status === 'cancelled')
                            <button class="action-btn btn-retry" onclick="retryDownload({{ $download->library_id }})">↻ Retry</button>
                        @elseif($download->status === 'completed')
                            <button class="action-btn btn-resume" onclick="playGame('{{ $download->game_title }}')">▶ Play</button>
                        @endif
                        <a href="{{ route('downloads.show', $download->id) }}" class="action-btn btn-view" style="text-align:center; text-decoration:none;">🔍 Details</a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const progressIntervals = {};

    function formatETA(seconds) {
        if (seconds <= 0) return 'Calculating...';
        if (seconds > 86400) return Math.floor(seconds / 86400) + 'd ' + Math.floor((seconds % 86400) / 3600) + 'h';
        if (seconds > 3600) return Math.floor(seconds / 3600) + 'h ' + Math.floor((seconds % 3600) / 60) + 'm';
        if (seconds > 60) return Math.floor(seconds / 60) + 'm ' + (seconds % 60) + 's';
        return seconds + 's';
    }

    function startProgressUpdate(downloadId) {
        if (progressIntervals[downloadId]) return;

        progressIntervals[downloadId] = setInterval(() => {
            $.post(`/downloads/${downloadId}/progress`, {
                _token: '{{ csrf_token() }}'
            }, function(response) {
                const d = response.download;
                const card = $(`.download-card[data-download-id="${downloadId}"]`);
                if (!card.length) return;

                card.find('.progress-percent').text(d.progress + '%');
                card.find('.progress-bar-inner').css('width', d.progress + '%');
                card.find('.downloaded-size').text(parseFloat(d.downloaded_size).toFixed(2) + ' GB');
                card.find('.total-size').text(parseFloat(d.total_size).toFixed(2) + ' GB');
                card.find('.current-speed').text(parseFloat(d.current_speed).toFixed(1) + ' MB/s');
                card.find('.peak-speed').text(parseFloat(d.peak_speed).toFixed(1) + ' MB/s');

                const badge = card.find('.status-badge');
                badge.removeClass().addClass('status-badge status-' + d.status);
                badge.text(d.status.charAt(0).toUpperCase() + d.status.slice(1));

                if (d.status === 'downloading' && d.current_speed > 0) {
                    const remainingGB = d.total_size - d.downloaded_size;
                    const remainingMB = remainingGB * 1024;
                    const etaSeconds = Math.round(remainingMB / d.current_speed);
                    card.find('.time-estimate-item').show();
                    card.find('.time-estimate').text(formatETA(etaSeconds));
                    card.addClass('downloading');
                } else {
                    card.removeClass('downloading');
                    if (d.status !== 'downloading') {
                        card.find('.time-estimate-item').hide();
                    }
                }

                if (d.status === 'completed') {
                    card.find('.progress-bar-inner').css('background', 'linear-gradient(90deg, #5c7e10, #a3cf06)');
                    card.removeClass('downloading');
                    badge.text('✓ Installed');
                    const actions = card.find('.download-actions');
                    actions.html(`
                        <button class="action-btn btn-resume" onclick="playGame('${d.game_title}')">▶ Play</button>
                        <a href="/downloads/${d.id}" class="action-btn btn-view" style="text-align:center; text-decoration:none;">🔍 Details</a>
                    `);
                    clearInterval(progressIntervals[downloadId]);
                    delete progressIntervals[downloadId];
                }
            });
        }, 1500);
    }

    function togglePause(downloadId, action) {
        const endpoint = action === 'pause' ? `pause` : `resume`;
        $.post(`/downloads/${downloadId}/${endpoint}`, {
            _token: '{{ csrf_token() }}'
        }, function(response) {
            const d = response.download;
            const card = $(`.download-card[data-download-id="${downloadId}"]`);
            const badge = card.find('.status-badge');
            badge.removeClass().addClass('status-badge status-' + d.status);
            badge.text(d.status.charAt(0).toUpperCase() + d.status.slice(1));

            const actions = card.find('.download-actions');
            const detailsBtn = `<a href="/downloads/${d.id}" class="action-btn btn-view" style="text-align:center; text-decoration:none;">🔍 Details</a>`;

            if (d.status === 'paused') {
                actions.html(`
                    <button class="action-btn btn-resume" onclick="togglePause(${d.id}, 'resume')">▶ Resume</button>
                    <button class="action-btn btn-cancel" onclick="cancelDownload(${d.id})">✕ Cancel</button>
                    ${detailsBtn}
                `);
                card.removeClass('downloading');
                clearInterval(progressIntervals[downloadId]);
                delete progressIntervals[downloadId];
            } else if (d.status === 'downloading') {
                actions.html(`
                    <button class="action-btn btn-pause" onclick="togglePause(${d.id}, 'pause')">⏸ Pause</button>
                    <button class="action-btn btn-cancel" onclick="cancelDownload(${d.id})">✕ Cancel</button>
                    ${detailsBtn}
                `);
                startProgressUpdate(d.id);
            }
        });
    }

    function cancelDownload(downloadId) {
        if (!confirm('Are you sure you want to cancel this download?')) return;

        $.post(`/downloads/${downloadId}/cancel`, {
            _token: '{{ csrf_token() }}'
        }, function() {
            const card = $(`.download-card[data-download-id="${downloadId}"]`);
            card.fadeOut(400, function() {
                card.remove();
                updateStats();
            });
            clearInterval(progressIntervals[downloadId]);
            delete progressIntervals[downloadId];
        });
    }

    function retryDownload(libraryId) {
        $.post('/downloads/store', {
            _token: '{{ csrf_token() }}',
            library_id: libraryId
        }, function(response) {
            if (response.redirect) {
                window.location.href = response.redirect;
            } else {
                location.reload();
            }
        });
    }

    function playGame(title) {
        alert('🎮 Launching ' + title + '...\n\n[This is a simulated launch in the demo environment]');
    }

    function updateStats() {
        let active = 0;
        $('.download-card').each(function() {
            const badge = $(this).find('.status-badge');
            if (badge.hasClass('status-downloading') || badge.hasClass('status-paused') || badge.hasClass('status-queued')) {
                active++;
            }
        });
        $('#statActive').text(active);
        $('#statTotal').text($('.download-card').length);
    }

    // Tab switching
    $('.tab-btn').click(function() {
        $('.tab-btn').removeClass('active');
        $('.tab-content').removeClass('active');
        $(this).addClass('active');
        $('#tab-' + $(this).data('tab')).addClass('active');
    });

    // Initialize progress updates for downloading items
    $(document).ready(function() {
        @foreach($activeDownloads as $d)
            @if($d->status === 'downloading' || $d->status === 'queued')
                startProgressUpdate({{ $d->id }});
            @endif
        @endforeach
    });
</script>
@endsection
