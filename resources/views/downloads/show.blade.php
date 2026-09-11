@extends('front.gamefront')

@section('title', 'Downloading - ' . $download->game_title)

@section('Content')
<style>
    body {
        background: #1b2838;
        color: #c7d5e0;
        font-family: "Motiva Sans", Arial, Helvetica, sans-serif;
        min-height: 100vh;
    }

    .download-detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .breadcrumb {
        font-size: 12px;
        color: #8f98a0;
        margin-bottom: 20px;
    }

    .breadcrumb a {
        color: #66c0f4;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        color: #ffffff;
    }

    .hero-section {
        background: linear-gradient(135deg, #1a3f5f 0%, #0e141b 100%);
        border-radius: 4px;
        padding: 30px;
        margin-bottom: 30px;
        border: 1px solid #3d4450;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 80% 20%, rgba(102, 192, 244, 0.1), transparent 50%);
        pointer-events: none;
    }

    .hero-content {
        display: flex;
        gap: 30px;
        align-items: flex-start;
        position: relative;
        z-index: 1;
    }

    .hero-image {
        width: 400px;
        height: 180px;
        border-radius: 4px;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        position: relative;
    }

    .hero-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-image::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, transparent 60%, rgba(14, 20, 27, 0.8));
    }

    .hero-info {
        flex: 1;
    }

    .hero-title {
        font-size: 32px;
        color: #ffffff;
        font-weight: 300;
        margin-bottom: 15px;
        letter-spacing: 1px;
    }

    .hero-status {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: bold;
    }

    .status-pill.downloading {
        background: rgba(102, 192, 244, 0.15);
        color: #66c0f4;
        border: 1px solid #66c0f4;
    }

    .status-pill.downloading .pulse {
        width: 8px;
        height: 8px;
        background: #66c0f4;
        border-radius: 50%;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.3); }
    }

    .status-pill.paused {
        background: rgba(201, 162, 39, 0.15);
        color: #c9a227;
        border: 1px solid #c9a227;
    }

    .status-pill.completed {
        background: rgba(163, 207, 6, 0.15);
        color: #a3cf06;
        border: 1px solid #a3cf06;
    }

    .status-pill.queued {
        background: rgba(143, 152, 160, 0.15);
        color: #8f98a0;
        border: 1px solid #8f98a0;
    }

    .hero-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 15px;
    }

    .meta-box {
        background: rgba(14, 20, 27, 0.5);
        padding: 15px;
        border-radius: 4px;
        border-left: 3px solid #66c0f4;
    }

    .meta-label {
        font-size: 11px;
        color: #8f98a0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .meta-value {
        font-size: 20px;
        color: #ffffff;
        font-weight: 500;
    }

    .meta-value.speed {
        color: #a3cf06;
    }

    .meta-value.percent {
        color: #66c0f4;
    }

    .progress-section {
        background: #163a50;
        border-radius: 4px;
        padding: 30px;
        margin-bottom: 25px;
        border: 1px solid #3d4450;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .progress-title {
        font-size: 16px;
        color: #ffffff;
        font-weight: 500;
    }

    .progress-percentage {
        font-size: 28px;
        font-weight: bold;
        color: #66c0f4;
        font-family: 'Consolas', monospace;
    }

    .main-progress-outer {
        height: 20px;
        background: #0e141b;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.5);
        margin-bottom: 15px;
    }

    .main-progress-inner {
        height: 100%;
        background: linear-gradient(90deg, #417a9b, #66c0f4, #8dd4f7);
        background-size: 200% 100%;
        border-radius: 10px;
        position: relative;
        transition: width 0.4s ease;
        animation: gradientMove 3s ease infinite;
    }

    @keyframes gradientMove {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .main-progress-inner::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            90deg,
            transparent 0%,
            rgba(255, 255, 255, 0.4) 50%,
            transparent 100%
        );
        animation: shine 1.2s infinite;
    }

    @keyframes shine {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .progress-info-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        padding-top: 15px;
        border-top: 1px solid #3d4450;
    }

    .info-block {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 11px;
        color: #8f98a0;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 15px;
        color: #c7d5e0;
    }

    .info-value.highlight {
        color: #a3cf06;
        font-weight: bold;
    }

    .info-value.eta {
        color: #66c0f4;
        font-weight: bold;
    }

    .speed-chart-section {
        background: #163a50;
        border-radius: 4px;
        padding: 25px;
        margin-bottom: 25px;
        border: 1px solid #3d4450;
    }

    .section-title {
        font-size: 16px;
        color: #ffffff;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #3d4450;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-icon {
        color: #66c0f4;
    }

    .chart-container {
        height: 160px;
        background: #0e141b;
        border-radius: 4px;
        padding: 15px;
        position: relative;
        overflow: hidden;
    }

    .speed-canvas {
        width: 100%;
        height: 100%;
        display: block;
    }

    .chart-grid {
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        bottom: 15px;
        background-image:
            linear-gradient(rgba(61, 68, 80, 0.3) 1px, transparent 1px),
            linear-gradient(90deg, rgba(61, 68, 80, 0.3) 1px, transparent 1px);
        background-size: 100% 33%, 10% 100%;
        pointer-events: none;
    }

    .chart-y-labels {
        position: absolute;
        top: 15px;
        left: 0;
        bottom: 15px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        font-size: 10px;
        color: #8f98a0;
        padding: 0 5px;
    }

    .controls-section {
        background: #163a50;
        border-radius: 4px;
        padding: 25px;
        margin-bottom: 25px;
        border: 1px solid #3d4450;
    }

    .control-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .ctrl-btn {
        padding: 14px 28px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .ctrl-btn-primary {
        background: linear-gradient(to bottom, #417a9b, #2e5c78);
        color: #ffffff;
    }

    .ctrl-btn-primary:hover {
        background: linear-gradient(to bottom, #4e93b8, #366d8e);
        box-shadow: 0 4px 15px rgba(102, 192, 244, 0.3);
    }

    .ctrl-btn-warning {
        background: linear-gradient(to bottom, #c9a227, #a0801f);
        color: #ffffff;
    }

    .ctrl-btn-warning:hover {
        background: linear-gradient(to bottom, #dab42d, #b58d22);
    }

    .ctrl-btn-success {
        background: linear-gradient(to bottom, #5c7e10, #3e590a);
        color: #ffffff;
    }

    .ctrl-btn-success:hover {
        background: linear-gradient(to bottom, #6d9313, #4a6a0c);
    }

    .ctrl-btn-danger {
        background: linear-gradient(to bottom, #7a2e2e, #542020);
        color: #ffffff;
    }

    .ctrl-btn-danger:hover {
        background: linear-gradient(to bottom, #933636, #632626);
    }

    .ctrl-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .details-section {
        background: #163a50;
        border-radius: 4px;
        padding: 25px;
        border: 1px solid #3d4450;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
    }

    .detail-item {
        padding: 15px;
        background: rgba(14, 20, 27, 0.5);
        border-radius: 4px;
    }

    .detail-label {
        font-size: 11px;
        color: #8f98a0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .detail-text {
        font-size: 14px;
        color: #c7d5e0;
        word-break: break-all;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #8f98a0;
        text-decoration: none;
        font-size: 13px;
        margin-bottom: 20px;
        transition: color 0.2s;
    }

    .back-btn:hover {
        color: #66c0f4;
    }

    .steam-anim-overlay {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: linear-gradient(135deg, #1b2838, #2a475e);
        border: 1px solid #66c0f4;
        border-radius: 4px;
        padding: 12px 16px;
        box-shadow: 0 8px 32px rgba(102, 192, 244, 0.2);
        z-index: 1000;
        min-width: 280px;
    }

    .steam-mini-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .steam-mini-title {
        font-size: 12px;
        color: #ffffff;
        font-weight: bold;
    }

    .steam-mini-progress {
        height: 4px;
        background: #0e141b;
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 6px;
    }

    .steam-mini-bar {
        height: 100%;
        background: linear-gradient(90deg, #417a9b, #66c0f4);
        border-radius: 2px;
        transition: width 0.3s;
        animation: gradientMove 2s ease infinite;
        background-size: 200% 100%;
    }

    .steam-mini-info {
        display: flex;
        justify-content: space-between;
        font-size: 10px;
        color: #8f98a0;
    }
</style>

<div class="download-detail-container">
    <a href="{{ route('downloads.index') }}" class="back-btn">← Back to Downloads</a>

    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-image">
                <img src="{{ $download->game_image }}" alt="{{ $download->game_title }}"
                     onerror="this.src='https://via.placeholder.com/400x180/0e141b/66c0f4?text={{ urlencode($download->game_title) }}'">
            </div>
            <div class="hero-info">
                <h1 class="hero-title">{{ $download->game_title }}</h1>
                <div class="hero-status">
                    <span class="status-pill {{ $download->status }}" id="statusPill">
                        @if($download->status === 'downloading')
                            <span class="pulse"></span> DOWNLOADING
                        @elseif($download->status === 'paused')
                            ⏸ PAUSED
                        @elseif($download->status === 'completed')
                            ✓ READY TO PLAY
                        @elseif($download->status === 'queued')
                            📋 IN QUEUE
                        @else
                            {{ strtoupper($download->status) }}
                        @endif
                    </span>
                </div>
                <div class="hero-meta">
                    <div class="meta-box">
                        <div class="meta-label">Progress</div>
                        <div class="meta-value percent" id="heroPercent">{{ $download->progress }}%</div>
                    </div>
                    <div class="meta-box">
                        <div class="meta-label">Downloaded</div>
                        <div class="meta-value" id="heroDownloaded">{{ number_format($download->downloaded_size, 2) }} GB</div>
                    </div>
                    <div class="meta-box">
                        <div class="meta-label">Total Size</div>
                        <div class="meta-value">{{ number_format($download->total_size, 2) }} GB</div>
                    </div>
                    <div class="meta-box">
                        <div class="meta-label">Current Speed</div>
                        <div class="meta-value speed" id="heroSpeed">{{ $download->current_speed }} MB/s</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="progress-section">
        <div class="progress-header">
            <span class="progress-title">📊 Download Progress</span>
            <span class="progress-percentage" id="bigPercent">{{ $download->progress }}%</span>
        </div>
        <div class="main-progress-outer">
            <div class="main-progress-inner" id="mainProgressBar" style="width: {{ $download->progress }}%"></div>
        </div>
        <div class="progress-info-row">
            <div class="info-block">
                <span class="info-label">⚡ Current Speed</span>
                <span class="info-value highlight" id="infoSpeed">{{ $download->current_speed }} MB/s</span>
            </div>
            <div class="info-block">
                <span class="info-label">🚀 Peak Speed</span>
                <span class="info-value" id="infoPeak">{{ $download->peak_speed }} MB/s</span>
            </div>
            <div class="info-block">
                <span class="info-label">📦 Downloaded</span>
                <span class="info-value" id="infoDownloaded">{{ number_format($download->downloaded_size, 3) }} GB</span>
            </div>
            <div class="info-block">
                <span class="info-label">📁 Total Size</span>
                <span class="info-value">{{ number_format($download->total_size, 3) }} GB</span>
            </div>
            <div class="info-block">
                <span class="info-label">⏱️ Time Remaining</span>
                <span class="info-value eta" id="infoETA">Calculating...</span>
            </div>
            <div class="info-block">
                <span class="info-label">💾 Disk Usage</span>
                <span class="info-value">{{ number_format($download->progress * $download->total_size / 10000, 1) }}%</span>
            </div>
        </div>
    </div>

    <div class="speed-chart-section">
        <h3 class="section-title"><span class="section-icon">📈</span> Download Speed Over Time</h3>
        <div class="chart-container">
            <div class="chart-y-labels">
                <span id="yMax">50 MB/s</span>
                <span id="yMid">25 MB/s</span>
                <span>0 MB/s</span>
            </div>
            <div class="chart-grid"></div>
            <canvas class="speed-canvas" id="speedChart"></canvas>
        </div>
    </div>

    <div class="controls-section">
        <h3 class="section-title"><span class="section-icon">🎮</span> Download Controls</h3>
        <div class="control-buttons" id="controlButtons">
            @if($download->status === 'downloading')
                <button class="ctrl-btn ctrl-btn-warning" onclick="doAction('pause')">⏸ Pause Download</button>
                <button class="ctrl-btn ctrl-btn-danger" onclick="doAction('cancel')">✕ Cancel Download</button>
            @elseif($download->status === 'paused' || $download->status === 'queued')
                <button class="ctrl-btn ctrl-btn-success" onclick="doAction('resume')">▶ Resume Download</button>
                <button class="ctrl-btn ctrl-btn-danger" onclick="doAction('cancel')">✕ Cancel Download</button>
            @elseif($download->status === 'completed')
                <button class="ctrl-btn ctrl-btn-success" onclick="playGame()">▶ Play Now</button>
                <button class="ctrl-btn ctrl-btn-primary" onclick="openLocation()">📂 Open File Location</button>
            @else
                <a href="{{ route('library') }}" class="ctrl-btn ctrl-btn-primary">← Go to Library</a>
            @endif
            <a href="{{ route('downloads.index') }}" class="ctrl-btn ctrl-btn-primary">📋 All Downloads</a>
        </div>
    </div>

    <div class="details-section">
        <h3 class="section-title"><span class="section-icon">ℹ️</span> Install Details</h3>
        <div class="details-grid">
            <div class="detail-item">
                <div class="detail-label">Install Location</div>
                <div class="detail-text">{{ $download->install_path }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Download Started</div>
                <div class="detail-text">{{ $download->started_at ? $download->started_at->format('F d, Y H:i:s') : 'Pending' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Estimated Completion</div>
                <div class="detail-text" id="completionDate">In progress...</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Total Downloaded So Far</div>
                <div class="detail-text" id="totalTransferred">{{ number_format($download->downloaded_size * 1024, 0) }} MB</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Build ID</div>
                <div class="detail-text">{{ str_pad($download->id * 1337, 10, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Library Item ID</div>
                <div class="detail-text">#{{ $download->library_id }}</div>
            </div>
        </div>
    </div>
</div>

<div class="steam-anim-overlay" id="miniOverlay" style="{{ $download->status === 'completed' ? 'display:none;' : '' }}">
    <div class="steam-mini-header">
        <span class="steam-mini-title" id="miniTitle">{{ $download->game_title }}</span>
        <span id="miniPercent" style="color:#66c0f4; font-size:11px; font-weight:bold;">{{ $download->progress }}%</span>
    </div>
    <div class="steam-mini-progress">
        <div class="steam-mini-bar" id="miniBar" style="width: {{ $download->progress }}%"></div>
    </div>
    <div class="steam-mini-info">
        <span id="miniSpeed">{{ $download->current_speed }} MB/s</span>
        <span id="miniETA">Calculating...</span>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const DOWNLOAD_ID = {{ $download->id }};
    let currentStatus = '{{ $download->status }}';
    let progressInterval = null;
    let speedHistory = [];
    const maxHistoryPoints = 50;

    const canvas = document.getElementById('speedChart');
    const ctx = canvas.getContext('2d');

    function resizeCanvas() {
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    function drawChart() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        if (speedHistory.length < 2) return;

        const padding = 30;
        const chartWidth = canvas.width - padding * 2;
        const chartHeight = canvas.height - padding;

        const maxSpeed = Math.max(...speedHistory, 50);
        document.getElementById('yMax').textContent = Math.round(maxSpeed) + ' MB/s';
        document.getElementById('yMid').textContent = Math.round(maxSpeed / 2) + ' MB/s';

        ctx.strokeStyle = 'rgba(102, 192, 244, 0.1)';
        ctx.lineWidth = 2;
        ctx.beginPath();

        const step = chartWidth / Math.max(maxHistoryPoints - 1, speedHistory.length - 1);
        let x = padding;

        for (let i = 0; i < speedHistory.length; i++) {
            const speed = speedHistory[i];
            const y = canvas.height - padding - (speed / maxSpeed) * chartHeight;
            if (i === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
            x += step;
        }
        ctx.stroke();

        const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
        gradient.addColorStop(0, 'rgba(102, 192, 244, 0.4)');
        gradient.addColorStop(1, 'rgba(102, 192, 244, 0)');
        ctx.fillStyle = gradient;
        ctx.lineTo(x - step, canvas.height - padding);
        ctx.lineTo(padding, canvas.height - padding);
        ctx.closePath();
        ctx.fill();

        ctx.strokeStyle = '#66c0f4';
        ctx.lineWidth = 2;
        ctx.shadowColor = '#66c0f4';
        ctx.shadowBlur = 8;
        ctx.beginPath();
        x = padding;
        for (let i = 0; i < speedHistory.length; i++) {
            const speed = speedHistory[i];
            const y = canvas.height - padding - (speed / maxSpeed) * chartHeight;
            if (i === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
            x += step;
        }
        ctx.stroke();
        ctx.shadowBlur = 0;

        if (speedHistory.length > 0) {
            const lastSpeed = speedHistory[speedHistory.length - 1];
            const lastX = padding + (speedHistory.length - 1) * step;
            const lastY = canvas.height - padding - (lastSpeed / maxSpeed) * chartHeight;
            ctx.beginPath();
            ctx.arc(lastX, lastY, 4, 0, Math.PI * 2);
            ctx.fillStyle = '#a3cf06';
            ctx.fill();
            ctx.shadowColor = '#a3cf06';
            ctx.shadowBlur = 15;
            ctx.fill();
            ctx.shadowBlur = 0;
        }
    }

    function formatETA(seconds) {
        if (!isFinite(seconds) || seconds <= 0) return 'Calculating...';
        if (seconds > 86400 * 7) return 'More than a week';
        const days = Math.floor(seconds / 86400);
        const hours = Math.floor((seconds % 86400) / 3600);
        const mins = Math.floor((seconds % 3600) / 60);
        const secs = Math.floor(seconds % 60);
        if (days > 0) return `${days}d ${hours}h ${mins}m`;
        if (hours > 0) return `${hours}h ${mins}m ${secs}s`;
        if (mins > 0) return `${mins}m ${secs}s`;
        return `${secs}s`;
    }

    function updateUI(d) {
        document.getElementById('heroPercent').textContent = parseFloat(d.progress).toFixed(1) + '%';
        document.getElementById('bigPercent').textContent = parseFloat(d.progress).toFixed(1) + '%';
        document.getElementById('mainProgressBar').style.width = d.progress + '%';
        document.getElementById('heroDownloaded').textContent = parseFloat(d.downloaded_size).toFixed(2) + ' GB';
        document.getElementById('heroSpeed').textContent = parseFloat(d.current_speed).toFixed(1) + ' MB/s';
        document.getElementById('infoSpeed').textContent = parseFloat(d.current_speed).toFixed(2) + ' MB/s';
        document.getElementById('infoPeak').textContent = parseFloat(d.peak_speed).toFixed(2) + ' MB/s';
        document.getElementById('infoDownloaded').textContent = parseFloat(d.downloaded_size).toFixed(3) + ' GB';
        document.getElementById('totalTransferred').textContent = (parseFloat(d.downloaded_size) * 1024).toFixed(0) + ' MB';
        document.getElementById('miniPercent').textContent = parseFloat(d.progress).toFixed(0) + '%';
        document.getElementById('miniBar').style.width = d.progress + '%';
        document.getElementById('miniSpeed').textContent = parseFloat(d.current_speed).toFixed(1) + ' MB/s';

        speedHistory.push(parseFloat(d.current_speed));
        if (speedHistory.length > maxHistoryPoints) speedHistory.shift();
        drawChart();

        if (parseFloat(d.current_speed) > 0 && parseFloat(d.progress) < 100) {
            const remainingGB = parseFloat(d.total_size) - parseFloat(d.downloaded_size);
            const remainingMB = remainingGB * 1024;
            const etaSeconds = remainingMB / parseFloat(d.current_speed);
            const etaText = formatETA(etaSeconds);
            document.getElementById('infoETA').textContent = etaText;
            document.getElementById('miniETA').textContent = etaText;

            if (isFinite(etaSeconds) && etaSeconds > 0) {
                const compDate = new Date(Date.now() + etaSeconds * 1000);
                document.getElementById('completionDate').textContent = compDate.toLocaleString();
            }
        }

        updateStatusUI(d.status);
    }

    function updateStatusUI(status) {
        const pill = document.getElementById('statusPill');
        const controls = document.getElementById('controlButtons');
        const overlay = document.getElementById('miniOverlay');
        pill.className = 'status-pill ' + status;

        switch(status) {
            case 'downloading':
                pill.innerHTML = '<span class="pulse"></span> DOWNLOADING';
                overlay.style.display = 'block';
                controls.innerHTML = `
                    <button class="ctrl-btn ctrl-btn-warning" onclick="doAction('pause')">⏸ Pause Download</button>
                    <button class="ctrl-btn ctrl-btn-danger" onclick="doAction('cancel')">✕ Cancel Download</button>
                    <a href="{{ route('downloads.index') }}" class="ctrl-btn ctrl-btn-primary">📋 All Downloads</a>
                `;
                break;
            case 'paused':
                pill.innerHTML = '⏸ PAUSED';
                overlay.style.display = 'block';
                controls.innerHTML = `
                    <button class="ctrl-btn ctrl-btn-success" onclick="doAction('resume')">▶ Resume Download</button>
                    <button class="ctrl-btn ctrl-btn-danger" onclick="doAction('cancel')">✕ Cancel Download</button>
                    <a href="{{ route('downloads.index') }}" class="ctrl-btn ctrl-btn-primary">📋 All Downloads</a>
                `;
                break;
            case 'completed':
                pill.innerHTML = '✓ READY TO PLAY';
                overlay.style.display = 'none';
                controls.innerHTML = `
                    <button class="ctrl-btn ctrl-btn-success" onclick="playGame()">▶ Play Now</button>
                    <button class="ctrl-btn ctrl-btn-primary" onclick="openLocation()">📂 Open File Location</button>
                    <a href="{{ route('downloads.index') }}" class="ctrl-btn ctrl-btn-primary">📋 All Downloads</a>
                    <a href="{{ route('library') }}" class="ctrl-btn ctrl-btn-primary">🎮 My Library</a>
                `;
                if (progressInterval) {
                    clearInterval(progressInterval);
                    progressInterval = null;
                }
                break;
            case 'queued':
                pill.innerHTML = '📋 IN QUEUE';
                overlay.style.display = 'block';
                controls.innerHTML = `
                    <button class="ctrl-btn ctrl-btn-success" onclick="doAction('resume')">▶ Start Download</button>
                    <button class="ctrl-btn ctrl-btn-danger" onclick="doAction('cancel')">✕ Cancel</button>
                    <a href="{{ route('downloads.index') }}" class="ctrl-btn ctrl-btn-primary">📋 All Downloads</a>
                `;
                break;
            case 'cancelled':
                pill.innerHTML = '✕ CANCELLED';
                overlay.style.display = 'none';
                controls.innerHTML = `
                    <a href="{{ route('library') }}" class="ctrl-btn ctrl-btn-success">↻ Retry from Library</a>
                    <a href="{{ route('downloads.index') }}" class="ctrl-btn ctrl-btn-primary">📋 All Downloads</a>
                `;
                break;
        }
    }

    function startProgressPolling() {
        if (progressInterval) clearInterval(progressInterval);

        progressInterval = setInterval(() => {
            $.post(`/downloads/${DOWNLOAD_ID}/progress`, {
                _token: '{{ csrf_token() }}'
            }, function(response) {
                updateUI(response.download);
                currentStatus = response.download.status;
            });
        }, 1000);
    }

    function doAction(action) {
        if (action === 'cancel' && !confirm('Are you sure you want to cancel this download?')) return;

        $.post(`/downloads/${DOWNLOAD_ID}/${action}`, {
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.download) {
                updateUI(response.download);
                currentStatus = response.download.status;
                if ((action === 'pause') && progressInterval) {
                    clearInterval(progressInterval);
                    progressInterval = null;
                }
                if ((action === 'resume') && !progressInterval && currentStatus !== 'completed') {
                    startProgressPolling();
                }
                if (action === 'cancel') {
                    if (progressInterval) {
                        clearInterval(progressInterval);
                        progressInterval = null;
                    }
                    setTimeout(() => {
                        window.location.href = '{{ route('downloads.index') }}';
                    }, 1500);
                }
            }
        });
    }

    function playGame() {
        alert('🎮 Launching {{ $download->game_title }}...\n\nGame is ready to play!\n\n[This is a simulated launch in the demo environment]');
    }

    function openLocation() {
        alert('📂 Opening file location:\n\n{{ $download->install_path }}\n\n[This is a simulated action in the demo environment]');
    }

    $(document).ready(function() {
        speedHistory = Array(20).fill(0);
        drawChart();

        @if($download->status === 'downloading' || $download->status === 'queued' || $download->status === 'paused')
            startProgressPolling();
        @endif
    });
</script>
@endsection
