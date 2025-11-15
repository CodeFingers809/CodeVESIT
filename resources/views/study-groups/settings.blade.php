<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <a href="{{ route('study-groups.show', $studyGroup) }}" class="text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
                    {{ $studyGroup->name }}
                </a>
                <span class="text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">/</span>
                <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                    Settings
                </h2>
            </div>
        </div>
    </x-slot>

    @include('study-groups.partials.navigation-tabs')

    @if($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-gruvbox-light-red/20 dark:bg-gruvbox-dark-red/20 border border-gruvbox-light-red dark:border-gruvbox-dark-red">
            <ul class="list-disc list-inside text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="space-y-6">
        <!-- Join Code (Moderators Only) -->
        @if($isModerator)
            <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                <h3 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Invite Members</h3>
                <div class="p-4 rounded-lg bg-gruvbox-light-yellow/20 dark:bg-gruvbox-dark-yellow/20 border border-gruvbox-light-yellow dark:border-gruvbox-dark-yellow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">Join Code (Share with classmates)</p>
                            <p class="mt-1 text-2xl font-bold font-mono text-gruvbox-light-yellow dark:text-gruvbox-dark-yellow">{{ $studyGroup->join_code }}</p>
                        </div>
                        <button onclick="navigator.clipboard.writeText('{{ $studyGroup->join_code }}')" class="px-4 py-2 bg-gruvbox-light-yellow dark:bg-gruvbox-dark-yellow text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity">
                            Copy
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Members List -->
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <h3 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Members ({{ $members->count() }})</h3>
            <div class="space-y-2">
                @foreach($members as $member)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2">
                        <div class="flex items-center gap-3">
                            <img src="https://api.dicebear.com/7.x/bottts/svg?seed={{ urlencode($member->avatar_seed ?: $member->name) }}"
                                 alt="{{ $member->name }}"
                                 class="w-10 h-10 rounded-full border-2 border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                            <div>
                                <p class="font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                                    {{ $member->name }}
                                    @if($member->id === $studyGroup->created_by)
                                        <span class="ml-2 px-2 py-0.5 text-xs rounded bg-gruvbox-light-blue/20 text-gruvbox-light-blue dark:bg-gruvbox-dark-blue/20 dark:text-gruvbox-dark-blue">Creator</span>
                                    @elseif($moderators->contains($member->id))
                                        <span class="ml-2 px-2 py-0.5 text-xs rounded bg-gruvbox-light-green/20 text-gruvbox-light-green dark:bg-gruvbox-dark-green/20 dark:text-gruvbox-dark-green">Moderator</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">{{ $member->email }}</p>
                            </div>
                        </div>
                        @if($isCreator && $member->id !== $studyGroup->created_by)
                            <div class="flex gap-2">
                                @if(!$moderators->contains($member->id))
                                    <form method="POST" action="{{ route('study-groups.moderators.add', $studyGroup) }}">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $member->id }}">
                                        <button type="submit" class="px-3 py-1 text-sm bg-gruvbox-light-green dark:bg-gruvbox-dark-green text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded hover:opacity-90 transition-opacity">
                                            Make Moderator
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('study-groups.moderators.remove', [$studyGroup, $member->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 text-sm bg-gruvbox-light-red dark:bg-gruvbox-dark-red text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded hover:opacity-90 transition-opacity">
                                            Remove Moderator
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Group Information -->
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <h3 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Group Information</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Name</span>
                    <span class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $studyGroup->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Status</span>
                    <span class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                        {{ ucfirst($studyGroup->status) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Created</span>
                    <span class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $studyGroup->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Total Members</span>
                    <span class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $members->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Moderators</span>
                    <span class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $moderators->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Danger Zone (Creator Only) -->
        @if(!$isCreator && $studyGroup->created_by !== auth()->id())
            <div class="p-6 rounded-lg bg-gruvbox-light-red/20 dark:bg-gruvbox-dark-red/20 border border-gruvbox-light-red dark:border-gruvbox-dark-red">
                <h3 class="text-lg font-semibold text-gruvbox-light-red dark:text-gruvbox-dark-red mb-4">Danger Zone</h3>
                <p class="text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-4">Once you leave this group, you will need the join code to rejoin.</p>
                <form method="POST" action="{{ route('study-groups.leave', $studyGroup) }}">
                    @csrf
                    <button type="submit" onclick="return confirm('Are you sure you want to leave this group?')" class="px-4 py-2 bg-gruvbox-light-red dark:bg-gruvbox-dark-red text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-medium">
                        Leave Group
                    </button>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>
