<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Manage Reports
        </h2>
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
            ← Back to Admin Dashboard
        </a>
    </div>

    <div class="space-y-4">
        @forelse($reports as $report)
            <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ ucfirst($report->reportable_type) }} Report</h3>
                        <p class="text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mt-1">{{ $report->reason }}</p>
                        <div class="mt-3 flex items-center gap-4 text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                            <span>Reported by: {{ $report->reporter->name }}</span>
                            <span>Status: <span class="px-2 py-1 rounded text-xs {{ $report->status === 'resolved' ? 'bg-gruvbox-light-green/20 text-gruvbox-light-green' : ($report->status === 'dismissed' ? 'bg-gruvbox-light-fg4/20 text-gruvbox-light-fg4' : 'bg-gruvbox-light-yellow/20 text-gruvbox-light-yellow') }}">{{ ucfirst($report->status) }}</span></span>
                        </div>
                    </div>
                    @if($report->status === 'pending')
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.reports.resolve', $report) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-gruvbox-light-green dark:bg-gruvbox-dark-green text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity">
                                    Resolve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.reports.dismiss', $report) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-gruvbox-light-fg4 dark:bg-gruvbox-dark-fg4 text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity">
                                    Dismiss
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-center text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 py-8">No reports found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $reports->links() }}
    </div>
</x-app-layout>
