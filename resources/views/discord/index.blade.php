<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STEAM Community - Voice & Text Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/navbar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            background-color: #36393f;
            color: #dcddde;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100%;
            overflow: hidden;
        }

        .discord-container {
            display: flex;
            height: calc(100vh - 105px);
            margin-top: 0;
            padding-top: 0;
        }

        /* Server List */
        .server-list {
            width: 72px;
            background-color: #202225;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px 0;
            overflow-y: auto;
            flex-shrink: 0;
        }

        .server-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #36393f;
            margin-bottom: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.2s;
            position: relative;
            color: #fff;
        }

        .server-icon:hover, .server-icon.active {
            border-radius: 16px;
            background-color: #5865f2;
        }

        .server-icon.home-icon {
            background-color: #5865f2;
        }

        .server-icon.home-icon:hover, .server-icon.home-icon.active {
            background-color: #4752c4;
        }

        .server-icon::before {
            content: '';
            position: absolute;
            left: -16px;
            width: 8px;
            height: 0;
            background-color: white;
            border-radius: 0 4px 4px 0;
            opacity: 0;
            transition: all 0.2s;
        }

        .server-icon:hover::before,
        .server-icon.active::before {
            opacity: 1;
            height: 20px;
        }

        .server-pill {
            width: 32px;
            height: 2px;
            background-color: #36393f;
            margin: 4px 0 8px;
            border-radius: 1px;
        }

        .add-server {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #36393f;
            color: #3ba55c;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            cursor: pointer;
            margin-top: 8px;
            transition: all 0.2s;
        }

        .add-server:hover {
            background-color: #3ba55c;
            color: white;
            border-radius: 16px;
        }

        /* Channel List */
        .channel-list {
            width: 240px;
            background-color: #2f3136;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .server-header {
            height: 48px;
            padding: 0 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #202225;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            color: #fff;
            box-shadow: 0 1px 0 rgba(4,4,5,0.2),0 1.5px 0 rgba(6,6,7,0.05),0 2px 0 rgba(4,4,5,0.05);
        }

        .server-header:hover {
            background-color: #34373c;
        }

        .server-header-chevron {
            font-size: 12px;
            color: #b9bbbe;
        }

        .channels {
            flex: 1;
            padding: 8px;
            overflow-y: auto;
        }

        .channel-category {
            color: #8e9297;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin: 16px 8px 4px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            user-select: none;
        }

        .channel-category:hover {
            color: #dcddde;
        }

        .channel-category-toggle {
            font-size: 10px;
            margin-right: 4px;
        }

        .channel-add-btn {
            font-size: 18px;
            opacity: 0;
            transition: opacity 0.15s;
            line-height: 1;
        }

        .channel-category:hover .channel-add-btn {
            opacity: 1;
        }

        .channel-item {
            padding: 6px 8px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            margin-bottom: 2px;
            color: #8e9297;
            font-size: 15px;
            position: relative;
        }

        .channel-item:hover, .channel-item.active {
            background-color: #393c43;
            color: white;
        }

        .channel-icon {
            margin-right: 6px;
            font-size: 18px;
            flex-shrink: 0;
        }

        .voice-channel-icon {
            color: #3ba55c;
        }

        .channel-name {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .channel-user-count {
            margin-left: auto;
            font-size: 12px;
            color: #8e9297;
            font-weight: 600;
            padding: 0 4px;
        }

        .voice-users-inline {
            margin-top: 2px;
            padding-left: 30px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .voice-user-inline {
            display: flex;
            align-items: center;
            padding: 4px 6px;
            border-radius: 4px;
            font-size: 13px;
            color: #b9bbbe;
        }

        .voice-user-inline:hover {
            background-color: #393c43;
            color: #fff;
        }

        .voice-avatar-inline {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #5865f2;
            margin-right: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            color: #fff;
            position: relative;
            flex-shrink: 0;
        }

        .voice-speaking-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 6px;
            border: 2px solid #3ba55c;
            flex-shrink: 0;
        }

        .voice-speaking-indicator.speaking {
            background-color: #3ba55c;
            animation: speakPulse 0.5s infinite;
        }

        @keyframes speakPulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(59, 165, 92, 0.7); }
            50% { transform: scale(1.15); box-shadow: 0 0 6px 2px rgba(59, 165, 92, 0.3); }
        }

        .voice-muted-icon {
            color: #ed4245;
            margin-left: auto;
            font-size: 12px;
        }

        .voice-icons-inline {
            display: flex;
            gap: 4px;
            margin-left: auto;
            color: #b9bbbe;
        }

        /* User List */
        .user-list {
            width: 240px;
            background-color: #2f3136;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .user-list-header {
            height: 48px;
            padding: 0 16px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #202225;
            font-weight: bold;
            font-size: 13px;
            color: #96989d;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

        .users {
            flex: 1;
            padding: 16px 8px;
            overflow-y: auto;
        }

        .user-category {
            color: #8e9297;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            margin: 0 8px 8px;
            padding-left: 4px;
            letter-spacing: 0.2px;
        }

        .user-item {
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            margin-bottom: 2px;
        }

        .user-item:hover {
            background-color: #393c43;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 12px;
            background-color: #5865f2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #fff;
            flex-shrink: 0;
            position: relative;
        }

        .user-status-dot {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 3px solid #2f3136;
            background-color: #3ba55c;
        }

        .user-status-dot.offline {
            background-color: #747f8d;
        }

        .user-status-dot.idle {
            background-color: #faa61a;
        }

        .user-status-dot.dnd {
            background-color: #ed4245;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 500;
            color: #b9bbbe;
            font-size: 15px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-item:hover .user-name {
            color: #fff;
        }

        .user-status-text {
            font-size: 12px;
            color: #72767d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Chat Area */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #36393f;
            min-width: 0;
        }

        .chat-header {
            height: 48px;
            padding: 0 16px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #202225;
            background-color: #36393f;
            flex-shrink: 0;
            box-shadow: 0 1px 0 rgba(4,4,5,0.2),0 1.5px 0 rgba(6,6,7,0.05);
            justify-content: space-between;
        }

        .chat-header-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chat-header-icon {
            font-size: 24px;
            color: #8e9297;
        }

        .chat-header-title {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
        }

        .chat-header-desc {
            font-size: 14px;
            color: #b9bbbe;
        }

        .chat-header-divider {
            width: 1px;
            height: 24px;
            background-color: #4f545c;
            margin: 0 12px;
        }

        .chat-header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
            color: #b9bbbe;
            font-size: 18px;
        }

        .chat-header-actions i {
            cursor: pointer;
            transition: color 0.15s;
        }

        .chat-header-actions i:hover {
            color: #fff;
        }

        .chat-search {
            background-color: #202225;
            border-radius: 4px;
            padding: 2px 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #72767d;
            font-size: 13px;
            min-width: 140px;
        }

        .chat-search input {
            background: none;
            border: none;
            outline: none;
            color: #b9bbbe;
            font-size: 13px;
            width: 100%;
        }

        /* Voice Panel in chat area */
        .voice-connected-panel {
            background-color: #292b2f;
            padding: 12px 16px;
            display: none;
            border-bottom: 1px solid #202225;
        }

        .voice-connected-panel.active {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .voice-connected-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .voice-connected-icon {
            font-size: 20px;
            color: #3ba55c;
        }

        .voice-connected-text {
            font-size: 14px;
        }

        .voice-connected-title {
            font-weight: 600;
            color: #fff;
        }

        .voice-connected-sub {
            font-size: 12px;
            color: #8e9297;
        }

        .voice-controls {
            display: flex;
            gap: 8px;
        }

        .voice-btn {
            background: #4f545c;
            border: none;
            color: #dcddde;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 16px;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .voice-btn:hover {
            background-color: #5d6269;
        }

        .voice-btn.active {
            background-color: #3ba55c;
            color: white;
        }

        .voice-btn.muted {
            background-color: #ed4245;
            color: white;
        }

        .voice-btn.disconnect {
            background-color: #ed4245;
            color: white;
        }

        .voice-btn.disconnect:hover {
            background-color: #d4383b;
        }

        .chat-messages {
            flex: 1;
            padding: 16px 0;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .chat-messages::-webkit-scrollbar {
            width: 8px;
        }
        .chat-messages::-webkit-scrollbar-track {
            background: #2e3338;
            border-radius: 4px;
        }
        .chat-messages::-webkit-scrollbar-thumb {
            background: #202225;
            border-radius: 4px;
        }

        .message {
            margin-bottom: 0;
            display: flex;
            padding: 2px 16px;
            position: relative;
        }

        .message:hover {
            background-color: #32353b;
        }

        .message.grouped {
            padding-top: 0;
            padding-bottom: 0;
            margin-top: 2px;
        }

        .message.grouped .message-avatar {
            visibility: hidden;
        }

        .message.grouped .message-header {
            display: none;
        }

        .message-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 16px;
            background-color: #5865f2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #fff;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .message-content {
            flex: 1;
            min-width: 0;
            padding: 2px 0;
        }

        .message-header {
            display: flex;
            align-items: baseline;
            margin-bottom: 2px;
        }

        .message-author {
            font-weight: 500;
            color: white;
            margin-right: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .message-author:hover {
            text-decoration: underline;
        }

        .message-author.owner {
            color: #f47b67;
        }

        .message-role-tag {
            background-color: #5865f2;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 1px 6px;
            border-radius: 3px;
            margin-right: 6px;
            text-transform: uppercase;
        }

        .message-timestamp {
            font-size: 12px;
            color: #72767d;
        }

        .message-text {
            color: #dcddde;
            line-height: 1.375rem;
            font-size: 16px;
            word-wrap: break-word;
        }

        .message-divider {
            display: flex;
            align-items: center;
            margin: 16px 16px 8px;
            color: #72767d;
            font-size: 12px;
        }

        .message-divider::before,
        .message-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #4f545c;
        }

        .message-divider::before {
            margin-right: 12px;
        }

        .message-divider::after {
            margin-left: 12px;
        }

        .message-day-label {
            color: #ffffff;
            font-weight: 600;
        }

        .chat-input-wrapper {
            padding: 0 16px 24px;
            background-color: #36393f;
            flex-shrink: 0;
        }

        .chat-input-container {
            background-color: #40444b;
            border-radius: 8px;
            display: flex;
            align-items: flex-end;
        }

        .chat-input-tools {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 10px 10px;
            color: #b9bbbe;
            font-size: 22px;
        }

        .chat-input-tools i {
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.15s;
        }

        .chat-input-tools i:hover {
            color: #fff;
            background-color: #4f545c;
        }

        .chat-input-container textarea {
            flex: 1;
            background-color: transparent;
            border: none;
            outline: none;
            padding: 11px 0;
            color: #dcddde;
            resize: none;
            font-family: inherit;
            font-size: 16px;
            max-height: 200px;
            line-height: 1.375rem;
        }

        .chat-input-send {
            padding: 10px 16px;
            color: #b9bbbe;
            font-size: 22px;
            cursor: pointer;
            align-self: center;
        }

        .chat-input-send:hover {
            color: #fff;
        }

        .typing-indicator {
            height: 24px;
            padding: 0 16px;
            display: flex;
            align-items: center;
            color: #72767d;
            font-size: 13px;
        }

        .typing-dots {
            display: inline-flex;
            gap: 3px;
            margin-right: 6px;
        }

        .typing-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background-color: #72767d;
            animation: typingBounce 1.4s infinite;
        }

        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes typingBounce {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
            30% { transform: translateY(-4px); opacity: 1; }
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.85);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background-color: #36393f;
            border-radius: 6px;
            padding: 0;
            width: 440px;
            max-width: 90%;
            overflow: hidden;
        }

        .modal-header {
            font-size: 20px;
            font-weight: 600;
            padding: 16px;
            text-align: center;
            color: #fff;
            border-bottom: 1px solid #202225;
            background-color: #2f3136;
        }

        .modal-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #b9bbbe;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            background-color: #303339;
            border: 1px solid #040405;
            border-radius: 3px;
            color: #dcddde;
            font-size: 16px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #7289da;
        }

        select.form-control {
            cursor: pointer;
        }

        .btn {
            padding: 10px 24px;
            border-radius: 3px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.17s;
        }

        .btn-primary {
            background-color: #5865f2;
            color: white;
        }

        .btn-primary:hover {
            background-color: #4752c4;
        }

        .btn-secondary {
            background-color: transparent;
            color: #b9bbbe;
            padding: 10px 16px;
        }

        .btn-secondary:hover {
            color: #fff;
            text-decoration: underline;
        }

        .btn-danger {
            background-color: #d83c3e;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c23335;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 16px;
            background-color: #2f3136;
            border-top: 1px solid #202225;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 32px 16px;
            color: #8e9297;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .empty-state-icon {
            font-size: 56px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 24px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 15px;
            color: #b9bbbe;
            max-width: 400px;
            line-height: 1.4;
        }

        .create-server-btn {
            width: auto;
            margin-top: 8px;
            padding: 8px 12px;
            background: none;
            border: none;
            color: #8e9297;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            gap: 6px;
            width: 100%;
            text-align: left;
        }

        .create-server-btn:hover {
            background-color: #393c43;
            color: #fff;
        }

        .public-server-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background-color: #3ba55c;
            border: 2px solid #202225;
            font-size: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        /* Voice channel users expanded view */
        .voice-channel-expanded {
            background-color: #292b2f;
            border-radius: 8px;
            margin: 0 4px 4px;
            padding: 8px;
        }

        .voice-channel-header-row {
            display: flex;
            align-items: center;
            padding: 4px 8px;
            color: #8e9297;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .join-voice-badge {
            margin-left: auto;
            background-color: #3ba55c;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.15s;
            text-transform: none;
        }

        .join-voice-badge:hover {
            background-color: #2d8d49;
        }

        .join-voice-badge.connected {
            background-color: #5865f2;
        }
    </style>
</head>
<body>
    @include('layouts.navbar')

    <div class="discord-container">
        <!-- Server List -->
        <div class="server-list">
            <div class="server-icon home-icon active" onclick="selectServer(null)" title="Direct Messages">
                <i class="fas fa-home"></i>
            </div>
            <div class="server-pill"></div>

            @foreach($servers as $server)
                <div class="server-icon" onclick="selectServer({{ $server->id }})" data-server-id="{{ $server->id }}" title="{{ $server->name }}">
                    <span>{{ substr($server->name, 0, 2) }}</span>
                </div>
            @endforeach

            @if(isset($publicServers) && $publicServers->count() > 0)
                <div class="server-pill"></div>
                <div style="color:#8e9297; font-size:10px; font-weight:600; padding:4px 0; text-transform:uppercase;">Public</div>

                @foreach($publicServers as $server)
                    <div class="server-icon" onclick="joinServer({{ $server->id }})" data-server-id="{{ $server->id }}" title="Join: {{ $server->name }}">
                        <span>{{ substr($server->name, 0, 2) }}</span>
                        <div class="public-server-badge">+</div>
                    </div>
                @endforeach
            @endif

            <div class="add-server" onclick="showCreateServerModal()" title="Add a Server">
                <i class="fas fa-plus"></i>
            </div>
        </div>

        <!-- Channel List -->
        <div class="channel-list" id="channelList">
            <div class="server-header" id="serverHeader" onclick="toggleServerMenu()">
                <span id="serverHeaderTitle">STEAM Community</span>
                <i class="fas fa-chevron-down server-header-chevron"></i>
            </div>
            <div class="channels" id="channels">
                <div class="empty-state">
                    <div class="empty-state-icon">🎮</div>
                    <h3>Welcome to Community</h3>
                    <p>Select a server from the left to view channels, or create your own!</p>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-area" id="chatArea">
            <div class="chat-header" id="chatHeader">
                <div class="chat-header-info">
                    <i class="fas fa-hashtag chat-header-icon"></i>
                    <span class="chat-header-title" id="chatHeaderTitle">Welcome</span>
                </div>
                <div class="chat-header-actions">
                    <i class="fas fa-bell" title="Notifications"></i>
                    <i class="fas fa-thumbtack" title="Pinned Messages"></i>
                    <i class="fas fa-users" title="Toggle Members" onclick="toggleMembersList()"></i>
                    <div class="chat-search">
                        <i class="fas fa-search" style="font-size:13px;"></i>
                        <input type="text" placeholder="Search">
                    </div>
                    <i class="fas fa-inbox" title="Inbox"></i>
                    <i class="fas fa-question-circle" title="Help"></i>
                </div>
            </div>

            <div class="voice-connected-panel" id="voiceConnectedPanel">
                <div class="voice-connected-info">
                    <i class="fas fa-headset voice-connected-icon"></i>
                    <div class="voice-connected-text">
                        <div class="voice-connected-title" id="voiceConnectedTitle">General Voice</div>
                        <div class="voice-connected-sub" id="voiceConnectedCount">— 0 user connected</div>
                    </div>
                </div>
                <div class="voice-controls">
                    <button class="voice-btn active" id="micBtn" onclick="toggleMic()" title="Mute">
                        <i class="fas fa-microphone"></i>
                    </button>
                    <button class="voice-btn active" id="deafenBtn" onclick="toggleDeafen()" title="Deafen">
                        <i class="fas fa-headphones"></i>
                    </button>
                    <button class="voice-btn disconnect" onclick="disconnectVoice()" title="Disconnect">
                        <i class="fas fa-sign-out-alt"></i>
                        <span style="font-size:13px; font-weight:600;">Disconnect</span>
                    </button>
                </div>
            </div>

            <div class="chat-messages" id="chatMessages">
                <div class="empty-state">
                    <div class="empty-state-icon">💬</div>
                    <h3>No Channel Selected</h3>
                    <p>Pick a channel on the left to start chatting, or click a voice channel to talk with friends!</p>
                </div>
            </div>

            <div class="typing-indicator" id="typingIndicator" style="display:none;">
                <span class="typing-dots">
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                </span>
                <span id="typingText">Someone is typing...</span>
            </div>

            <div class="chat-input-wrapper">
                <div class="chat-input-container" id="chatInputContainer" style="opacity:0.6; pointer-events:none;">
                    <div class="chat-input-tools">
                        <i class="fas fa-plus-circle" title="Upload"></i>
                    </div>
                    <textarea id="messageInput" placeholder="Select a channel to start chatting..." rows="1"></textarea>
                    <div class="chat-input-send" onclick="sendMessage()" title="Send Message">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- User List -->
        <div class="user-list" id="userList">
            <div class="user-list-header">
                <i class="fas fa-users" style="margin-right:6px;"></i> Members
            </div>
            <div class="users" id="users">
                <div class="empty-state">
                    <p style="font-size:14px;">No server selected</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Server Modal -->
    <div class="modal" id="createServerModal">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-plus-circle" style="color:#5865f2; margin-right:8px;"></i>
                Create Your Server
            </div>
            <div class="modal-body">
                <form id="createServerForm">
                    <div class="form-group">
                        <label>Server Name</label>
                        <input type="text" class="form-control" name="name" required maxlength="100" placeholder="My Awesome Community" autofocus>
                    </div>
                    <div class="form-group">
                        <label>Description (Optional)</label>
                        <textarea class="form-control" name="description" rows="3" maxlength="500" placeholder="What's your community about? Gaming, art, tech...?"></textarea>
                    </div>
                    <div class="form-group">
                        <label style="display:flex; align-items:center; gap:8px;">
                            <input type="checkbox" name="is_public" id="isPublic" checked style="width:16px; height:16px; cursor:pointer;">
                            <span style="color:#dcddde; font-size:13px; font-weight:500; text-transform:none; letter-spacing:0;">Public Server (anyone can discover and join)</span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="hideCreateServerModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitCreateServer()">
                    <i class="fas fa-check"></i> Create Server
                </button>
            </div>
        </div>
    </div>

    <!-- Create Channel Modal -->
    <div class="modal" id="createChannelModal">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-hashtag" style="color:#5865f2; margin-right:8px;"></i>
                Create Channel
            </div>
            <div class="modal-body">
                <form id="createChannelForm">
                    <div class="form-group">
                        <label>Channel Type</label>
                        <select class="form-control" name="type" id="channelType">
                            <option value="text">💬 Text Channel</option>
                            <option value="voice">🔊 Voice Channel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Channel Name</label>
                        <input type="text" class="form-control" name="name" id="channelName" required maxlength="100" placeholder="general" autofocus>
                    </div>
                    <div class="form-group">
                        <label>Description (Optional)</label>
                        <textarea class="form-control" name="description" rows="3" maxlength="500" placeholder="What's this channel for?"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="hideCreateChannelModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitCreateChannel()">
                    <i class="fas fa-check"></i> Create Channel
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const currentUserId = {{ Auth::id() }};
        const currentUserName = "{{ Auth::user()->name }}";
        let currentServer = null;
        let currentChannel = null;
        let currentChannelType = null;
        let currentVoiceChannel = null;
        let localStream = null;
        let peerConnections = {};
        let messageInterval = null;
        let signalingInterval = null;
        let voiceUsersPoll = null;
        let isMuted = false;
        let isDeafened = false;
        let lastMessageAuthor = null;
        let lastMessageDate = null;

        function showToast(message, type = 'info') {
            const colors = {
                info: '#5865f2',
                success: '#3ba55c',
                error: '#ed4245',
                warning: '#faa61a'
            };
            const toast = document.createElement('div');
            toast.style.cssText = `
                position:fixed; bottom:30px; left:50%; transform:translateX(-50%);
                background:${colors[type] || colors.info}; color:white; padding:12px 24px;
                border-radius:4px; z-index:99999; font-weight:500; font-size:14px;
                box-shadow:0 8px 24px rgba(0,0,0,0.4);
                animation: toastIn 0.3s ease;
            `;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.transition = 'opacity 0.3s, transform 0.3s';
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(-50%) translateY(20px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        const toastStyle = document.createElement('style');
        toastStyle.textContent = `@keyframes toastIn { from {opacity:0; transform:translate(-50%, 20px);} to {opacity:1; transform:translate(-50%, 0);} }`;
        document.head.appendChild(toastStyle);

        // Modal functions
        function showCreateServerModal() {
            document.getElementById('createServerModal').classList.add('active');
        }
        function hideCreateServerModal() {
            document.getElementById('createServerModal').classList.remove('active');
            document.getElementById('createServerForm').reset();
        }

        function showCreateChannelModal() {
            if (!currentServer) {
                showToast('Please select or create a server first!', 'warning');
                return;
            }
            document.getElementById('createChannelModal').classList.add('active');
        }
        function hideCreateChannelModal() {
            document.getElementById('createChannelModal').classList.remove('active');
            document.getElementById('createChannelForm').reset();
        }

        function submitCreateServer() {
            const form = document.getElementById('createServerForm');
            const formData = new FormData(form);
            const name = formData.get('name');
            if (!name || name.trim().length === 0) {
                showToast('Please enter a server name', 'error');
                return;
            }

            const data = {
                name: name,
                description: formData.get('description'),
                is_public: document.getElementById('isPublic').checked
            };

            $.ajax({
                url: '/discord/servers',
                type: 'POST',
                data: data,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(response) {
                    hideCreateServerModal();
                    showToast('🎉 Server created successfully!', 'success');
                    setTimeout(() => location.reload(), 600);
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message || 'Failed to create server';
                    showToast(msg, 'error');
                }
            });
        }

        function submitCreateChannel() {
            const form = document.getElementById('createChannelForm');
            const formData = new FormData(form);
            const name = formData.get('name');
            if (!name || name.trim().length === 0) {
                showToast('Please enter a channel name', 'error');
                return;
            }

            const data = {
                name: name,
                type: formData.get('type'),
                description: formData.get('description')
            };

            $.ajax({
                url: `/discord/servers/${currentServer}/channels`,
                type: 'POST',
                data: data,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(response) {
                    hideCreateChannelModal();
                    showToast('✅ Channel created!', 'success');
                    loadServer(currentServer);
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message || 'Failed to create channel';
                    showToast(msg, 'error');
                }
            });
        }

        function toggleServerMenu() {
            // Placeholder for server menu
        }

        function toggleMembersList() {
            $('#userList').toggle();
        }

        // Server functions
        function selectServer(serverId) {
            if (serverId === currentServer) return;

            currentServer = serverId;
            currentChannel = null;
            currentChannelType = null;

            document.querySelectorAll('.server-icon').forEach(icon => {
                icon.classList.remove('active');
                if (icon.dataset.serverId == serverId ||
                    (serverId === null && icon.classList.contains('home-icon'))) {
                    icon.classList.add('active');
                }
            });

            if (serverId) {
                loadServer(serverId);
            } else {
                resetView();
            }
        }

        function loadServer(serverId) {
            $.get(`/discord/servers/${serverId}`, function(data) {
                const server = data.server;
                currentServer = server.id;

                document.getElementById('serverHeaderTitle').textContent = server.name;

                loadChannels(server.channels, data.activeVoiceSessions || {});
                loadMembers(server.members || []);
            }).fail(function() {
                showToast('Could not load server', 'error');
            });
        }

        function loadChannels(channels, activeVoiceSessions) {
            const channelsContainer = document.getElementById('channels');
            const textChannels = channels.filter(c => c.type === 'text');
            const voiceChannels = channels.filter(c => c.type === 'voice');

            let html = '';

            if (textChannels.length > 0) {
                html += `
                    <div class="channel-category">
                        <span><span class="channel-category-toggle">▼</span> Text Channels</span>
                        <span class="channel-add-btn" onclick="event.stopPropagation(); document.getElementById('channelType').value='text'; showCreateChannelModal();">+</span>
                    </div>
                `;
                textChannels.forEach(channel => {
                    const active = channel.id === currentChannel;
                    html += `
                        <div class="channel-item ${active ? 'active' : ''}" onclick="selectChannel(${channel.id})" data-channel-id="${channel.id}">
                            <span class="channel-icon">#</span>
                            <span class="channel-name">${channel.name}</span>
                        </div>
                    `;
                });
            }

            if (voiceChannels.length > 0) {
                html += `
                    <div class="channel-category">
                        <span><span class="channel-category-toggle">▼</span> Voice Channels</span>
                        <span class="channel-add-btn" onclick="event.stopPropagation(); document.getElementById('channelType').value='voice'; showCreateChannelModal();">+</span>
                    </div>
                `;
                voiceChannels.forEach(channel => {
                    const usersInChannel = (activeVoiceSessions && activeVoiceSessions[channel.id])
                        ? Object.values(activeVoiceSessions[channel.id]) : [];
                    const connected = channel.id === currentVoiceChannel;

                    html += `
                        <div class="channel-item ${connected ? 'active' : ''}" onclick="joinVoiceChannel(${channel.id})" data-channel-id="${channel.id}">
                            <span class="channel-icon voice-channel-icon"><i class="fas fa-volume-up"></i></span>
                            <span class="channel-name">${channel.name}</span>
                            <span class="join-voice-badge ${connected ? 'connected' : ''}" onclick="event.stopPropagation(); joinVoiceChannel(${channel.id});">
                                ${connected ? 'Connected' : (usersInChannel.length > 0 ? 'Join' : `🔊 ${usersInChannel.length}`)}
                            </span>
                        </div>
                    `;

                    if (usersInChannel.length > 0) {
                        html += '<div class="voice-users-inline">';
                        usersInChannel.forEach(session => {
                            const user = session.user || session;
                            if (!user) return;
                            const isCurrentUser = user.id === currentUserId;
                            html += `
                                <div class="voice-user-inline">
                                    <span class="voice-speaking-indicator ${Math.random() > 0.7 ? 'speaking' : ''}"></span>
                                    <div class="voice-avatar-inline">
                                        ${user.name ? user.name.charAt(0).toUpperCase() : '?'}
                                    </div>
                                    <span style="flex:1; font-size:13px; ${isCurrentUser ? 'color:#fff; font-weight:600;' : ''}">
                                        ${user.name || 'User'} ${isCurrentUser ? '<small style="color:#8e9297;">(you)</small>' : ''}
                                    </span>
                                    <span class="voice-icons-inline">
                                        <i class="fas fa-microphone" style="${session.is_muted ? 'color:#ed4245;' : ''}" title="${session.is_muted ? 'Muted' : 'Mic active'}"></i>
                                    </span>
                                </div>
                            `;
                        });
                        html += '</div>';
                    }
                });
            }

            html += `
                <button class="create-server-btn" onclick="showCreateChannelModal()">
                    <span style="font-size:18px; color:#3ba55c;">+</span>
                    Create Channel
                </button>
            `;

            channelsContainer.innerHTML = html;
        }

        function loadMembers(members) {
            const usersContainer = document.getElementById('users');

            const owner = [];
            const admins = [];
            const regulars = [];

            members.forEach(member => {
                const user = member.user;
                if (!user) return;
                if (member.role === 'owner') owner.push({ user, role: member.role });
                else if (member.role === 'admin') admins.push({ user, role: member.role });
                else regulars.push({ user, role: member.role });
            });

            let html = '';

            if (owner.length > 0) {
                html += `<div class="user-category">Owner — ${owner.length}</div>`;
                owner.forEach(m => {
                    html += renderMemberItem(m.user, m.role);
                });
            }
            if (admins.length > 0) {
                html += `<div class="user-category">Admins — ${admins.length}</div>`;
                admins.forEach(m => {
                    html += renderMemberItem(m.user, m.role);
                });
            }
            if (regulars.length > 0) {
                html += `<div class="user-category">Members — ${regulars.length}</div>`;
                regulars.forEach(m => {
                    html += renderMemberItem(m.user, m.role);
                });
            }

            if (html === '') {
                html = '<div class="empty-state" style="padding:20px;"><p>No members yet</p></div>';
            }

            usersContainer.innerHTML = html;
        }

        function renderMemberItem(user, role) {
            const isCurrentUser = user.id === currentUserId;
            const initial = user.name ? user.name.charAt(0).toUpperCase() : '?';
            const roleClass = role === 'owner' ? 'owner' : '';
            return `
                <div class="user-item">
                    <div class="user-avatar" style="${role === 'owner' ? 'background: linear-gradient(135deg,#f47b67,#d45c46);' : ''}">
                        ${initial}
                        <div class="user-status-dot"></div>
                    </div>
                    <div class="user-info">
                        <div class="user-name ${roleClass}">
                            ${user.name} ${isCurrentUser ? '<span style="color:#72767d; font-size:12px;">(you)</span>' : ''}
                        </div>
                        <div class="user-status-text">
                            ${role === 'owner' ? '👑 Server Owner' : (role === 'admin' ? '🛡️ Admin' : 'Online')}
                        </div>
                    </div>
                </div>
            `;
        }

        function resetView() {
            document.getElementById('serverHeaderTitle').textContent = 'Direct Messages';
            document.getElementById('chatHeaderTitle').textContent = 'Direct Messages';
            document.getElementById('channels').innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">🏠</div>
                    <h3>Direct Messages</h3>
                    <p>Select a server from the left to view channels, or create your own!</p>
                    <button class="create-server-btn" style="margin-top:20px; justify-content:center;" onclick="showCreateServerModal()">
                        <i class="fas fa-plus" style="color:#3ba55c;"></i> Create Your First Server
                    </button>
                </div>
            `;
            document.getElementById('chatMessages').innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">💬</div>
                    <h3>Welcome to Community</h3>
                    <p>Join or create a server to start chatting with friends using text and voice channels!</p>
                </div>
            `;
            disableChatInput();
            document.getElementById('users').innerHTML = `
                <div class="empty-state" style="padding:20px;">
                    <p style="font-size:14px;">Select a server to see members</p>
                </div>
            `;
        }

        function enableChatInput() {
            const container = document.getElementById('chatInputContainer');
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        }

        function disableChatInput() {
            const container = document.getElementById('chatInputContainer');
            container.style.opacity = '0.6';
            container.style.pointerEvents = 'none';
        }

        // Channel functions
        function selectChannel(channelId) {
            if (channelId === currentChannel) return;

            currentChannel = channelId;
            currentChannelType = 'text';

            document.querySelectorAll('.channel-item').forEach(item => {
                item.classList.remove('active');
                if (item.dataset.channelId == channelId &&
                    !item.querySelector('.voice-channel-icon')) {
                    item.classList.add('active');
                }
            });

            loadChannel(channelId);
        }

        function loadChannel(channelId) {
            $.get(`/discord/channels/${channelId}/messages`, function(data) {
                const messages = data.messages;
                const channelNameEl = document.querySelector(`.channel-item[data-channel-id="${channelId}"] .channel-name`);
                const channelName = channelNameEl ? channelNameEl.textContent : 'general';

                document.getElementById('chatHeaderTitle').textContent = channelName;
                loadMessages(messages);
                enableChatInput();
                const input = document.getElementById('messageInput');
                input.placeholder = `Message #${channelName}`;

                if (messageInterval) clearInterval(messageInterval);
                messageInterval = setInterval(() => {
                    if (currentChannel === channelId) {
                        $.get(`/discord/channels/${channelId}/messages`, function(d2) {
                            loadMessages(d2.messages, true);
                        });
                    }
                }, 2500);
            });
        }

        function loadMessages(messages, incremental = false) {
            const messagesContainer = document.getElementById('chatMessages');

            if (messages.length === 0) {
                messagesContainer.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">💬</div>
                        <h3>No messages yet</h3>
                        <p>Be the first to say hi! 👋</p>
                    </div>
                `;
                lastMessageAuthor = null;
                lastMessageDate = null;
                return;
            }

            let html = '';
            lastMessageAuthor = null;
            lastMessageDate = null;

            messages.forEach(msg => {
                const user = msg.user || { name: 'Unknown', id: 0 };
                const msgDate = new Date(msg.created_at).toLocaleDateString();
                const msgTime = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                const authorKey = user.id;
                const sameGroup = lastMessageAuthor === authorKey && lastMessageDate === msgDate;

                if (lastMessageDate !== msgDate) {
                    html += `
                        <div class="message-divider">
                            <span class="message-day-label">${msgDate}</span>
                        </div>
                    `;
                }

                const msgClass = sameGroup ? 'message grouped' : 'message';
                const isOwner = user.id === 1;

                html += `
                    <div class="${msgClass}">
                        <div class="message-avatar" style="${isOwner ? 'background: linear-gradient(135deg,#f47b67,#d45c46);' : ''}">
                            ${user.name ? user.name.charAt(0).toUpperCase() : '?'}
                        </div>
                        <div class="message-content">
                            <div class="message-header">
                                <span class="message-author ${isOwner ? 'owner' : ''}">${user.name}</span>
                                <span class="message-timestamp">${msgTime}</span>
                            </div>
                            <div class="message-text">${escapeHtml(msg.content)}</div>
                        </div>
                    </div>
                `;

                lastMessageAuthor = authorKey;
                lastMessageDate = msgDate;
            });

            messagesContainer.innerHTML = html;
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Voice chat functions
        function joinVoiceChannel(channelId) {
            if (currentVoiceChannel === channelId) {
                showToast('Already in this voice channel', 'info');
                return;
            }

            if (currentVoiceChannel) {
                leaveVoiceChannelInternal(currentVoiceChannel, false);
            }

            $.ajax({
                url: `/discord/channels/${channelId}/join-voice`,
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(data) {
                    currentVoiceChannel = channelId;

                    const channelEl = document.querySelector(`.channel-item[data-channel-id="${channelId}"] .channel-name`);
                    const channelName = channelEl ? channelEl.textContent : 'Voice';
                    document.getElementById('voiceConnectedTitle').textContent = channelName;
                    document.getElementById('voiceConnectedCount').textContent = '— Connecting...';
                    document.getElementById('voiceConnectedPanel').classList.add('active');

                    showToast(`🎙️ Connected to: ${channelName}`, 'success');

                    sendSignal(channelId, 'join', { user_id: currentUserId, name: currentUserName });
                    startSignalingPolling(channelId);
                    startVoiceUsersPolling(channelId);

                    navigator.mediaDevices.getUserMedia({ audio: true })
                        .then(stream => {
                            localStream = stream;
                            if (voiceUsersPoll) clearInterval(voiceUsersPoll);
                            loadVoiceChannelUsers(channelId);
                        })
                        .catch(err => {
                            console.warn('Mic error:', err);
                            showToast('⚠️ Microphone not available. Voice channel joined, but others cannot hear you.', 'warning');
                        });

                    loadServer(currentServer);
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON?.message || 'Could not join voice', 'error');
                }
            });
        }

        function startVoiceUsersPolling(channelId) {
            if (voiceUsersPoll) clearInterval(voiceUsersPoll);
            voiceUsersPoll = setInterval(() => {
                if (currentVoiceChannel === channelId) {
                    loadVoiceChannelUsers(channelId);
                }
            }, 3000);
        }

        function loadVoiceChannelUsers(channelId) {
            $.get(`/discord/channels/${channelId}/voice-users`, function(data) {
                const users = data.users || [];
                const count = users.length;
                document.getElementById('voiceConnectedCount').textContent = `— ${count} user${count === 1 ? '' : 's'} connected`;

                users.forEach(sess => {
                    const uid = sess.user_id || sess.user?.id;
                    if (uid && uid !== currentUserId && !peerConnections[uid] && localStream) {
                        establishWebRTCConnection(uid, channelId);
                    }
                });

                loadServer(currentServer);
            });
        }

        function toggleMic() {
            isMuted = !isMuted;
            const btn = document.getElementById('micBtn');
            if (localStream) {
                localStream.getAudioTracks().forEach(t => t.enabled = !isMuted);
            }
            btn.innerHTML = isMuted
                ? '<i class="fas fa-microphone-slash"></i>'
                : '<i class="fas fa-microphone"></i>';
            btn.classList.toggle('muted', isMuted);
            btn.classList.toggle('active', !isMuted);
            showToast(isMuted ? '🔇 Microphone muted' : '🎙️ Microphone unmuted', 'info');

            if (currentVoiceChannel) {
                $.ajax({
                    url: `/discord/channels/${currentVoiceChannel}/voice-session`,
                    type: 'PUT',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: { is_muted: isMuted }
                });
            }
        }

        function toggleDeafen() {
            isDeafened = !isDeafened;
            const btn = document.getElementById('deafenBtn');
            if (localStream && isDeafened) {
                localStream.getAudioTracks().forEach(t => t.enabled = false);
                Object.values(peerConnections).forEach(pc => {
                    pc.getReceivers().forEach(r => {
                        if (r.track && r.track.kind === 'audio') r.track.enabled = false;
                    });
                });
            } else if (localStream) {
                if (!isMuted) localStream.getAudioTracks().forEach(t => t.enabled = true);
                Object.values(peerConnections).forEach(pc => {
                    pc.getReceivers().forEach(r => {
                        if (r.track && r.track.kind === 'audio') r.track.enabled = true;
                    });
                });
            }
            btn.innerHTML = isDeafened
                ? '<i class="fas fa-headphones-alt"></i>'
                : '<i class="fas fa-headphones"></i>';
            btn.classList.toggle('muted', isDeafened);
            btn.classList.toggle('active', !isDeafened);
            showToast(isDeafened ? '🔕 Deafened' : '🔊 Undeafened', 'info');

            if (currentVoiceChannel) {
                $.ajax({
                    url: `/discord/channels/${currentVoiceChannel}/voice-session`,
                    type: 'PUT',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: { is_deafened: isDeafened }
                });
            }
        }

        function disconnectVoice() {
            if (currentVoiceChannel) {
                leaveVoiceChannel(currentVoiceChannel);
            }
        }

        function leaveVoiceChannel(channelId) {
            leaveVoiceChannelInternal(channelId, true);
        }

        function leaveVoiceChannelInternal(channelId, showAlert) {
            sendSignal(channelId, 'leave', { user_id: currentUserId });

            if (signalingInterval) {
                clearInterval(signalingInterval);
                signalingInterval = null;
            }
            if (voiceUsersPoll) {
                clearInterval(voiceUsersPoll);
                voiceUsersPoll = null;
            }

            Object.keys(peerConnections).forEach(uid => {
                try { peerConnections[uid].close(); } catch (e) {}
                delete peerConnections[uid];
            });

            $.ajax({
                url: `/discord/channels/${channelId}/leave-voice`,
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function() {
                    currentVoiceChannel = null;
                    document.getElementById('voiceConnectedPanel').classList.remove('active');

                    if (localStream) {
                        localStream.getTracks().forEach(t => t.stop());
                        localStream = null;
                    }

                    isMuted = false;
                    isDeafened = false;
                    const micBtn = document.getElementById('micBtn');
                    const defBtn = document.getElementById('deafenBtn');
                    micBtn.innerHTML = '<i class="fas fa-microphone"></i>';
                    micBtn.classList.add('active');
                    micBtn.classList.remove('muted');
                    defBtn.innerHTML = '<i class="fas fa-headphones"></i>';
                    defBtn.classList.add('active');
                    defBtn.classList.remove('muted');

                    if (showAlert) showToast('👋 Left voice channel', 'info');
                    if (currentServer) loadServer(currentServer);
                }
            });
        }

        // WebRTC signaling functions
        function establishWebRTCConnection(targetUserId, channelId) {
            if (peerConnections[targetUserId]) return;
            const configuration = {
                iceServers: [
                    { urls: 'stun:stun.l.google.com:19302' },
                    { urls: 'stun:stun1.l.google.com:19302' }
                ]
            };
            const pc = new RTCPeerConnection(configuration);
            peerConnections[targetUserId] = pc;
            if (localStream) {
                localStream.getTracks().forEach(t => pc.addTrack(t, localStream));
            }
            pc.ontrack = (e) => {
                const el = document.createElement('audio');
                el.autoplay = true;
                el.srcObject = e.streams[0];
                el.setAttribute('data-peer', targetUserId);
                document.body.appendChild(el);
            };
            pc.onicecandidate = (e) => {
                if (e.candidate) sendSignal(channelId, 'ice-candidate', e.candidate, targetUserId);
            };
            pc.createOffer()
                .then(o => pc.setLocalDescription(o))
                .then(() => sendSignal(channelId, 'offer', pc.localDescription, targetUserId))
                .catch(e => console.error('offer error', e));
        }

        function sendSignal(channelId, type, data, targetUserId = null) {
            $.ajax({
                url: `/discord/channels/${channelId}/signal`,
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: {
                    type: type,
                    data: JSON.stringify(data),
                    target_user_id: targetUserId
                }
            }).fail(() => {});
        }

        function handleSignaling(data) {
            const { type, data: signalData, senderId } = data;
            if (senderId === currentUserId) return;
            let pc = peerConnections[senderId];
            if (!pc && ['offer', 'join'].includes(type)) {
                const configuration = {
                    iceServers: [
                        { urls: 'stun:stun.l.google.com:19302' },
                        { urls: 'stun:stun1.l.google.com:19302' }
                    ]
                };
                pc = new RTCPeerConnection(configuration);
                peerConnections[senderId] = pc;
                if (localStream) localStream.getTracks().forEach(t => pc.addTrack(t, localStream));
                pc.ontrack = (e) => {
                    const el = document.createElement('audio');
                    el.autoplay = true;
                    el.srcObject = e.streams[0];
                    el.setAttribute('data-peer', senderId);
                    document.body.appendChild(el);
                };
                pc.onicecandidate = (e) => {
                    if (e.candidate && currentVoiceChannel) {
                        sendSignal(currentVoiceChannel, 'ice-candidate', e.candidate, senderId);
                    }
                };
            }
            if (!pc) return;
            switch (type) {
                case 'offer':
                    pc.setRemoteDescription(new RTCSessionDescription(signalData))
                        .then(() => pc.createAnswer())
                        .then(a => pc.setLocalDescription(a))
                        .then(() => { if (currentVoiceChannel) sendSignal(currentVoiceChannel, 'answer', pc.localDescription, senderId); })
                        .catch(e => console.warn('offer handling', e));
                    break;
                case 'answer':
                    pc.setRemoteDescription(new RTCSessionDescription(signalData)).catch(e => {});
                    break;
                case 'ice-candidate':
                    pc.addIceCandidate(new RTCIceCandidate(signalData)).catch(e => {});
                    break;
                case 'join':
                    if (currentVoiceChannel && localStream) {
                        establishWebRTCConnection(senderId, currentVoiceChannel);
                    }
                    break;
                case 'leave':
                    try { if (peerConnections[senderId]) peerConnections[senderId].close(); } catch (e) {}
                    delete peerConnections[senderId];
                    document.querySelectorAll(`audio[data-peer="${senderId}"]`).forEach(e => e.remove());
                    break;
            }
        }

        function startSignalingPolling(channelId) {
            if (signalingInterval) clearInterval(signalingInterval);
            signalingInterval = setInterval(() => {
                $.get(`/discord/channels/${channelId}/signals`, function(data) {
                    (data.signals || []).forEach(signal => {
                        try {
                            const parsed = typeof signal.data === 'string' ? JSON.parse(signal.data) : signal.data;
                            handleSignaling({
                                type: signal.type,
                                data: parsed,
                                senderId: signal.sender_id
                            });
                        } catch (e) {}
                    });
                });
            }, 1500);
        }

        // Message sending
        const msgInput = document.getElementById('messageInput');
        msgInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        msgInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 200) + 'px';
        });

        function sendMessage() {
            const input = document.getElementById('messageInput');
            const content = input.value.trim();
            if (!content || !currentChannel) return;

            $.ajax({
                url: `/discord/channels/${currentChannel}/messages`,
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: { content: content },
                success: function() {
                    input.value = '';
                    input.style.height = 'auto';
                    loadChannel(currentChannel);
                },
                error: function() {
                    showToast('Failed to send message', 'error');
                }
            });
        }

        function joinServer(serverId) {
            if (confirm('Join this public server?')) {
                $.ajax({
                    url: `/discord/servers/${serverId}/join`,
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function() {
                        showToast('✅ Joined server!', 'success');
                        setTimeout(() => location.reload(), 600);
                    },
                    error: function(xhr) {
                        showToast(xhr.responseJSON?.message || 'Could not join', 'error');
                    }
                });
            }
        }
    </script>
</body>
</html>
