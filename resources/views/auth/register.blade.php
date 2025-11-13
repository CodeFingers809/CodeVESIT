<x-guest-layout>
    <h2 class="text-2xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-6">Register</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 focus:outline-none focus:ring-2 focus:ring-gruvbox-light-blue dark:focus:ring-gruvbox-dark-blue" />
            @error('name')
                <p class="mt-1 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">College Email (@ves.ac.in)</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="yourname@ves.ac.in"
                   class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 focus:outline-none focus:ring-2 focus:ring-gruvbox-light-blue dark:focus:ring-gruvbox-dark-blue" />
            <p class="mt-1 text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Must be a valid @ves.ac.in email address</p>
            @error('email')
                <p class="mt-1 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
            @enderror
        </div>

        <!-- Department -->
        <div>
            <label for="department" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Department</label>
            <select id="department" name="department" required
                    class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 focus:outline-none focus:ring-2 focus:ring-gruvbox-light-blue dark:focus:ring-gruvbox-dark-blue">
                <option value="">Select Department</option>
                <option value="Computer Engineering" {{ old('department') == 'Computer Engineering' ? 'selected' : '' }}>Computer Engineering</option>
                <option value="Information Technology" {{ old('department') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                <option value="Artificial Intelligence & Data Science" {{ old('department') == 'Artificial Intelligence & Data Science' ? 'selected' : '' }}>Artificial Intelligence & Data Science</option>
                <option value="Electronics & Telecommunication" {{ old('department') == 'Electronics & Telecommunication' ? 'selected' : '' }}>Electronics & Telecommunication</option>
                <option value="Electronics Engineering" {{ old('department') == 'Electronics Engineering' ? 'selected' : '' }}>Electronics & Computer Science</option>
                <option value="Instrumentation Engineering" {{ old('department') == 'Instrumentation Engineering' ? 'selected' : '' }}>Automation & Robotics</option>
            </select>
            @error('department')
                <p class="mt-1 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
            @enderror
        </div>

        <!-- Year -->
        <div>
            <label for="year" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Year</label>
            <select id="year" name="year" required
                    class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 focus:outline-none focus:ring-2 focus:ring-gruvbox-light-blue dark:focus:ring-gruvbox-dark-blue">
                <option value="">Select Year</option>
                <option value="FE" {{ old('year') == 'FE' ? 'selected' : '' }}>First Year (FE)</option>
                <option value="SE" {{ old('year') == 'SE' ? 'selected' : '' }}>Second Year (SE)</option>
                <option value="TE" {{ old('year') == 'TE' ? 'selected' : '' }}>Third Year (TE)</option>
                <option value="BE" {{ old('year') == 'BE' ? 'selected' : '' }}>Final Year (BE)</option>
            </select>
            @error('year')
                <p class="mt-1 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
            @enderror
        </div>

        <!-- Division -->
        <div>
            <label for="division" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Division</label>
            <input id="division" type="text" name="division" value="{{ old('division') }}" required placeholder="e.g., A, B, C"
                   class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 focus:outline-none focus:ring-2 focus:ring-gruvbox-light-blue dark:focus:ring-gruvbox-dark-blue" />
            @error('division')
                <p class="mt-1 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
            @enderror
        </div>

        <!-- Roll Number -->
        <div>
            <label for="roll_number" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Roll Number</label>
            <input id="roll_number" type="text" name="roll_number" value="{{ old('roll_number') }}" required
                   class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 focus:outline-none focus:ring-2 focus:ring-gruvbox-light-blue dark:focus:ring-gruvbox-dark-blue" />
            @error('roll_number')
                <p class="mt-1 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bio (Optional) -->
        <div>
            <label for="bio" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Bio (Optional)</label>
            <textarea id="bio" name="bio" rows="3" placeholder="Tell us about yourself..."
                      class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 resize-none focus:outline-none focus:ring-2 focus:ring-gruvbox-light-blue dark:focus:ring-gruvbox-dark-blue">{{ old('bio') }}</textarea>
            @error('bio')
                <p class="mt-1 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 focus:outline-none focus:ring-2 focus:ring-gruvbox-light-blue dark:focus:ring-gruvbox-dark-blue" />
            @error('password')
                <p class="mt-1 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 focus:outline-none focus:ring-2 focus:ring-gruvbox-light-blue dark:focus:ring-gruvbox-dark-blue" />
            @error('password_confirmation')
                <p class="mt-1 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full px-6 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                Register
            </button>
        </div>

        <div class="text-center pt-4 border-t border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                Already have an account?
                <a href="{{ route('login') }}" class="text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline font-semibold">Login</a>
            </p>
        </div>
    </form>
</x-guest-layout>
