<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Game Admin Panel')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Laravel Mix Compiled CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Custom Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0e141b 0%, #1b2838 100%);
            color: #c7d5e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .sidebar {
            background: linear-gradient(180deg, #1b2838 0%, #2a475e 100%);
            color: #c7d5e0;
            height: 100vh;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            border-right: 1px solid #417a9b;
            box-shadow: 4px 0 16px rgba(0, 0, 0, 0.6);
            overflow-y: auto;
        }

        .sidebar::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #1b2838;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #417a9b;
            border-radius: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #67c1f5;
        }

        .sidebar .brand {
            padding: 1.5rem;
            font-size: 1.8rem;
            text-align: center;
            border-bottom: 2px solid #417a9b;
            color: #ffffff;
            font-weight: 700;
            background: rgba(27, 40, 56, 0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar .brand i {
            color: #67c1f5;
            margin-right: 10px;
        }

        .sidebar .nav-link {
            color: #c7d5e0;
            padding: 1rem 1.5rem;
            margin: 0.25rem 0.5rem;
            border-radius: 4px;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            background: rgba(103, 193, 245, 0.1);
            color: #ffffff;
            border-left-color: #67c1f5;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(90deg, rgba(103, 193, 245, 0.2) 0%, transparent 100%);
            color: #ffffff;
            font-weight: 600;
            border-left-color: #67c1f5;
            box-shadow: 0 2px 8px rgba(103, 193, 245, 0.3);
        }

        .main-content {
            margin-left: 260px;
            padding: 2rem;
            width: calc(100% - 260px);
            min-height: 100vh;
        }

        /* Card Styling */
        .card {
            background: linear-gradient(135deg, #1b2838 0%, #2a475e 100%);
            border: 1px solid #417a9b;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.6);
            color: #c7d5e0;
        }

        .card-header {
            background: rgba(27, 40, 56, 0.8);
            border-bottom: 1px solid #417a9b;
            color: #ffffff;
        }

        .card-body {
            background: transparent;
        }

        /* Table Styling */
        .table {
            color: #c7d5e0;
        }

        .table-dark {
            background: #1b2838;
            color: #ffffff;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background: rgba(42, 71, 94, 0.3);
        }

        .table-bordered {
            border-color: #417a9b;
        }

        .table-bordered td,
        .table-bordered th {
            border-color: #417a9b;
        }

        /* Button Styling */
        .btn-primary {
            background: linear-gradient(90deg, #06BFFF 0%, #2D73FF 100%);
            border: none;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #1a9fd8 0%, #2563d4 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(6, 191, 255, 0.4);
        }

        .btn-secondary {
            background: #32465a;
            border: 1px solid #417a9b;
            color: #c7d5e0;
        }

        .btn-secondary:hover {
            background: #3d5a73;
            border-color: #67c1f5;
            color: #ffffff;
        }

        .btn-success {
            background: linear-gradient(135deg, #4c6b22 0%, #5c7e10 100%);
            border: none;
            color: #beee11;
        }

        .btn-danger {
            background: #c23030;
            border: none;
        }

        .btn-danger:hover {
            background: #a02020;
        }

        /* Form Controls */
        .form-control,
        .form-select {
            background: #32465a;
            border: 1px solid #417a9b;
            color: #c7d5e0;
        }

        .form-control:focus,
        .form-select:focus {
            background: #3d5a73;
            border-color: #67c1f5;
            color: #ffffff;
            box-shadow: 0 0 8px rgba(103, 193, 245, 0.4);
        }

        .form-label {
            color: #c7d5e0;
            font-weight: 500;
        }

        /* Modal Styling */
        .modal-content {
            background: linear-gradient(135deg, #1b2838 0%, #2a475e 100%);
            border: 1px solid #417a9b;
            color: #c7d5e0;
        }

        .modal-header {
            background: rgba(27, 40, 56, 0.9);
            border-bottom: 1px solid #417a9b;
        }

        .modal-footer {
            border-top: 1px solid #417a9b;
        }

        /* Headings */
        h1, h2, h3, h4, h5, h6 {
            color: #ffffff;
        }

        /* Badges */
        .badge {
            font-weight: 600;
        }

        .bg-success {
            background: #5c7e10 !important;
        }

        .bg-secondary {
            background: #32465a !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="brand">
                <i class="fas fa-gamepad"></i> GamePanel
            </div>
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt fa-fw me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.library') ? 'active' : '' }}" href="{{ route('admin.library') }}">
                        <i class="fas fa-book fa-fw me-2"></i> Library
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.products') ? 'active' : '' }}" href="{{ route('admin.products') }}">
                        <i class="fas fa-shopping-bag me-2"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.subscriptions') ? 'active' : '' }}" href="{{ route('admin.subscriptions') }}">
                        <i class="fas fa-box-open fa-fw me-2"></i> Subscriptions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                        <i class="fas fa-cog fa-fw me-2"></i> Settings
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Optional: jQuery (if needed) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Make sure all page-specific scripts are loaded AFTER the DOM -->
    @yield('scripts')
</body>
</html>
