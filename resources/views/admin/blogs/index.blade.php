<x-layout>
    <x-slot:title>
        Blog Management - {{ config('app.name') }}
    </x-slot:title>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6 space-y-4 sm:space-y-0 sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Blog Management</h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Manage blog posts and articles</p>
            </div>
            <div class="flex justify-start sm:justify-end">
                <a href="{{ route('admin.blogs.create') }}"
                    class="inline-flex items-center space-x-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition duration-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v12m6-6H6"></path>
                    </svg>
                    <span>Create Blog Post</span>
                </a>
            </div>
        </div>
        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Blog Posts Table -->
        <div class="bg-white rounded-lg shadow-md">
            @if ($blogs->count() > 0)
                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Title</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Author</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Views</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Published</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($blogs as $blog)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if ($blog->featured_image)
                                                <img src="{{ asset('storage/' . $blog->featured_image) }}"
                                                    alt="{{ $blog->title }}"
                                                    class="h-12 w-12 rounded object-cover mr-3">
                                            @elseif ($blog->image_url)
                                                <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}"
                                                class="h-12 w-12 rounded object-cover mr-3">
                                            </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ Str::limit($blog->title, 50) }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ Str::limit($blog->excerpt ?? 'No excerpt', 40) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"> {{ $blog->author_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            {{ $blog->category ?? 'Uncategorized' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($blog->is_published)
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Published
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($blog->views) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $blog->published_at ? $blog->published_at->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.blogs.edit', $blog->id) }}"
                                                class="text-blue-600 hover:text-blue-900 blog-edit-link" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.blogs.destroy', $blog->id) }}"
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this blog post?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[#E3E174] hover:text-[#E3E174]/80"
                                                    title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="sm:hidden divide-y divide-gray-200">
                    @foreach ($blogs as $blog)
                        <div class="p-4 space-y-4">
                            <div class="flex items-start space-x-3">
                                @if ($blog->featured_image)
                                    <img src="{{ asset('storage/' . $blog->featured_image) }}"
                                        alt="{{ $blog->title }}"
                                        class="h-16 w-16 rounded object-cover">
                                @endif
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">
                                        {{ Str::limit($blog->title, 60) }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        {{ Str::limit($blog->excerpt ?? 'No excerpt', 80) }}
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-gray-500">Author</p>
                                    <p class="font-medium text-gray-900">{{   $blog->author_name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Category</p>
                                    <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-semibold text-purple-800">
                                        {{ $blog->category ?? 'Uncategorized' }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-gray-500">Status</p>
                                    @if ($blog->is_published)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">
                                            Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-800">
                                            Draft
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-gray-500">Views</p>
                                    <p class="font-medium text-gray-900">{{ number_format($blog->views) }}</p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-gray-500">Published</p>
                                    <p class="text-gray-900">{{ $blog->published_at ? $blog->published_at->format('M d, Y') : '-' }}</p>
                                </div>
                                
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('admin.blogs.edit', $blog->id) }}"
                                    class="blog-edit-button inline-flex items-center justify-center rounded-md border border-blue-600 px-3 py-2 text-sm font-semibold text-blue-600 transition hover:bg-blue-600 hover:text-white">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.blogs.destroy', $blog->id) }}"
                                    class="inline-flex"
                                    onsubmit="return confirm('Are you sure you want to delete this blog post?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center rounded-md border border-[#E3E174] px-3 py-2 text-sm font-semibold text-[#E3E174] transition hover:bg-gray-50">
                                        Delete
                                    </button>
                                </form>
                                <a href="{{ route('admin.blogs.comments', $blog->id) }}" 
                                    class="inline-flex items-center justify-center rounded-md border border-purple-600 px-3 py-2 text-sm font-semibold text-purple-600 transition hover:bg-purple-600 hover:text-white"
                                    >
                                     View Comments ({{ $blog->comments->count() }})
                                 </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No blog posts</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new blog post.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.blogs.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Blog Post
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout>
