<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Strategic Web')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="font-sans antialiased">
    <!-- Header Section -->
    <header class="bg-primary text-white p-3">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0">Strategic Web</h1>
                <div>
                    @auth
                        <span>Welcome, {{ auth()->user()->name }}</span>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <a href="#" class="text-white ms-3" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </form>
                    @else
                        <span>Welcome, Guest</span>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Wrapper -->
    <div class="d-flex">
        <!-- Sidebar Menu -->
        <nav class="sidebar bg-light border-end" style="width: 250px;">
            <div class="p-3">
                <h4 class="sidebar-title">Menu</h4>
                <ul class="nav flex-column">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center {{ request()->routeIs('teras.*') ? 'active' : '' }}" href="{{ route('teras.index') }}">
                                <i class="bi bi-grid"></i>
                                <span class="link-text">Teras</span>
                            </a>
                        </li>
                        @if (auth()->user()->userType === 'admin')
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('program_rows.report') ? 'active' : '' }}" href="{{ route('program_rows.report') }}">
                                    <i class="bi bi-grid"></i>
                                    <span class="link-text">Laporan</span>
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="flex-grow-1 p-4">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>