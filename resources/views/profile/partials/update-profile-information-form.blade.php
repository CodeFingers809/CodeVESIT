<section>
    <header>
        <h2 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Profile Information
        </h2>

        <p class="mt-1 text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
            Update your account's profile information and email address.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0" />
            @error('name')
                <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0" />
            @error('email')
                <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 rounded-lg bg-gruvbox-light-yellow/20 dark:bg-gruvbox-dark-yellow/20 border border-gruvbox-light-yellow/50 dark:border-gruvbox-dark-yellow/50">
                    <p class="text-sm text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                        Your email address is unverified.

                        <button form="send-verification" class="underline text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:opacity-75">
                            Click here to re-send the verification email.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-gruvbox-light-green dark:text-gruvbox-dark-green">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label for="avatar_seed" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Avatar Seed</label>
            <p class="text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 mb-2">Change this to get a different avatar. Leave empty to use your name.</p>
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <input id="avatar_seed" name="avatar_seed" type="text" value="{{ old('avatar_seed', $user->avatar_seed) }}"
                           placeholder="{{ $user->name }}"
                           oninput="updateAvatarPreview()"
                           class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0" />
                    @error('avatar_seed')
                        <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col items-center">
                    <p class="text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 mb-1">Preview</p>
                    <img id="avatar_preview"
                         src="https://api.dicebear.com/7.x/bottts/svg?seed={{ urlencode($user->avatar_seed ?: $user->name) }}"
                         alt="Avatar Preview"
                         class="w-16 h-16 rounded-full border-2 border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                </div>
            </div>
            <button type="button" onclick="generateRandomSeed()" class="mt-2 text-sm text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
                Generate Random
            </button>
        </div>

        <script>
            function updateAvatarPreview() {
                const input = document.getElementById('avatar_seed');
                const preview = document.getElementById('avatar_preview');
                const seed = input.value || '{{ $user->name }}';
                preview.src = `https://api.dicebear.com/7.x/bottts/svg?seed=${encodeURIComponent(seed)}`;
            }

            function generateRandomSeed() {
                const randomSeed = Math.random().toString(36).substring(2, 15);
                document.getElementById('avatar_seed').value = randomSeed;
                updateAvatarPreview();
            }
        </script>

        <div>
            <label class="flex items-center">
                <input type="checkbox" name="email_notifications" value="1" {{ old('email_notifications', $user->email_notifications ?? true) ? 'checked' : '' }}
                       class="w-4 h-4 text-gruvbox-light-blue dark:text-gruvbox-dark-blue border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 rounded">
                <span class="ml-2 text-sm text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">Receive email notifications for study group announcements</span>
            </label>
            <p class="mt-1 text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Uncheck this to stop receiving email notifications from your study groups</p>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                Save
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gruvbox-light-green dark:text-gruvbox-dark-green">
                    Saved.
                </p>
            @endif
        </div>
    </form>
</section>
