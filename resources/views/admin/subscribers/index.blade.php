<x-layout>
    <x-slot:title>
        Newsletter Subscribers - {{ config('app.name') }}
    </x-slot:title>

    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-3 sm:py-6 lg:py-8 space-y-3 sm:space-y-6">
        
        <!-- Header Section - 375px Optimized -->
        <div class="space-y-3">
            <div>
                <h1 class="text-lg sm:text-2xl font-bold text-gray-900">Subscribers</h1>
                <p class="text-xs sm:text-base text-gray-600 mt-0.5">Unelma Mail list</p>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs sm:text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Dashboard</span>
                </a>
                <a href="https://core.unelmamail.com/" target="_blank"
                    class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-blue-600 px-3 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                    <i class="fa-solid fa-external-link text-[10px]"></i>
                    <span>Unelma Mail</span>
                </a>
            </div>
        </div>

        <!-- Alert Messages - Compact -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-400 text-green-700 px-3 py-2 rounded text-xs sm:text-sm">
                <div class="flex items-center">
                    <i class="fa-solid fa-check-circle mr-2 text-xs"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-400 text-red-700 px-3 py-2 rounded text-xs sm:text-sm">
                <div class="flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-2 text-xs"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if ($error)
            <div class="bg-red-100 border-l-4 border-red-400 text-red-700 px-3 py-2 rounded text-xs sm:text-sm">
                <div class="flex items-start gap-2">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 text-xs"></i>
                    <div>
                        <p class="font-semibold text-xs">Unable to load subscribers</p>
                        <p class="mt-0.5 text-xs opacity-90">{{ $error }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Cards - 375px: 2x2 compact grid -->
        <div class="grid grid-cols-2 gap-2 sm:gap-4 md:grid-cols-4">
            <!-- Total Subscribers -->
            <div class="bg-white rounded-lg shadow-sm border border-cyan-200 p-3 sm:p-5">
                <div class="flex items-center justify-between mb-1 sm:mb-3">
                    <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500 tracking-wide">Total</p>
                    <div class="w-8 h-8 sm:w-12 sm:h-12 bg-cyan-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-users text-cyan-600 text-xs sm:text-base"></i>
                    </div>
                </div>
                <p class="text-xl sm:text-3xl font-bold text-gray-900">{{ $meta ? number_format($meta['total']) : $subscribers->count() }}</p>
                <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5">Subscribers</p>
            </div>

            <!-- Subscribed -->
            <div class="bg-white rounded-lg shadow-sm border border-green-200 p-3 sm:p-5">
                <div class="flex items-center justify-between mb-1 sm:mb-3">
                    <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500 tracking-wide">Active</p>
                    <div class="w-8 h-8 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check text-green-600 text-xs sm:text-base"></i>
                    </div>
                </div>
                <p class="text-xl sm:text-3xl font-bold text-gray-900">{{ $subscribers->where('status', 'subscribed')->count() }}</p>
                <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5">Subscribed</p>
            </div>

            <!-- Unconfirmed -->
            <div class="bg-white rounded-lg shadow-sm border border-amber-200 p-3 sm:p-5">
                <div class="flex items-center justify-between mb-1 sm:mb-3">
                    <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500 tracking-wide">Pending</p>
                    <div class="w-8 h-8 sm:w-12 sm:h-12 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-clock text-amber-600 text-xs sm:text-base"></i>
                    </div>
                </div>
                <p class="text-xl sm:text-3xl font-bold text-gray-900">{{ $subscribers->where('status', 'unconfirmed')->count() }}</p>
                <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5">Unconfirmed</p>
            </div>

            <!-- Current Page -->
            <div class="bg-white rounded-lg shadow-sm border border-purple-200 p-3 sm:p-5">
                <div class="flex items-center justify-between mb-1 sm:mb-3">
                    <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500 tracking-wide">Page</p>
                    <div class="w-8 h-8 sm:w-12 sm:h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-file text-purple-600 text-xs sm:text-base"></i>
                    </div>
                </div>
                <p class="text-xl sm:text-3xl font-bold text-gray-900">{{ $meta ? $meta['current_page'] : 1 }}<span class="text-sm sm:text-lg text-gray-400">/{{ $meta ? $meta['last_page'] : 1 }}</span></p>
                <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5">Current</p>
            </div>
        </div>

        <!-- Subscribers List Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header - 375px Optimized -->
            <div class="px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-100">
                <!-- Title Row -->
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-envelope text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-sm sm:text-lg font-semibold text-gray-900">Mailing List</h2>
                        <p class="text-[10px] sm:text-sm text-gray-500">{{ $subscribers->count() }} contacts</p>
                    </div>
                </div>

                <!-- Filter Form - 375px: Stacked compact -->
                <form method="GET" class="flex gap-1.5 sm:gap-2">
                    <select id="status" name="status"
                        class="flex-1 min-w-0 rounded-lg border border-gray-300 bg-white text-gray-700 text-xs sm:text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-300 px-2 py-1.5 sm:py-2">
                        <option value="">All</option>
                        <option value="subscribed" @selected(($filters['status'] ?? null) === 'subscribed')>Subscribed</option>
                        <option value="unconfirmed" @selected(($filters['status'] ?? null) === 'unconfirmed')>Pending</option>
                        <option value="unsubscribed" @selected(($filters['status'] ?? null) === 'unsubscribed')>Unsubscribed</option>
                        <option value="bounced" @selected(($filters['status'] ?? null) === 'bounced')>Bounced</option>
                    </select>
                    <select id="per_page" name="per_page"
                        class="w-14 sm:w-16 rounded-lg border border-gray-300 bg-white text-gray-700 text-xs sm:text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-300 px-1.5 py-1.5 sm:py-2">
                        @foreach ([10, 20, 50] as $size)
                            <option value="{{ $size }}" @selected(($filters['per_page'] ?? 20) == $size)>{{ $size }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-3 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                </form>
            </div>

            <!-- Mobile Card View - 375px Optimized -->
            <div class="lg:hidden divide-y divide-gray-100">
                @forelse ($subscribers as $subscriber)
                    <div class="p-3 sm:p-4">
                        <!-- Subscriber Header - Compact for 375px -->
                        <div class="flex items-center gap-2.5 mb-2.5">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full bg-gradient-to-br from-cyan-400 to-cyan-600 text-white font-bold text-sm sm:text-lg flex-shrink-0">
                                {{ strtoupper(substr($subscriber['first_name'] ?? $subscriber['email'], 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5 mb-0.5">
                                    <p class="text-xs sm:text-sm font-semibold text-gray-900 truncate">
                                        {{ trim(($subscriber['first_name'] ?? '') . ' ' . ($subscriber['last_name'] ?? '')) ?: 'No Name' }}
                                    </p>
                                    @php
                                        $status = strtolower($subscriber['status'] ?? 'subscribed');
                                        $statusStyles = [
                                            'subscribed' => 'bg-green-100 text-green-700',
                                            'unconfirmed' => 'bg-amber-100 text-amber-700',
                                            'unsubscribed' => 'bg-gray-100 text-gray-600',
                                            'bounced' => 'bg-red-100 text-red-700',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-1.5 py-0.5 text-[9px] sm:text-[10px] font-semibold status-badge {{ $statusStyles[$status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </div>
                                <p class="text-[10px] sm:text-xs text-gray-500 truncate">{{ $subscriber['email'] }}</p>
                            </div>
                        </div>

                        <!-- Details - Compact 2-column -->
                        <div class="grid grid-cols-2 gap-2 mb-2.5">
                            <div class="bg-gray-50 rounded-md px-2.5 py-2">
                                <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase tracking-wide mb-0.5">Date</p>
                                <p class="text-[11px] sm:text-xs font-medium text-gray-900">
                                    {{ optional($subscriber['created_at'])->format('M j, Y') ?? '—' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-md px-2.5 py-2">
                                <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase tracking-wide mb-0.5">Source</p>
                                <p class="text-[11px] sm:text-xs font-medium text-gray-900 truncate">{{ $subscriber['source'] ?? 'API' }}</p>
                            </div>
                        </div>

                        <!-- Tags - Compact -->
                        @if (!empty($subscriber['tags'] ?? []))
                            <div class="mb-2.5">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($subscriber['tags'] as $tag)
                                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-medium text-blue-700">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Delete Button - Compact -->
                        <form action="{{ route('admin.subscribers.destroy', $subscriber['id']) }}" method="POST"
                            onsubmit="return confirm('Delete this subscriber?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100 active:bg-red-200">
                                <i class="fa-solid fa-trash text-[10px]"></i>
                                Delete
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="px-3 py-10 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-envelope-circle-check text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-700 text-sm">No subscribers</p>
                                <p class="text-xs text-gray-500 mt-1 max-w-[200px] mx-auto">
                                    Subscribers will appear here once they sign up.
                                </p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Subscriber</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Source</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Tags</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($subscribers as $subscriber)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-br from-cyan-400 to-cyan-600 text-white font-semibold flex-shrink-0">
                                            {{ strtoupper(substr($subscriber['first_name'] ?? $subscriber['email'], 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">
                                                {{ trim(($subscriber['first_name'] ?? '') . ' ' . ($subscriber['last_name'] ?? '')) ?: $subscriber['email'] }}
                                            </p>
                                            <p class="text-sm text-gray-500 truncate">{{ $subscriber['email'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $status = strtolower($subscriber['status'] ?? 'subscribed');
                                        $statusStyles = [
                                            'subscribed' => 'bg-green-100 text-green-800 border-green-200',
                                            'unconfirmed' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'unsubscribed' => 'bg-gray-100 text-gray-800 border-gray-200',
                                            'bounced' => 'bg-red-100 text-red-800 border-red-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border status-badge {{ $statusStyles[$status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ optional($subscriber['created_at'])->format('M j, Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $subscriber['source'] ?? 'API' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse ($subscriber['tags'] ?? [] as $tag)
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 border border-blue-200">
                                                {{ $tag }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400">—</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <form action="{{ route('admin.subscribers.destroy', $subscriber['id']) }}"
                                        method="POST" class="inline"
                                        onsubmit="return confirm('Delete this subscriber?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-red-300 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                            <i class="fa-solid fa-trash"></i>
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-envelope-circle-check text-2xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-700">No subscribers found</p>
                                            <p class="text-sm text-gray-500 mt-1">Subscribers will appear here once they sign up.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination - 375px Optimized -->
            @if ($meta && $meta['last_page'] > 1)
                <div class="border-t border-gray-100 px-3 sm:px-6 py-3 sm:py-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                        <p class="text-[11px] sm:text-sm text-gray-600 text-center sm:text-left">
                            <span class="font-semibold">{{ $subscribers->count() }}</span> of <span class="font-semibold">{{ number_format($meta['total']) }}</span>
                        </p>
                        <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                            <a href="{{ request()->fullUrlWithQuery(['page' => max(1, $meta['current_page'] - 1)]) }}"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white w-9 h-9 sm:w-auto sm:h-auto sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold text-gray-700 transition hover:bg-gray-50 {{ $meta['current_page'] <= 1 ? 'pointer-events-none opacity-40' : '' }}">
                                <i class="fa-solid fa-chevron-left text-[10px] sm:mr-1"></i>
                                <span class="hidden sm:inline">Prev</span>
                            </a>
                            <span class="px-2.5 py-1.5 text-xs sm:text-sm font-medium text-gray-700 bg-gray-100 rounded-lg">
                                {{ $meta['current_page'] }}/{{ $meta['last_page'] }}
                            </span>
                            <a href="{{ request()->fullUrlWithQuery(['page' => min($meta['last_page'], $meta['current_page'] + 1)]) }}"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white w-9 h-9 sm:w-auto sm:h-auto sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold text-gray-700 transition hover:bg-gray-50 {{ $meta['current_page'] >= $meta['last_page'] ? 'pointer-events-none opacity-40' : '' }}">
                                <span class="hidden sm:inline">Next</span>
                                <i class="fa-solid fa-chevron-right text-[10px] sm:ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout>
