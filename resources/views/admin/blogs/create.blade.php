<x-layout>
    <x-slot:title>Create blog Post - {{ config('app.name') }}</x-slot:title>
    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.blogs.index') }}" class="hover:text-gray-900">Blog</a>
                <span>/</span>
                <span class="text-gray-900">Create</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Create New Blog Post</h1>
            <p class="text-gray-600 mt-1">Write and publish a new blog article</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Blog Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                        placeholder="e.g., Getting Started with Cloud Computing">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-6">
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                        Slug (URL)
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                        placeholder="e.g., getting-started-with-cloud-computing">
                    @error('slug')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from title</p>
                </div>

                <!-- Excerpt -->
                <div class="mb-6">
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                        Excerpt (Short Description)
                    </label>
                    <textarea id="excerpt" name="excerpt" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('excerpt') border-red-500 @enderror"
                        placeholder="A brief summary of your blog post (max 500 characters)...">{{ old('excerpt') }}</textarea>
                    @error('excerpt')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">This appears in blog listings and search results</p>
                </div>

                <!-- Content -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Content <span class="text-red-500">*</span>
                    </label>
                    <textarea id="content" name="content" rows="15" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('content') border-red-500 @enderror"
                        placeholder="Write your blog post content here... (HTML supported)">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">HTML tags are supported</p>
                </div>

                <!-- Featured Image -->
                <div class="mb-6">
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                        Featured Image
                    </label>
                    <input type="file" id="featured_image" name="featured_image"
                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('featured_image') border-red-500 @enderror">
                    @error('featured_image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Accepted formats: JPEG, PNG, GIF, WebP. Max size: 2MB</p>
                </div>

                <!--Blog Image Url -->
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
                <!-- Category and Tags -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Category
                        </label>
                        <input type="text" id="category" name="category" value="{{ old('category') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror"
                            placeholder="e.g., Technology, Cloud, Tutorial">
                        @error('category')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tags -->
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                            Tags
                        </label>
                        <input type="text" id="tags" name="tags" value="{{ old('tags') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tags') border-red-500 @enderror"
                            placeholder="e.g., cloud, aws, tutorial (comma-separated)">
                        @error('tags')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Separate tags with commas</p>
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="border-t pt-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>

                    <!-- Meta Title -->
                    <div class="mb-6">
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Meta Title
                        </label>
                        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('meta_title') border-red-500 @enderror"
                            placeholder="SEO title (if different from blog title)">
                        @error('meta_title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meta Description -->
                    <div class="mb-6">
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Meta Description
                        </label>
                        <textarea id="meta_description" name="meta_description" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('meta_description') border-red-500 @enderror"
                            placeholder="Brief description for search engines (150-160 characters recommended)">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meta Keywords -->
                    <div class="mb-6">
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                            Meta Keywords
                        </label>
                        <input type="text" id="meta_keywords" name="meta_keywords"
                            value="{{ old('meta_keywords') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('meta_keywords') border-red-500 @enderror"
                            placeholder="e.g., keyword1, keyword2, keyword3">
                        @error('meta_keywords')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Separate keywords with commas</p>
                    </div>
                </div>

                <!-- Display Settings -->
                <div class="border-t pt-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Display Settings</h3>

                    <!-- Order -->
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
                        <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                    </div>

                    <!-- Published Status -->
                    <div class="mb-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" id="is_published" name="is_published" value="1"
                                    {{ old('is_published', false) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </div>
                            <div class="ml-3">
                                <label for="is_published" class="font-medium text-gray-700">
                                    Publish Blog Post
                                </label>
                                <p class="text-sm text-gray-500">
                                    Make this blog post visible to the public
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.blogs.index') }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Create Blog Post</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
