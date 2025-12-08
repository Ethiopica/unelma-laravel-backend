<x-layout>
    <x-slot:title>
        Favorites Management - Admin
    </x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8 space-y-4 sm:space-y-6 lg:space-y-8">
        <!-- Header Section - Mobile First -->
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Favorites Overview</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Track user favorites across blogs, services, and products.</p>
            </div>
            <form method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                <div class="relative flex-1 sm:min-w-[220px]">
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Search by user name or email"
                        class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    />
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fa-solid fa-search text-xs"></i>
                    Search
                </button>
            </form>
        </div>

        <!-- Summary Cards - Mobile First Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-blue-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Total</p>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-heart text-blue-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-green-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Blog</p>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-blog text-green-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($stats[\App\Models\Favorite::TYPE_BLOG]) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-purple-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Service</p>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-briefcase text-purple-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($stats[\App\Models\Favorite::TYPE_SERVICE]) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-amber-200 p-4 sm:p-5 col-span-2 md:col-span-1">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Product</p>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-box text-amber-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($stats[\App\Models\Favorite::TYPE_PRODUCT]) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <!-- Header with Filter - Mobile First -->
            <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="inline-flex items-center justify-center rounded-full bg-blue-100 text-blue-600 w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                        <i class="fa-solid fa-heart text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">All Favorites</h2>
                        <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">User favorites across all content types.</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.favorites.index') }}"
                        class="px-3 py-1.5 rounded-full text-sm font-semibold {{ !$filterType ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        All
                    </a>
                    @foreach ($validTypes as $type)
                        <a href="{{ route('admin.favorites.index', array_merge(request()->query(), ['type' => $type])) }}"
                            class="px-3 py-1.5 rounded-full text-sm font-semibold {{ $filterType === $type ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            {{ ucfirst($type) }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">User</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Type</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Content</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Favorited At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($favorites as $favorite)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-900">{{ $favorite->user?->name ?? 'Unknown User' }}</span>
                                        <span class="text-xs text-gray-500 truncate max-w-xs">{{ $favorite->user?->email ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 favorite-type">
                                        {{ ucfirst($favorite->favorite_type) }}
                                    </span>
                                </td>
                                <td class="px-4 lg:px-6 py-4">
                                    @php
                                        $content = $contentLookups[$favorite->favorite_type][$favorite->item_id] ?? 'Unavailable';
                                    @endphp
                                    <span class="text-sm font-medium text-gray-900 truncate block max-w-xs">{{ $content }}</span>
                                    <p class="text-xs text-gray-500">ID: {{ $favorite->item_id }}</p>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="hidden lg:inline">{{ $favorite->created_at?->format('M j, Y g:i A') ?? '—' }}</span>
                                    <span class="lg:hidden">{{ $favorite->created_at?->format('M j, Y') ?? '—' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 lg:px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-heart-circle-exclamation text-lg"></i>
                                        </div>
                                        <p class="font-semibold text-gray-700">No favorites found</p>
                                        <p class="text-sm text-gray-500 max-w-sm px-4">
                                            Favorites will appear here as users mark content.
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
                @forelse ($favorites as $favorite)
                    <div class="p-4 space-y-3 bg-white">
                        <!-- User Info -->
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 flex items-center justify-center rounded-full bg-blue-500 text-white font-semibold flex-shrink-0">
                                {{ strtoupper(substr($favorite->user?->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $favorite->user?->name ?? 'Unknown User' }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">{{ $favorite->user?->email ?? '—' }}</p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 flex-shrink-0 favorite-type">
                                {{ ucfirst($favorite->favorite_type) }}
                            </span>
                        </div>

                        <!-- Content Details -->
                        <div>
                            @php
                                $content = $contentLookups[$favorite->favorite_type][$favorite->item_id] ?? 'Unavailable';
                            @endphp
                            <p class="text-xs text-gray-500 mb-1">Content</p>
                            <p class="text-sm font-medium text-gray-900">{{ $content }}</p>
                            <p class="text-xs text-gray-500 mt-1">ID: {{ $favorite->item_id }}</p>
                        </div>

                        <!-- Date -->
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Favorited At</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $favorite->created_at?->format('M j, Y g:i A') ?? '—' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-3">
                            <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-heart-circle-exclamation text-lg"></i>
                            </div>
                            <p class="font-semibold text-gray-700">No favorites found</p>
                            <p class="text-sm text-gray-500 max-w-sm px-4">
                                Favorites will appear here as users mark content.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="px-4 sm:px-6 py-4 border-t border-gray-100">
                {{ $favorites->links() }}
            </div>
        </div>
    </div>
</x-layout>






