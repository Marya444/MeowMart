<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Welcome to MeowMart POS System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
        @layer base {
            body {
                font-family: 'Instrument Sans', sans-serif;
                @apply bg-[#FDF5E6] text-[#5C4033]; /* Cream Beige background, Mocha Brown text */
            }
            .app-card {
                @apply bg-white rounded-lg shadow-2xl p-8 md:p-12 lg:p-16 flex flex-col items-center;
                @apply border-t-8 border-[#FFA552]; /* Accent border at the top for emphasis */
            }
            .btn-action {
                @apply w-full px-6 py-3 rounded-md text-lg font-semibold transition-colors duration-300 mb-4;
                @apply bg-[#FFA552] text-white hover:bg-[#FF8C33]; /* Soft Orange button */
            }
            .btn-alt-action {
                @apply w-full px-6 py-3 rounded-md text-lg font-semibold transition-colors duration-300;
                @apply border-2 border-[#A6D6B9] text-[#A6D6B9] hover:bg-[#A6D6B9] hover:text-white; /* Mint Green border, text, and hover fill */
            }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">
    <div class="app-card max-w-md w-full mx-auto text-center">
        <div class="mb-8">
            <img src="{{ asset('img/logohd_meowmart.png') }}" alt="MeowMart POS Logo" class="h-24 mx-auto mb-4">
            <h1 class="text-4xl font-bold text-[#5C4033]">MeowMart POS System</h1>
        </div>

        <p class="text-xl text-[#A8A8A8] mb-8">
            Your trusted solution for seamless retail management.
        </p>

        <div class="w-full">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-action">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-action">Log In</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-alt-action">Register Account</a>
                    @endif
                @endauth
            @endif
        </div>

        <footer class="mt-12 text-sm text-[#A8A8A8]">
            &copy; {{ date('Y') }} MeowMart POS. All rights reserved.
        </footer>
    </div>
</body>
</html>
