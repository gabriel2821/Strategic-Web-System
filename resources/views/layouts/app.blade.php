<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Strategic Web')</title>
    <link rel="icon" href="{{ asset('photo/BPS4.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
</head>
<body class="font-sans antialiased">
    <!-- Header Section -->
    <header class="bg-primary text-white p-3">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <img src="{{ asset('photo/BPS4-removebg-preview.png') }}" alt="Strategic Web Logo" style="height: 60px;">
                @auth
                    <div class="dropdown">
                        <span class="dropdown-toggle d-flex align-items-center" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                            👤
                        </span>
                        <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="userDropdown">
                            <li class="dropdown-header">{{ auth()->user()->name }}</li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @forelse (auth()->user()->unreadNotifications as $notification)
                                <li class="px-3 small text-dark">
                                    {{ $notification->data['message'] ?? 'No message' }}
                                    <br>
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="text-primary small">View</a>
                                </li>
                            @empty
                                <li class="px-3 text-muted small">Tiada pemberitahuan baru</li>
                            @endforelse
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <span>Welcome, Guest</span>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Wrapper -->
    <div class="d-flex">
        <!-- Sidebar Menu -->
        <nav class="sidebar bg-light border-end">
            <span class="sidebar-toggle-icon">☰</span>
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
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center {{ request()->routeIs('program_rows.report') ? 'active' : '' }}" href="{{ route('program_rows.report') }}">
                                <i class="bi bi-grid"></i>
                                <span class="link-text">Laporan</span>
                            </a>
                        </li>
                        

                        @if (auth()->user()->userType === 'admin')
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center" href="{{ route('dashboard') }}">
                                <i class="bi bi-grid"></i>
                                <span class="link-text">Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('archives.create') }}" class="nav-link d-flex align-items-center">
                                <i class="bi bi-box-arrow-down"></i>
                                <span class="link-text">Arkib</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('google.sheet.send') }}" id="export-to-sheet-form">
                                @csrf
                                <button type="submit" class="btn btn-success export-to-sheet">Eksport</button>
                            </form>
                        </li>
                        @endif
                    @endauth
                </ul>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="flex-grow-1 p-4">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <!-- Feedback elements with loading spinner -->
                <div id="message" style="display: none; background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 15px; border-radius: 4px; z-index: 1000; position: relative;">
                    <span class="spinner" style="display: none;">⏳</span>
                    <span id="message-text"></span>
                </div>
                <div id="error" style="display: none; background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 15px; border-radius: 4px; z-index: 1000; position: relative;"></div>
                <!-- Page Heading from Breeze -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset
                <!-- Page Content -->
                @yield('content')
            </div>
        </main>
    </div>

    <script>
    $(document).ready(function() {
        // Debug DOM presence and initial state
        console.log('Is #message present?', $('#message').length);
        console.log('Is #error present?', $('#error').length);
        console.log('Initial #message display:', $('#message').css('display'));
        console.log('Initial #message visibility:', $('#message').css('visibility'));

        $('#export-to-sheet-form').on('submit', function(event) {
            event.preventDefault();

            if (!confirm('Hantar semua data ke Google Sheet?')) {
                return;
            }

            // Clear session-based alerts
            $('.alert-success.alert-dismissible, .alert-danger.alert-dismissible').alert('close');

            // Reset feedback and show spinner
            console.log('Resetting feedback divs');
            $('#message').css('display', 'block').find('.spinner').css('display', 'inline').end().find('#message-text').text('');
            $('#error').css('display', 'none').text('');

            let $button = $(this).find('.export-to-sheet');
            $button.prop('disabled', true);

            console.log('Initiating AJAX request to: ' + $(this).attr('action'));

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function() {
                    console.log('AJAX request started');
                },
                success: function(response) {
                    console.log('AJAX Success:', response);
                    console.log('Updating #message div with:', response.message);
                    if ($('#message').length) {
                        $('#message').css({
                            'display': 'block',
                            'opacity': '1',
                            'visibility': 'visible'
                        }).find('.spinner').css('display', 'none').end()
                          .find('#message-text').text(response.message || 'Data successfully written to Google Sheet');
                        console.log('After success: #message exists:', $('#message').length);
                        console.log('After success: #message display:', $('#message').css('display'));
                        console.log('After success: #message visibility:', $('#message').css('visibility'));
                        console.log('After success: #message HTML:', $('#message').html());
                    } else {
                        console.error('#message div not found during success callback');
                    }
                    $('#error').css('display', 'none');
                    // Auto-hide after 5 seconds
                    setTimeout(function() {
                        $('#message').fadeOut('slow');
                    }, 5000);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {
                        status: xhr.status,
                        responseText: xhr.responseText,
                        error: error
                    });
                    let errorMsg = xhr.responseJSON?.error || 'An unknown error occurred. Please try again later.';
                    console.log('Updating #error div with:', errorMsg);
                    if ($('#error').length) {
                        $('#error').text('Error: ' + errorMsg).css({
                            'display': 'block',
                            'opacity': '1',
                            'visibility': 'visible'
                        });
                        $('#message').css('display', 'none');
                        console.log('After error: #error display:', $('#error').css('display'));
                    } else {
                        console.error('#error div not found during error callback');
                    }
                    // Auto-hide after 5 seconds
                    setTimeout(function() {
                        $('#error').fadeOut('slow');
                    }, 5000);
                },
                complete: function(xhr, status) {
                    console.log('AJAX Complete:', { status: status, response: xhr.responseText });
                    $button.prop('disabled', false);
                }
            });
        });
    });
    </script>
</body>
</html>