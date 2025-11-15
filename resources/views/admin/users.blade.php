<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Manage Users
        </h2>
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
            ← Back to Admin Dashboard
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-gruvbox-light-red/20 dark:bg-gruvbox-dark-red/20 border border-gruvbox-light-red dark:border-gruvbox-dark-red">
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="space-y-4">
        @forelse($users as $user)
            <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-4">
                        <img src="https://api.dicebear.com/7.x/bottts/svg?seed={{ urlencode($user->avatar_seed ?: $user->name) }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full">
                        <div>
                            <h3 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $user->name }}</h3>
                            <p class="text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">{{ $user->email }}</p>
                            <div class="mt-1 flex items-center gap-2">
                                <span class="px-2 py-1 rounded text-xs {{ $user->role === 'admin' ? 'bg-gruvbox-light-red/20 text-gruvbox-light-red' : 'bg-gruvbox-light-blue/20 text-gruvbox-light-blue' }}">{{ ucfirst($user->role) }}</span>
                                <span class="px-2 py-1 rounded text-xs {{ $user->is_active ? 'bg-gruvbox-light-green/20 text-gruvbox-light-green' : 'bg-gruvbox-light-fg4/20 text-gruvbox-light-fg4' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('admin.users.toggle-role', $user) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity text-sm">
                                Toggle Role
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gruvbox-light-orange dark:bg-gruvbox-dark-orange text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity text-sm">
                                Toggle Status
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.delete', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-gruvbox-light-red dark:bg-gruvbox-dark-red text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity text-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 py-8">No users found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</x-app-layout>
