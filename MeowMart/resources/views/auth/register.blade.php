<x-guest-layout>
    <head>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <style type="text/tailwindcss">
            @layer base {
                body {
                    font-family: 'Instrument Sans', sans-serif;
                    @apply bg-[#FDF5E6] text-[#5C4033]; /* Cream Beige background, Mocha Brown text */
                    /* Ensure body covers full viewport and prevents scroll on content */
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .app-card {
                    /* Padding adjustments for the card */
                    @apply bg-white rounded-lg shadow-2xl px-8 pt-8 md:px-12 md:pt-12 lg:px-16 lg:pt-16 pb-6 flex flex-col items-center;
                    @apply border-t-8 border-[#FFA552]; /* Accent border at the top for emphasis */
                    max-width: 28rem; /* Equivalent to sm:max-w-md */
                    width: 90%; /* Occupy 90% of screen width */
                    box-sizing: border-box;
                }

                .btn-action {
                    @apply w-full px-6 py-3 rounded-md text-lg font-semibold transition-colors duration-300;
                    @apply bg-[#FFA552] text-white hover:bg-[#FF8C33]; /* Soft Orange button */
                }

                .btn-alt-action {
                    @apply w-full px-6 py-3 rounded-md text-lg font-semibold transition-colors duration-300;
                    @apply border-2 border-[#A6D6B9] text-[#A6D6B9] hover:bg-[#A6D6B9] hover:text-white; /* Mint Green border, text, and hover fill */
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
    <div class="min-h-screen flex flex-col sm:justify-center items-center p-4 bg-[#FDF5E6]">

        <div class="app-card w-full sm:max-w-md shadow-2xl overflow-hidden sm:rounded-lg">
            <h2 class="text-3xl font-bold text-[#5C4033] text-center mb-6">Register New Account</h2>

            <form method="POST" action="{{ route('register') }}" class="w-full">
                @csrf

                <div class="mb-4">
                    <x-input-label for="name" :value="__('Name')" class="text-label" />
                    <x-text-input id="name" class="input-field" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="error-message" />
                </div>

                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" class="text-label" />
                    <x-text-input id="email" class="input-field" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="error-message" />
                </div>

                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" class="text-label" />
                    <x-text-input id="password" class="input-field" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="error-message" />
                </div>

                <div class="mb-6"> {{-- Increased mb for more spacing before buttons --}}
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-label" />
                    <x-text-input id="password_confirmation" class="input-field" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="error-message" />
                </div>

                <div class="flex flex-col items-center justify-center mt-6 w-full">
                    <button type="submit" class="btn-action mb-4">
                        {{ __('Register') }}
                    </button>

                    <a class="link-alt" href="{{ route('login') }}">
                        {{ __('Already have an account? Log in') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
