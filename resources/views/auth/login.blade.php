<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 p-3 rounded-lg bg-gruvbox-light-green/20 dark:bg-gruvbox-dark-green/20 border border-gruvbox-light-green dark:border-gruvbox-dark-green">
            <p class="text-sm text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ session('status') }}</p>
        </div>
    @endif

    <h2 class="text-2xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-6">Login</h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 focus:outline-none focus:ring-2 focus:ring-gruvbox-light-blue dark:focus:ring-gruvbox-dark-blue" />
            @error('email')
                <p class="mt-1 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 focus:outline-none focus:ring-2 focus:ring-gruvbox-light-blue dark:focus:ring-gruvbox-dark-blue" />
            @error('password')
                <p class="mt-1 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember"
                   class="w-4 h-4 rounded border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-blue dark:text-gruvbox-dark-blue focus:ring-gruvbox-light-blue dark:focus:ring-gruvbox-dark-blue">
            <label for="remember_me" class="ml-2 text-sm text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">Remember me</label>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
                    Forgot your password?
                </a>
            @endif

            <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                Log in
            </button>
        </div>

        <div class="text-center mt-6 pt-6 border-t border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline font-semibold">Register</a>
            </p>
        </div>
    </form>
</x-guest-layout>
