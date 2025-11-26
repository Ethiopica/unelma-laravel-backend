<x-layout>
    <x-slot:title>
        Favorites Management - Admin
    </x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <div>
            <div class="flex items-center text-sm text-gray-500 gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
                <span>/</span>
                <span class="text-gray-900 font-semibold">Favorites</span>
            </div>
            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Favorites Overview</h1>
                    <p class="text-gray-600 text-sm">Track user favorites across blogs, services, and products.</p>
                </div>
                <form method="GET" class="flex items-center gap-3">
                    <div class="relative flex-1 min-w-[220px]">
                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Search by user name or email"
                            class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        />
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button type="submit"
                        class="rounded-xl bg-blue-600 text-white px-4 py-2.5 text-sm font-semibold hover:bg-blue-700 focus:ring-2 focus:ring-blue-500/50">
                        Search
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                <p class="text-xs uppercase text-gray-500 font-semibold">Total Favorites</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                <p class="text-xs uppercase text-gray-500 font-semibold">Blog Favorites</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats[\App\Models\Favorite::TYPE_BLOG]) }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                <p class="text-xs uppercase text-gray-500 font-semibold">Service Favorites</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats[\App\Models\Favorite::TYPE_SERVICE]) }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                <p class="text-xs uppercase text-gray-500 font-semibold">Product Favorites</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats[\App\Models\Favorite::TYPE_PRODUCT]) }}</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="px-4 py-4 border-b border-gray-100 flex flex-wrap gap-2">
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

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Content</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Favorited At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($favorites as $favorite)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-900">{{ $favorite->user?->name ?? 'Unknown User' }}</span>
                                        <span class="text-xs text-gray-500">{{ $favorite->user?->email ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                        {{ ucfirst($favorite->favorite_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $content = $contentLookups[$favorite->favorite_type][$favorite->item_id] ?? 'Unavailable';
                                    @endphp
                                    <span class="text-sm font-medium text-gray-900">{{ $content }}</span>
                                    <p class="text-xs text-gray-500">ID: {{ $favorite->item_id }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $favorite->created_at?->format('M j, Y g:i A') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                    <i class="fa-solid fa-heart-circle-exclamation text-3xl text-gray-300 mb-3"></i>
                                    <p class="text-sm font-medium">No favorites found.</p>
                                    <p class="text-xs text-gray-400">Favorites will appear here as users mark content.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-4 border-t border-gray-100">
                {{ $favorites->links() }}
            </div>
        </div>
    </div>
</x-layout>






