<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('College Email (@ves.ac.in)')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="yourname@ves.ac.in" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Must be a valid @ves.ac.in email address</p>
        </div>

        <!-- Department -->
        <div class="mt-4">
            <x-input-label for="department" :value="__('Department')" />
            <select id="department" name="department" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                <option value="">Select Department</option>
                <option value="Computer Engineering" {{ old('department') == 'Computer Engineering' ? 'selected' : '' }}>Computer Engineering</option>
                <option value="Information Technology" {{ old('department') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                <option value="Electronics Engineering" {{ old('department') == 'Electronics Engineering' ? 'selected' : '' }}>Electronics Engineering</option>
                <option value="Electronics & Telecommunication" {{ old('department') == 'Electronics & Telecommunication' ? 'selected' : '' }}>Electronics & Telecommunication</option>
                <option value="Mechanical Engineering" {{ old('department') == 'Mechanical Engineering' ? 'selected' : '' }}>Mechanical Engineering</option>
                <option value="Instrumentation Engineering" {{ old('department') == 'Instrumentation Engineering' ? 'selected' : '' }}>Instrumentation Engineering</option>
                <option value="Artificial Intelligence & Data Science" {{ old('department') == 'Artificial Intelligence & Data Science' ? 'selected' : '' }}>Artificial Intelligence & Data Science</option>
            </select>
            <x-input-error :messages="$errors->get('department')" class="mt-2" />
        </div>

        <!-- Year -->
        <div class="mt-4">
            <x-input-label for="year" :value="__('Year')" />
            <select id="year" name="year" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                <option value="">Select Year</option>
                <option value="FE" {{ old('year') == 'FE' ? 'selected' : '' }}>First Year (FE)</option>
                <option value="SE" {{ old('year') == 'SE' ? 'selected' : '' }}>Second Year (SE)</option>
                <option value="TE" {{ old('year') == 'TE' ? 'selected' : '' }}>Third Year (TE)</option>
                <option value="BE" {{ old('year') == 'BE' ? 'selected' : '' }}>Final Year (BE)</option>
            </select>
            <x-input-error :messages="$errors->get('year')" class="mt-2" />
        </div>

        <!-- Division -->
        <div class="mt-4">
            <x-input-label for="division" :value="__('Division')" />
            <x-text-input id="division" class="block mt-1 w-full" type="text" name="division" :value="old('division')" required placeholder="e.g., A, B, C" />
            <x-input-error :messages="$errors->get('division')" class="mt-2" />
        </div>

        <!-- Roll Number -->
        <div class="mt-4">
            <x-input-label for="roll_number" :value="__('Roll Number')" />
            <x-text-input id="roll_number" class="block mt-1 w-full" type="text" name="roll_number" :value="old('roll_number')" required />
            <x-input-error :messages="$errors->get('roll_number')" class="mt-2" />
        </div>

        <!-- Bio (Optional) -->
        <div class="mt-4">
            <x-input-label for="bio" :value="__('Bio (Optional)')" />
            <textarea id="bio" name="bio" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Tell us about yourself...">{{ old('bio') }}</textarea>
            <x-input-error :messages="$errors->get('bio')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
