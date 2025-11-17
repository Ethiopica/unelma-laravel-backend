<x-layout>
    <x-slot:title>Create Products - {{ config('app.name') }}</x-slot:title>
    <!-- Navigation Bar -->
    <x-header />

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.products.index') }}" class="hover:text-gray-900">Products</a>
                <span>/</span>
                <span class="text-gray-900">Create</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Create New Product</h1>
            <p class="text-gray-600 mt-1">Add a new product offer to your catalog</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Product Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        placeholder="e.g., Cloud Server Pro">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product category -->
                <div class="mb-6">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Category
                    </label>
                    <input type="text" id="category" name="category" value="{{ old('category') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror"
                        placeholder="e.g., Open source">
                    @error('category')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product sku -->
                <div class="mb-6">
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                        Sku <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sku') border-red-500 @enderror"
                        placeholder="e.g., 123456_UnelmaPlatforms_OpenSource">
                    @error('sku')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Price -->
                <div class="mb-6">
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                        Price (€) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">€</span>
                        <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01"
                            min="0" required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror"
                            placeholder="0.00">
                    </div>
                    @error('price')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                

                <!-- Highlights -->
                <div class="mb-6">
                    <label for="highlights" class="block text-sm font-medium text-gray-700 mb-2">
                        Highlights
                    </label>
                    <textarea id="highlights" name="highlights" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                        placeholder="Product highlights...">{{ old('highlights') }}</textarea>
                    @error('highlights')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                        placeholder="Describe your product features and benefits...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                
                <!-- Rating -->
                <div class="mb-6">
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">
                        Rating 
                    </label>
                    <input type="number" id="rating" name="rating" value="{{ old('rating') }}" step="0.01" min="0" max="5"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('rating') border-red-500 @enderror"
                    placeholder="0">
                 
                    @error('rating')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Image -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Product Image
                    </label>
                    <input type="file" id="image" name="image"
                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror">
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Accepted formats: JPEG, PNG, GIF, WebP. Max size: 2MB</p>
                </div>

                <!--Product Image Url -->
                <div class="mb-6">
                    <label for="image_url" class="block text-sm font-medium text-gray-700 mb-2">
                       Or Image Url
                    </label>
                    <input type="text" id="image_url" name="image_url" value="{{ old('image_url') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image_url') border-red-500 @enderror"
                        placeholder="e.g., https://www.example.com/images/product1.jpg">
                    @error('image_url')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                </div>

                <!-- Settings Section -->
                <div class="border-t pt-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Display Settings</h3>

                    <!-- Display Order -->
                    <div class="mb-6">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Display Order
                        </label>
                        <input type="number" id="order" name="order" value="{{ old('order', 0) }}"
                            min="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('order') border-red-500 @enderror"
                            placeholder="0">
                        @error('order')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Lower numbers appear first (0 = highest priority)</p>
                    </div>

                    <!-- Checkboxes -->
                    <div class="space-y-4">
                        <!-- Featured -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" id="is_featured" name="is_featured" value="1"
                                    {{ old('is_featured') ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </div>
                            <div class="ml-3">
                                <label for="is_featured" class="font-medium text-gray-700">
                                    Featured Product
                                </label>
                                <p class="text-sm text-gray-500">
                                    Display a featured badge on this product
                                </p>
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </div>
                            <div class="ml-3">
                                <label for="is_active" class="font-medium text-gray-700">
                                    Active
                                </label>
                                <p class="text-sm text-gray-500">
                                    Make this product visible on the website
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.products.index') }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Create Product</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
