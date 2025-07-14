<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <title>Login - Strategic Web</title>
    <style>
        /* paste your CSS here — from <style> tag, unchanged */
        /* Keep everything from your provided CSS */
    </style>
</head>
<body>

<section class="hero">
    <div class="hero-text">
        <h1>Selamat datang ke Bahagian Pelaksanaan Strategik</h1>
        <p>Memperkasakan pelaksanaan strategik melalui sistem pintar. Sila log masuk untuk meneruskan perjalanan anda bersama kami.</p>
    </div>

    <div class="login-box">
        <h2>Log Masuk</h2>

        @if (session('status'))
            <div class="error">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email:</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Kata Laluan:</label>
                <input id="password" type="password" name="password" required>
            </div>

            <button type="submit">Daftar Masuk</button>
        </form>
    </div>
</section>

</body>
</html>
