<x-layout>
    <x-slot:title>
        Product Ratings | Unelma Admin
    </x-slot:title>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">Product Ratings</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">View and manage customer ratings</p>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Stats Cards - Mobile First Grid -->
        <div class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4 mb-6">
            <!-- Total Ratings -->
            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase truncate">Total</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_ratings'] }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-2 sm:p-3 flex-shrink-0 ml-2">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Average Rating -->
            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase truncate">Average</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mt-1">{{ $stats['average_rating'] }}</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-2 sm:p-3 flex-shrink-0 ml-2">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- 5 Star -->
            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase truncate">5 Star</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-600 mt-1">{{ $stats['five_star'] }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-2 sm:p-3 flex-shrink-0 ml-2">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- 1 Star -->
            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase truncate">1 Star</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-red-600 mt-1">{{ $stats['one_star'] }}</p>
                    </div>
                    <div class="bg-red-100 rounded-full p-2 sm:p-3 flex-shrink-0 ml-2">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-7.536 5.879a1 1 0 001.415 0 3 3 0 014.242 0 1 1 0 001.415-1.415 5 5 0 00-7.072 0 1 1 0 000 1.415z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters - Mobile First -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('admin.ratings.index') }}" class="space-y-4 sm:space-y-0 sm:flex sm:flex-wrap sm:gap-4 sm:items-end">
                <!-- Product Filter -->
                <div class="w-full sm:flex-1 sm:min-w-[200px]">
                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Product</label>
                    <select name="product_id" id="product_id" class="w-full px-3 py-2 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">All Products</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Rating Filter -->
                <div class="w-full sm:w-32">
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <select name="rating" id="rating" class="w-full px-3 py-2 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">All</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="submit" class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 text-sm font-medium">
                        Filter
                    </button>
                    <a href="{{ route('admin.ratings.index') }}" class="flex-1 sm:flex-none text-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-200 text-sm font-medium">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Ratings List -->
        @if ($ratings->count() > 0)
            <!-- Mobile Card View (visible on small screens) -->
            <div class="block lg:hidden space-y-4">
                @foreach ($ratings as $rating)
                    <div class="bg-white rounded-lg shadow p-4">
                        <!-- Header: Product & Rating -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center min-w-0 flex-1">
                                @if ($rating->product && $rating->product->image_local_url)
                                    <img src="{{ $rating->product->image_local_url }}" alt="{{ $rating->product->name }}"
                                        class="h-12 w-12 rounded-lg object-cover mr-3 flex-shrink-0">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center mr-3 flex-shrink-0">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $rating->product->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $rating->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                            <!-- Star Rating Badge -->
                            <div class="flex items-center bg-yellow-50 px-2 py-1 rounded-full ml-2 flex-shrink-0">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-sm font-semibold text-yellow-700 ml-1">{{ $rating->rating }}</span>
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="flex items-center mb-3 pb-3 border-b border-gray-100">
                            @if ($rating->user && $rating->user->profile_picture)
                                <img src="{{ asset('storage/' . $rating->user->profile_picture) }}" 
                                    alt="{{ $rating->user->name }}"
                                    class="h-8 w-8 rounded-full object-cover mr-2 flex-shrink-0">
                            @else
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-2 flex-shrink-0">
                                    <span class="text-blue-600 text-xs font-bold">
                                        {{ $rating->user ? strtoupper(substr($rating->user->name, 0, 1)) : '?' }}
                                    </span>
                                </div>
                            @endif
                            <span class="text-sm text-gray-700">{{ $rating->user->name ?? 'Unknown User' }}</span>
                        </div>

                        <!-- Stars Display -->
                        <div class="flex items-center mb-3">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>

                        <!-- Feedback -->
                        @if ($rating->feedback)
                            <div class="mb-3">
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $rating->feedback }}</p>
                            </div>
                        @endif

                        <!-- Delete Action -->
                        <div class="pt-3 border-t border-gray-100">
                            <form method="POST" action="{{ route('admin.ratings.destroy', $rating) }}"
                                onsubmit="return confirm('Are you sure you want to delete this rating?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full sm:w-auto flex items-center justify-center gap-2 text-red-600 hover:text-red-800 hover:bg-red-50 font-medium text-sm px-3 py-2 rounded-lg transition duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span>Delete Rating</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop Table View (visible on large screens) -->
            <div class="hidden lg:block bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rating
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Feedback
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($ratings as $rating)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if ($rating->product && $rating->product->image_local_url)
                                                <img src="{{ $rating->product->image_local_url }}" alt="{{ $rating->product->name }}"
                                                    class="h-10 w-10 rounded-lg object-cover mr-3">
                                            @else
                                                <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center mr-3">
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $rating->product->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if ($rating->user && $rating->user->profile_picture)
                                                <img src="{{ asset('storage/' . $rating->user->profile_picture) }}" 
                                                    alt="{{ $rating->user->name }}"
                                                    class="h-8 w-8 rounded-full object-cover mr-2">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-2">
                                                    <span class="text-blue-600 text-xs font-bold">
                                                        {{ $rating->user ? strtoupper(substr($rating->user->name, 0, 1)) : '?' }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="text-sm text-gray-900">
                                                {{ $rating->user->name ?? 'Unknown User' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-600">({{ $rating->rating }})</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600 max-w-xs truncate">
                                            {{ $rating->feedback ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $rating->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form method="POST" action="{{ route('admin.ratings.destroy', $rating) }}"
                                            class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this rating?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 font-medium inline-flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if ($ratings->hasPages())
                <div class="mt-6">
                    {{ $ratings->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow p-8 sm:p-12 text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-700 mb-2">No Ratings Yet</h3>
                <p class="text-sm sm:text-base text-gray-500">Customer ratings will appear here once they start reviewing products.</p>
            </div>
        @endif
    </div>
</x-layout>

























