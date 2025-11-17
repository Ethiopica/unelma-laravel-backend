<x-layout>
    <x-slot:title>
        Products | Unelma
    </x-slot:title>
    
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Products Management</h1>
                <p class="text-gray-600 mt-1">Manage product offers and listings</p>
            </div>
            <a href="{{ route('admin.products.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Add New Product</span>
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Products Grid -->
        @if ($products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-200">
                        <!-- Product Image -->
                        @if ($product->image)
                            <div class="h-48 overflow-hidden bg-gray-200">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover">
                            </div>
                        @elseif ($product->image_url)
                        <div class="h-48 overflow-hidden bg-gray-200">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover">
                        </div>
                        @else
                            <div
                                class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Product Info -->
                        <div class="p-5">
                            <!-- Badges -->
                            <div class="flex items-center space-x-2 mb-3">
                                @if ($product->is_featured)
                                    <span
                                        class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                        ⭐ Featured
                                    </span>
                                @endif
                                @if ($product->is_active)
                                    <span
                                        class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                        Inactive
                                    </span>
                                @endif
                            </div>

                            <!-- Product Name -->
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $product->name }}</h3>

                            <!-- Description -->
                            @if ($product->description)
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                    {{ $product->description }}
                                </p>
                            @endif

                            <!-- Price & Order -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <span class="text-xs text-gray-500">Price</span>
                                    <p class="text-2xl font-bold text-blue-600">
                                        €{{ number_format($product->price, 2) }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-gray-500">Order</span>
                                    <p class="text-lg font-semibold text-gray-700">{{ $product->order }}</p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="text-blue-600 hover:text-blue-800 font-medium flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    <span>Edit</span>
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                    class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-800 font-medium flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium uppercase">Total Products</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $products->count() }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium uppercase">Active</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">
                                {{ $products->where('is_active', true)->count() }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium uppercase">Featured</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">
                                {{ $products->where('is_featured', true)->count() }}</p>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium uppercase">Inactive</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">
                                {{ $products->where('is_active', false)->count() }}</p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-700 mb-2">No Products Yet</h3>
                <p class="text-gray-500 mb-6">Start by creating your first product offer</p>
                <a href="{{ route('admin.products.create') }}"
                    class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-200 space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Create First Product</span>
                </a>
            </div>
        @endif
    </div>
</x-layout>
