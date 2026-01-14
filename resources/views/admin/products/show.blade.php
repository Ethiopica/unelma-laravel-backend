<x-layout>
    <x-slot:title>
        {{ $product->name }} - {{ config('app.name') }}
    </x-slot:title>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.products.index') }}"
                class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Back to Products
            </a>
        </div>

        <!-- Product Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-4">
                        <h1 class="text-3xl font-bold text-gray-800">{{ $product->name }}</h1>
                        <!-- Badges -->
                        <div class="flex items-center space-x-2">
                            @if ($product->is_featured)
                                <span
                                    class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                    ⭐ Featured
                                </span>
                            @endif
                            @if ($product->is_active)
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    Active
                                </span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>

                    @if ($product->price)
                        <div class="text-3xl font-bold text-blue-600 mb-2">€{{ number_format($product->price, 2) }}
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.products.edit', $product) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        <span>Edit Product</span>
                    </a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                        onsubmit="return confirm('Are you sure you want to delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        @php
            $featuredProductNames = ['data management', 'data science', 'cloud service'];
            $isFeaturedProduct = in_array(strtolower($product->name), $featuredProductNames, true);
        @endphp

        <!-- Image and Form Side by Side -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Product Image -->
            <div class="h-full">
                <div class="bg-white rounded-lg shadow-md overflow-hidden h-full flex flex-col">
                    @php
                        // Determine image source with priority: full_image_url > image_local_url > image > image_url
                        $imageSource = null;
                        
                        // Priority 1: Use full_image_url (handles Supabase URLs correctly)
                        try {
                            $fullImageUrl = $product->getAttribute('full_image_url') ?? $product->full_image_url ?? null;
                            if (!empty($fullImageUrl)) {
                                $imageSource = $fullImageUrl;
                            }
                        } catch (\Exception $e) {
                            // Fall through to next option
                        }
                        
                        // Priority 2: Use image_local_url accessor (most reliable)
                        if (empty($imageSource)) {
                            try {
                                $localImageUrl = $product->getAttribute('image_local_url') ?? $product->image_local_url ?? null;
                                if (!empty($localImageUrl)) {
                                    $imageSource = $localImageUrl;
                                }
                            } catch (\Exception $e) {
                                // Fall through to next option
                            }
                        }
                        
                        // Priority 3: Fallback to image field - use Storage::url()
                        if (empty($imageSource) && !empty($product->image)) {
                            // Handle different image path formats
                            if (str_starts_with($product->image, 'http://') || str_starts_with($product->image, 'https://')) {
                                $imageSource = $product->image;
                            } else {
                                // Use Storage::url() - works correctly with Supabase when AWS_URL is configured
                                $disk = config('filesystems.default');
                                if (in_array($disk, ['s3', 'supabase', 'cloudinary'])) {
                                    $imageSource = \Storage::disk($disk)->url($product->image);
                                } else {
                                    // Default: prepend /storage/ for local storage
                                    $imageSource = '/storage/' . ltrim($product->image, '/');
                                }
                            }
                        }
                        
                        // Priority 4: Last resort: image_url field
                        if (empty($imageSource) && !empty($product->image_url)) {
                            $imageSource = $product->image_url;
                        }
                    @endphp

                    @if ($imageSource)
                        <div class="w-full flex-1">
                            <img src="{{ $imageSource }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="flex-1 bg-white p-12 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-24 h-24 text-gray-400 mx-auto" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                    @endif

                    @if ($isFeaturedProduct && $product->description)
                        <div class="border-t border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">About This Product</h2>
                            <p class="text-gray-600 leading-relaxed">
                                {{ $product->description }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Query Form -->
            <div id="queryForm" class="bg-white rounded-lg shadow-md p-6 h-full flex flex-col">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Have a Question? Get in Touch</h2>

                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-bold">Success!</p>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4 flex-1 flex flex-col">
                    @csrf

                    <!-- Hidden field to track product -->
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                            Your Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 @error('name') border-red-500 @enderror"
                            placeholder="Enter your full name">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">
                            Your Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 @error('email') border-red-500 @enderror"
                            placeholder="your.email@example.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Message Field -->
                    <div>
                        <label for="message" class="block text-sm font-bold text-gray-700 mb-2">
                            Your Message <span class="text-red-500">*</span>
                        </label>
                        <textarea id="message" name="message" rows="5" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 resize-none @error('message') border-red-500 @enderror"
                            placeholder="Write your message here... (minimum 10 characters)">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                            class="mt-auto w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-lg transition duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>Send Message</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if ($isFeaturedProduct)
            <!-- Pricing Cards -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Choose Your Plan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
                    <!-- Business Plan -->
                    <div
                        class="bg-white rounded-lg shadow-lg border-2 border-gray-200 hover:border-blue-500 transition duration-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6 text-center">
                            <h3 class="text-2xl font-bold mb-2">Business</h3>
                            <div class="flex items-baseline justify-center">
                                <span class="text-4xl font-bold">$99</span>
                                <span class="text-lg ml-2 opacity-90">/year</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <ul class="space-y-4 mb-6">
                                <li class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Unlimited Pages</span>
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>All Team Members</span>
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Unlimited Leads</span>
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Unlimited Page Views</span>
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Export in HTML/CSS</span>
                                </li>
                            </ul>
                            <button
                                onclick="document.getElementById('queryForm').scrollIntoView({ behavior: 'smooth' });"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-200 font-semibold">
                                Order Now
                            </button>
                        </div>
                    </div>

                    <!-- Professional Plan -->
                    <div
                        class="bg-white rounded-lg shadow-lg border-2 border-purple-500 hover:border-purple-600 transition duration-200 overflow-hidden relative">
                        <div
                            class="absolute top-0 right-0 bg-purple-600 text-white px-4 py-1 text-xs font-bold rounded-bl-lg">
                            POPULAR
                        </div>
                        <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-6 text-center">
                            <h3 class="text-2xl font-bold mb-2">Professional</h3>
                            <div class="flex items-baseline justify-center">
                                <span class="text-4xl font-bold">$199</span>
                                <span class="text-lg ml-2 opacity-90">/year</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <ul class="space-y-4 mb-6">
                                <li class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Unlimited Pages</span>
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>All Team Members</span>
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Unlimited Leads</span>
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Unlimited Page Views</span>
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Export in HTML/CSS</span>
                                </li>
                            </ul>
                            <button
                                onclick="document.getElementById('queryForm').scrollIntoView({ behavior: 'smooth' });"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition duration-200 font-semibold">
                                Order Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layout>
