<x-layout>
    <x-slot:title>Comments for {{ $blog->title }}</x-slot:title>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2 breadcrumb-nav">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.blogs.index') }}" class="hover:text-gray-900">Blog</a>
                <span>/</span>
                <span class="text-gray-900">Comments</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Comments</h1>
            <p class="text-gray-600 mt-1">Managing comments for "{{ $blog->title }}"</p>
        </div>

        <!-- Comments List -->
        <div class="bg-white rounded-lg shadow-md">
            @if ($comments->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach ($comments as $comment)
                        <li class="p-4 sm:p-6 hover:bg-gray-50">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        <p class="font-semibold text-gray-900">{{ $comment->user->name }}</p>
                                        <span class="text-gray-400">•</span>
                                        <p class="text-gray-500 text-sm">{{ $comment->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <p class="text-gray-700">{{ $comment->content }}</p>
                                </div>
                                <form method="POST" action="{{ route('admin.comments.destroy', $comment->id) }}" class="flex-shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button class="content-delete-action inline-flex items-center justify-center rounded-md border px-3 py-2 text-sm font-semibold transition hover:bg-gray-50" 
                                        onclick="return confirm('Delete this comment?')">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No comments yet</h3>
                    <p class="mt-1 text-sm text-gray-500">This blog post has no comments.</p>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('admin.blogs.index') }}" 
                class="blog-edit-button inline-flex items-center justify-center rounded-md border border-blue-600 px-4 py-2 text-sm font-semibold text-blue-600 transition hover:bg-blue-600 hover:text-white">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Blogs
            </a>
        </div>
    </div>
</x-layout>
