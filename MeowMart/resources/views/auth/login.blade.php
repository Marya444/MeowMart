<x-guest-layout>

    <head>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <style type="text/tailwindcss">
            @layer base {
                body {
                    font-family: 'Instrument Sans', sans-serif;
                    @apply bg-[#FDF5E6] text-[#5C4033];
                    /* Cream Beige background, Mocha Brown text */
                    /* Ensure body covers full viewport and prevents scroll on content */
                    min-height: 80vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .app-card {
                    /* Changed p- to px- and pt- for horizontal and top padding, then added pb- for bottom */
                    @apply bg-white rounded-lg shadow-2xl px-8 pt-8 md:px-12 md:pt-12 lg:px-16 lg:pt-16 pb-8 flex flex-col items-center;
                    @apply border-t-8 border-[#FFA552];
                    max-width: 28rem;
                    width: 90%;
                    box-sizing: border-box;
                    /* Ensure content fits without scrollbar */
                    /* Removed height: 100vh; and overflow: hidden; from .app-card */
                    /* This ensures the card scales naturally and does not force full viewport height,
                       allowing it to fit content while keeping body centered */
                }

                .btn-action {
                    @apply w-full px-6 py-3 rounded-md text-lg font-semibold transition-colors duration-300;
                    @apply bg-[#FFA552] text-white hover:bg-[#FF8C33];
                    /* Soft Orange button */
                }

                /* New style for the alternative/register button */
                .btn-alt-action {
                    @apply w-full px-6 py-3 rounded-md text-lg font-semibold transition-colors duration-300;
                    @apply border-2 border-[#A6D6B9] text-[#A6D6B9] hover:bg-[#A6D6B9] hover:text-white;
                    /* Mint Green border, text, and hover fill */
                }

                .input-field {
                    @apply block mt-1 w-full p-3 rounded-md border border-[#A6D6B9] focus:border-[#FFA552] focus:ring focus:ring-[#FFA552] focus:ring-opacity-50 transition duration-150 ease-in-out;
                }

                .text-label {
                    @apply text-lg font-medium text-[#5C4033] mb-2;
                }

                .link-alt {
                    @apply underline text-base text-[#A8A8A8] hover:text-[#5C4033] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FFA552];
                }

                .checkbox-style {
                    @apply rounded border-[#A6D6B9] text-[#FFA552] shadow-sm focus:ring-[#FFA552];
                }

                .error-message {
                    @apply text-red-500 text-sm mt-2;
                }

                .session-status {
                    @apply mb-4 text-center text-green-600;
                }
            }
        </style>
    </head>

        <div class="app-card w-full sm:max-w-md mt-6 mb-0 shadow-2xl overflow-hidden sm:rounded-lg">
            <h2 class="text-3xl font-bold text-[#5C4033] text-center mb-6">Welcome Back!</h2>

            <x-auth-session-status class="session-status" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="w-full">
                @csrf

                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" class="text-label" />
                    <x-text-input id="email" class="input-field" type="email" name="email" :value="old('email')"
                        required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="error-message" />
                </div>

                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" class="text-label" />
                    <x-text-input id="password" class="input-field" type="password" name="password" required
                        autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="error-message" />
                </div>

                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="checkbox-style" name="remember">
                        <span class="ms-2 text-base text-[#5C4033]">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-6 w-full">
                    @if (Route::has('password.request'))
                        <a class="link-alt" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <button type="submit" class="btn-action w-auto px-8 py-3 ml-3">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>

            <div class="mt-6 w-full text-center">
                <p class="text-lg text-[#5C4033] mb-4">New to MeowMart POS?</p>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-alt-action">
                        {{ __('Register Account') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>
