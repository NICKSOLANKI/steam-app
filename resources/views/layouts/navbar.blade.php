<link rel="stylesheet" href="{{ asset('asset/css/navbar.css') }}">

<div class="div">

    {{-- Navbar --}}
    @if(Auth::check())
        {{-- Logo / Home --}}
        <a class="Home" href="{{ route('notlogin.index') }}">
            <img class="steampng" src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Steam_Logo.png" alt="Steam Logo">
        </a>

        {{-- Navigation links --}}
        <a class="Home" href="{{ route('notlogin.index') }}">STORE</a>
        <a class="Home" href="{{ route('library') }}">LIBRARY</a>

        {{-- User profile link --}}
        <a class="Home" href="{{ route('profile.show') }}">
            {{ auth()->user()->name }}
        </a>

        <a class="Home" href="{{ route('cart.index') }}">
            <i class="fas fa-shopping-cart"></i> CART
        </a>

        {{-- Subscription link with fallback --}}
        @if(Route::has('subscription'))
            <a class="Home" href="{{ route('subscription') }}">SUBSCRIPTION</a>
        @else
            <a class="Home" href="{{ url('/subscription') }}">SUBSCRIPTION</a>
        @endif

        <a class="Home" href="{{ route('support') }}">SUPPORT</a>

        <a class="Home" href="{{ route('discord.index') }}">COMMUNITY</a>

        {{-- Download link with badge and dropdown --}}
        <a class="Home download-nav-btn" id="downloadNavBtn" href="{{ route('downloads.index') }}"
           onclick="event.stopPropagation(); toggleDropdown(event);">
            📥 DOWNLOADS
            <span class="dl-badge" id="dlBadge">0</span>
            <div class="dl-progress-mini" id="dlProgressMini">
                <div class="dl-progress-mini-fill" id="dlProgressFill" style="width: 0%"></div>
            </div>

            {{-- Download Dropdown --}}
            <div class="download-dropdown" id="downloadDropdown" onclick="event.stopPropagation();">
                <div class="download-dropdown-header">
                    <span class="download-dropdown-title">📥 Downloads Manager</span>
                    <a href="{{ route('downloads.index') }}" class="download-dropdown-viewall">View All →</a>
                </div>
                <div class="download-dropdown-items" id="downloadDropdownItems">
                    <div class="download-dropdown-empty">
                        Loading downloads...
                    </div>
                </div>
            </div>
        </a>

        {{-- Logout --}}
        <a class="Home" href="#"
           onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
            LOGOUT
        </a>
        <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>

    @else
        {{-- Logo / Home --}}
        <a class="Home" href="{{ route('notlogin.index') }}">
            <img class="steampng" src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Steam_Logo.png" alt="Steam Logo">
        </a>

        {{-- Navigation links --}}
        <a class="Home" href="{{ route('notlogin.index') }}">STORE</a>

        <a class="Home" href="{{ route('support') }}">SUPPORT</a>

        {{-- Auth links --}}
        <a class="Auth" href="{{ route('login') }}">LOG IN</a>
        <a class="Auth" href="{{ route('register') }}">REGISTER</a>
    @endif

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    window.APP_BASE_URL = "{{ rtrim(url('/'), '/') }}";
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });
    $.ajaxPrefilter(function(options) {
        if (options.url && typeof options.url === 'string'
            && options.url.charAt(0) === '/'
            && options.url.charAt(1) !== '/') {
            options.url = window.APP_BASE_URL + options.url;
        }
    });
    function appUrl(path) {
        if (!path) return window.APP_BASE_URL || '';
        if (path.charAt(0) === '/' && path.charAt(1) !== '/') {
            return window.APP_BASE_URL + path;
        }
        return path;
    }
</script>
@if(Auth::check())
<script>
    let dlDropdownOpen = false;
    let dlPollingInterval = null;

    function toggleDropdown(e) {
        if (e) e.preventDefault();
        dlDropdownOpen = !dlDropdownOpen;
        $('#downloadDropdown').toggleClass('show', dlDropdownOpen);
        if (dlDropdownOpen) {
            refreshDropdown();
        }
    }

    $(document).click(function(e) {
        if (!$(e.target).closest('.download-nav-btn').length) {
            dlDropdownOpen = false;
            $('#downloadDropdown').removeClass('show');
        }
    });

    function renderDropdownItems(downloads) {
        const container = $('#downloadDropdownItems');
        if (!downloads || downloads.length === 0) {
            container.html(`
                <div class="download-dropdown-empty">
                    📭 No active downloads<br>
                    <small style="color:#8f98a0;">Visit your Library to install games</small>
                </div>
            `);
            return;
        }

        let html = '';
        downloads.forEach(d => {
            const progress = parseFloat(d.progress).toFixed(0);
            const statusClass = ['downloading', 'paused', 'queued', 'completed'].includes(d.status) ? d.status : 'queued';
            const statusText = d.status === 'downloading' ? 'DL' :
                               d.status === 'paused' ? 'PSD' :
                               d.status === 'completed' ? 'OK' : 'Q';
            const isCompleted = d.status === 'completed';

            html += `
                <div class="download-dropdown-item" onclick="window.location.href=appUrl('/downloads/${d.id}')">
                    <div class="dd-image">
                        <img src="${d.game_image}" alt="${d.game_title}"
                             onerror="this.src='https://via.placeholder.com/80x35/0e141b/66c0f4?text=${encodeURIComponent(d.game_title.substring(0,8))}'">
                    </div>
                    <div class="dd-info">
                        <div class="dd-title">${d.game_title}</div>
                        <div class="dd-progress-wrap">
                            <div class="dd-progress-bar">
                                <div class="dd-progress-fill ${isCompleted ? 'completed' : ''}" style="width: ${progress}%"></div>
                            </div>
                            <span class="dd-percent">${progress}%</span>
                        </div>
                        <div class="dd-meta">
                            <span class="dd-speed">
                                ${isCompleted ? '✓ Installed' : (d.current_speed ? parseFloat(d.current_speed).toFixed(1) + ' MB/s' : '...')}
                            </span>
                            <span class="dd-status ${statusClass}">${statusText}</span>
                        </div>
                    </div>
                </div>
            `;
        });
        container.html(html);
    }

    function refreshDropdown() {
        if (!dlDropdownOpen) return;
        $.get('/downloads/api/recent', function(response) {
            renderDropdownItems(response.downloads || []);
        });
    }

    function refreshBadge() {
        $.get('/downloads/api/count', function(response) {
            const count = response.count || 0;
            const badge = $('#dlBadge');
            badge.text(count);
            badge.toggleClass('active', count > 0);

            if (response.active_download) {
                $('#dlProgressMini').addClass('show');
                $('#dlProgressFill').css('width', (response.active_progress || 0) + '%');
            } else {
                $('#dlProgressMini').removeClass('show');
            }
        }).fail(function() {
            // Auth check might fail on guest pages, silently ignore
        });
    }

    $(document).ready(function() {
        refreshBadge();
        refreshDropdown();

        if (!dlPollingInterval) {
            dlPollingInterval = setInterval(function() {
                refreshBadge();
                if (dlDropdownOpen) refreshDropdown();
            }, 3000);
        }
    });
</script>
@endif
