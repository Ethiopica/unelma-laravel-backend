<x-layout>
    <x-slot:title>Comments for {{ $blog->title }}</x-slot:title>

    <h1 class="text-2xl font-bold mb-4">Comments for "{{ $blog->title }}"</h1>

    @if ($comments->count() > 0)
        <ul class="space-y-4">
            @foreach ($comments as $comment)
                <li class="border p-4 rounded flex justify-between items-start">
                    <div>
                        <p class="font-medium">{{ $comment->user->name }}</p>
                        <p class="text-gray-500 text-sm">{{ $comment->created_at->format('M d, Y H:i') }}</p>
                        <p>{{ $comment->content }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.comments.destroy', $comment->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this comment?')">
                            Delete
                        </button>
                    </form>
                </li>
            @endforeach
        </ul>
    @else
        <p>No comments yet.</p>
    @endif

    <a href="{{ route('admin.blogs.index') }}" class="text-blue-600 hover:underline mt-4 inline-block">
        Back to blogs
    </a>
</x-layout>
