<x-layout>
    <x-slot:title>
        Product Ratings - {{ config('app.name') }}
    </x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8 space-y-4 sm:space-y-6 lg:space-y-8">
        <!-- Header Section -->
        <div class="space-y-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Product Ratings</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">
                    View and manage all product ratings from customers
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fa-solid fa-arrow-left flex-shrink-0"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.products.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-3 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fa-solid fa-box flex-shrink-0"></i>
                    <span>Products</span>
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
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

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 font-medium">Total Ratings</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_ratings']) }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-2 sm:p-3">
                        <i class="fa-solid fa-star text-blue-600 text-sm sm:text-base"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 font-medium">Average Rating</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">{{ $stats['average_rating'] }}/5</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-2 sm:p-3">
                        <i class="fa-solid fa-chart-line text-yellow-600 text-sm sm:text-base"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 font-medium">With Feedback</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['ratings_with_feedback']) }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-2 sm:p-3">
                        <i class="fa-solid fa-comment text-green-600 text-sm sm:text-base"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 font-medium">Today</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['ratings_today']) }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-2 sm:p-3">
                        <i class="fa-solid fa-calendar-day text-purple-600 text-sm sm:text-base"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-100 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="inline-flex items-center justify-center rounded-full bg-yellow-100 text-yellow-600 w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                        <i class="fa-solid fa-star text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">All Ratings</h2>
                        <p class="text-xs sm:text-sm text-gray-500">
                            Showing {{ $ratings->count() }} of {{ $ratings->total() }} ratings
                        </p>
                    </div>
                </div>

                <!-- Filters Form -->
                <form method="GET" action="{{ route('admin.ratings.index') }}" class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-2 sm:gap-3">
                    <select name="product_id"
                        class="rounded-lg border border-gray-300 bg-white text-gray-700 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-300 px-2 py-2">
                        <option value="">All Products</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(($filters['product_id'] ?? null) == $product->id)>
                                {{ Str::limit($product->name, 25) }}
                            </option>
                        @endforeach
                    </select>

                    <select name="rating"
                        class="rounded-lg border border-gray-300 bg-white text-gray-700 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-300 px-2 py-2">
                        <option value="">All Ratings</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected(($filters['rating'] ?? null) == $i)>
                                {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                            </option>
                        @endfor
                    </select>

                    <input type="date" name="from_date" value="{{ $filters['from_date'] ?? '' }}"
                        class="rounded-lg border border-gray-300 bg-white text-gray-700 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-300 px-2 py-2"
                        placeholder="From Date">

                    <input type="date" name="to_date" value="{{ $filters['to_date'] ?? '' }}"
                        class="rounded-lg border border-gray-300 bg-white text-gray-700 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-300 px-2 py-2"
                        placeholder="To Date">

                    <div class="flex gap-2 col-span-2 sm:col-span-1">
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fa-solid fa-filter text-xs mr-1"></i>
                            Filter
                        </button>
                        <a href="{{ route('admin.ratings.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                            <i class="fa-solid fa-times text-xs"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">User</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Product</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Rating</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Feedback</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Date</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($ratings as $rating)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 lg:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($rating->user?->profile_picture)
                                            <img src="{{ $rating->user->profile_picture_url ?? $rating->user->profile_picture }}" 
                                                alt="{{ $rating->user->name }}"
                                                class="h-10 w-10 rounded-full object-cover flex-shrink-0">
                                        @else
                                            <div class="h-10 w-10 flex items-center justify-center rounded-full bg-blue-500 text-white font-semibold flex-shrink-0">
                                                {{ strtoupper(substr($rating->user?->name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $rating->user?->name ?? 'Deleted User' }}
                                            </p>
                                            <p class="text-xs text-gray-500 truncate">
                                                {{ $rating->user?->email ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate max-w-[200px]">
                                            {{ $rating->product?->name ?? 'Deleted Product' }}
                                        </p>
                                        @if ($rating->product)
                                            <p class="text-xs text-gray-500">
                                                €{{ number_format($rating->product->price, 2) }}
                                            </p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fa-solid fa-star text-sm {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="ml-1 text-sm font-medium text-gray-700">{{ $rating->rating }}/5</span>
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4">
                                    @if ($rating->feedback)
                                        <p class="text-sm text-gray-600 max-w-[250px] truncate" title="{{ $rating->feedback }}">
                                            {{ $rating->feedback }}
                                        </p>
                                    @else
                                        <span class="text-xs text-gray-400 italic">No feedback</span>
                                    @endif
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="hidden lg:inline">{{ $rating->created_at->format('M j, Y H:i') }}</span>
                                    <span class="lg:hidden">{{ $rating->created_at->format('M j, Y') }}</span>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-right">
                                    <form action="{{ route('admin.ratings.destroy', $rating) }}"
                                        method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this rating? This will update the product\'s average rating.');">
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
                                            <i class="fa-solid fa-star text-lg"></i>
                                        </div>
                                        <p class="font-semibold text-gray-700">No ratings found</p>
                                        <p class="text-sm text-gray-500 max-w-md">
                                            @if (array_filter($filters))
                                                Try adjusting your filters to see more results.
                                            @else
                                                Once customers rate products, their ratings will appear here.
                                            @endif
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
                @forelse ($ratings as $rating)
                    <div class="p-4 space-y-3 bg-white">
                        <!-- User & Rating -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                @if ($rating->user?->profile_picture)
                                    <img src="{{ $rating->user->profile_picture_url ?? $rating->user->profile_picture }}" 
                                        alt="{{ $rating->user->name }}"
                                        class="h-10 w-10 rounded-full object-cover flex-shrink-0">
                                @else
                                    <div class="h-10 w-10 flex items-center justify-center rounded-full bg-blue-500 text-white font-semibold flex-shrink-0">
                                        {{ strtoupper(substr($rating->user?->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $rating->user?->name ?? 'Deleted User' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $rating->created_at->format('M j, Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star text-sm {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                        </div>

                        <!-- Product -->
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500 mb-1">Product</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $rating->product?->name ?? 'Deleted Product' }}
                            </p>
                        </div>

                        <!-- Feedback -->
                        @if ($rating->feedback)
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Feedback</p>
                                <p class="text-sm text-gray-700">{{ $rating->feedback }}</p>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="pt-2">
                            <form action="{{ route('admin.ratings.destroy', $rating) }}" method="POST"
                                class="w-full"
                                onsubmit="return confirm('Are you sure you want to delete this rating?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-md border border-red-300 bg-white px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                    <i class="fa-solid fa-trash"></i>
                                    Delete Rating
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-12 text-center text-sm text-gray-500">
                        <div class="flex flex-col items-center gap-3">
                            <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-star text-lg"></i>
                            </div>
                            <p class="font-semibold text-gray-700">No ratings found</p>
                            <p class="text-sm text-gray-500 max-w-md px-4">
                                @if (array_filter($filters))
                                    Try adjusting your filters to see more results.
                                @else
                                    Once customers rate products, their ratings will appear here.
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($ratings->hasPages())
                <div class="border-t border-gray-100 px-4 sm:px-6 py-4">
                    {{ $ratings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layout>
