<section>
    <header>
        <h2 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Update Password
        </h2>

        <p class="mt-1 text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                   class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0" />
            @if($errors->updatePassword->has('current_password'))
                <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">New Password</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                   class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0" />
            @if($errors->updatePassword->has('password'))
                <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                   class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0" />
            @if($errors->updatePassword->has('password_confirmation'))
                <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                Save
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gruvbox-light-green dark:text-gruvbox-dark-green">
                    Saved.
                </p>
            @endif
        </div>
    </form>
</section>
