<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Delete Account
        </h2>

        <p class="mt-1 text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </p>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="px-4 py-2 bg-gruvbox-light-red dark:bg-gruvbox-dark-red text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
        Delete Account
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                Are you sure you want to delete your account?
            </h2>

            <p class="mt-3 text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">Password</label>
                <input id="password" name="password" type="password" placeholder="Password"
                       class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0" />
                @if($errors->userDeletion->has('password'))
                    <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                        class="px-4 py-2 bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 rounded-lg hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors font-semibold">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-gruvbox-light-red dark:bg-gruvbox-dark-red text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                    Delete Account
                </button>
            </div>
        </form>
    </x-modal>
</section>
