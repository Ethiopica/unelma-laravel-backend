<x-layout>
    <x-slot:title>
        Newsletter Subscribers - {{ config('app.name') }}
    </x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8 space-y-4 sm:space-y-6 lg:space-y-8">
        <!-- Header Section - Mobile First -->
        <div class="space-y-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Newsletter Subscribers</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1 truncate">
                    Live list synced from Unelma Mail
                </p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 sm:w-auto gap-2">
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fa-solid fa-arrow-left flex-shrink-0"></i>
                    <span>Dashboard</span>
                </a>
                <a href="https://core.unelmamail.com/" target="_blank"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-3 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fa-solid fa-up-right-from-square text-xs flex-shrink-0"></i>
                    <span>Unelma Mail</span>
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <div class="flex items-center">
                    <i class="fa-solid fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <div class="flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if ($error)
            <div class="bg-red-100 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                    <div>
                        <p class="font-semibold">Unable to load subscribers</p>
                        <p class="mt-1 text-sm">{{ $error }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-100 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="inline-flex items-center justify-center rounded-full bg-cyan-100 text-cyan-600 w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                        <i class="fa-solid fa-envelope-open-text text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Mailing List Subscribers</h2>
                        <p class="text-xs sm:text-sm text-gray-500">
                            Showing {{ $subscribers->count() }} contacts{{ $meta ? ' (page '.$meta['current_page'].' of '.$meta['last_page'].')' : '' }}.
                        </p>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.subscribers.index') }}" class="flex items-center gap-2">
                    <select id="status" name="status"
                        class="flex-1 min-w-0 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-300 px-2 py-2">
                        <option value="">All statuses</option>
                        <option value="subscribed" @selected(($filters['status'] ?? null) === 'subscribed')>Subscribed</option>
                        <option value="unconfirmed" @selected(($filters['status'] ?? null) === 'unconfirmed')>Unconfirmed</option>
                        <option value="unsubscribed" @selected(($filters['status'] ?? null) === 'unsubscribed')>Unsubscribed</option>
                        <option value="bounced" @selected(($filters['status'] ?? null) === 'bounced')>Bounced</option>
                    </select>
                    <select id="per_page" name="per_page"
                        class="w-16 flex-shrink-0 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-300 px-1 py-2">
                        @foreach ([10, 20, 50, 100] as $size)
                            <option value="{{ $size }}" @selected(($filters['per_page'] ?? 20) == $size)>{{ $size }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="flex-shrink-0 inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fa-solid fa-filter text-xs"></i>
                    </button>
                </form>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Subscriber</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Subscription Date</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Source</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Tags</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($subscribers as $subscriber)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 lg:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 flex items-center justify-center rounded-full bg-cyan-500 text-white font-semibold flex-shrink-0">
                                            {{ strtoupper(substr($subscriber['first_name'] ?? $subscriber['email'], 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">
                                                {{ trim(($subscriber['first_name'] ?? '') . ' ' . ($subscriber['last_name'] ?? '')) ?: $subscriber['email'] }}
                                            </p>
                                            <p class="text-sm text-gray-600 truncate">{{ $subscriber['email'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    @php
                                        $status = strtolower($subscriber['status'] ?? 'subscribed');
                                        $statusColors = [
                                            'subscribed' => 'bg-green-100 text-green-800 border-green-200',
                                            'unconfirmed' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'unsubscribed' => 'bg-gray-100 text-gray-800 border-gray-200',
                                            'bounced' => 'bg-red-100 text-red-800 border-red-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border status-badge {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="hidden lg:inline">{{ optional($subscriber['created_at'])->format('M j, Y') ?? '—' }}</span>
                                    <span class="lg:hidden">{{ optional($subscriber['created_at'])->format('M j') ?? '—' }}</span>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $subscriber['source'] ?? 'API' }}
                                </td>
                                <td class="px-4 lg:px-6 py-4">
                                    <div class="flex justify-end flex-wrap gap-2">
                                        @forelse ($subscriber['tags'] ?? [] as $tag)
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800 border border-blue-200">
                                                {{ $tag }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400">No tags</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-right">
                                    <form action="{{ route('admin.subscribers.destroy', $subscriber['id']) }}"
                                        method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this subscriber? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center rounded-md border border-red-300 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50 whitespace-nowrap">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                            <span class="hidden lg:inline ml-1">Delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 lg:px-6 py-12 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-envelope-circle-check text-lg"></i>
                                        </div>
                                        <p class="font-semibold text-gray-700">No subscribers found</p>
                                        <p class="text-sm text-gray-500 max-w-md">
                                            Once visitors subscribe through your frontend form, they will appear here automatically.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden divide-y divide-gray-200">
                @forelse ($subscribers as $subscriber)
                    <div class="p-4 space-y-3 bg-white">
                        <!-- Subscriber Info -->
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 flex items-center justify-center rounded-full bg-cyan-500 text-white font-semibold flex-shrink-0">
                                {{ strtoupper(substr($subscriber['first_name'] ?? $subscriber['email'], 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    {{ trim(($subscriber['first_name'] ?? '') . ' ' . ($subscriber['last_name'] ?? '')) ?: $subscriber['email'] }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">{{ $subscriber['email'] }}</p>
                            </div>
                            @php
                                $status = strtolower($subscriber['status'] ?? 'subscribed');
                                $statusColors = [
                                    'subscribed' => 'bg-green-100 text-green-800 border-green-200',
                                    'unconfirmed' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'unsubscribed' => 'bg-gray-100 text-gray-800 border-gray-200',
                                    'bounced' => 'bg-red-100 text-red-800 border-red-200',
                                ];
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold border flex-shrink-0 status-badge {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                {{ ucfirst($status) }}
                            </span>
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Subscription Date</p>
                                <p class="font-medium text-gray-900">
                                    {{ optional($subscriber['created_at'])->format('M j, Y') ?? '—' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Source</p>
                                <p class="font-medium text-gray-900">{{ $subscriber['source'] ?? 'API' }}</p>
                            </div>
                        </div>

                        <!-- Tags -->
                        @if (!empty($subscriber['tags'] ?? []))
                            <div>
                                <p class="text-xs text-gray-500 mb-2">Tags</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($subscriber['tags'] as $tag)
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-800 border border-blue-200">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="pt-2">
                            <form action="{{ route('admin.subscribers.destroy', $subscriber['id']) }}" method="POST"
                                class="w-full"
                                onsubmit="return confirm('Are you sure you want to delete this subscriber? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-md border border-red-300 bg-white px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                    <i class="fa-solid fa-trash"></i>
                                    Delete Subscriber
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-12 text-center text-sm text-gray-500">
                        <div class="flex flex-col items-center gap-3">
                            <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-envelope-circle-check text-lg"></i>
                            </div>
                            <p class="font-semibold text-gray-700">No subscribers found</p>
                            <p class="text-sm text-gray-500 max-w-md px-4">
                                Once visitors subscribe through your frontend form, they will appear here automatically.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($meta)
                <div class="border-t border-gray-100 px-4 sm:px-6 py-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between text-sm text-gray-600">
                    <p>
                        Showing
                        <span class="font-semibold text-gray-900">{{ $subscribers->count() }}</span>
                        of
                        <span class="font-semibold text-gray-900">{{ number_format($meta['total']) }}</span>
                        subscribers.
                    </p>
                    <div class="flex items-center gap-3">
                        <span>Page {{ $meta['current_page'] }} / {{ $meta['last_page'] }}</span>
                        <div class="flex items-center gap-1">
                            <a href="{{ request()->fullUrlWithQuery(['page' => max(1, $meta['current_page'] - 1)]) }}"
                                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 {{ $meta['current_page'] <= 1 ? 'pointer-events-none opacity-50' : '' }}">
                                Prev
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['page' => min($meta['last_page'], $meta['current_page'] + 1)]) }}"
                                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 {{ $meta['current_page'] >= $meta['last_page'] ? 'pointer-events-none opacity-50' : '' }}">
                                Next
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout>







