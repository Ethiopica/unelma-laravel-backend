<x-layout>
    <x-slot:title>
        User Management - {{ config('app.name') }}
    </x-slot:title>
    @php
        $filter = $filter ?? 'all';
    @endphp
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6 space-y-4 sm:space-y-0 sm:flex sm:flex-wrap sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">User Management</h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Manage all users in the system</p>
            </div>
            <div class="flex justify-start sm:justify-end">
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex items-center space-x-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition duration-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
                    </svg>
                    <span>Add New User</span>
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
                <p class="text-gray-600 text-sm font-medium uppercase">Total Users</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_users'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
                <p class="text-gray-600 text-sm font-medium uppercase">Admin Users</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['admin_users'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
                <p class="text-gray-600 text-sm font-medium uppercase">Customers</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['customers'] }}</p>
            </div>
        </div>

        @if ($filter !== 'all')
            <div class="mb-6">
                <span
                    class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700">
                    Showing: {{ $filter === 'admin' ? 'Admin Users' : 'Customers' }}
                </span>
            </div>
        @endif

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $user->id }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if ($user->profile_picture)
                                                <img src="{{ asset('storage/' . $user->profile_picture) }}"
                                                    alt="{{ $user->name }}"
                                                    class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div
                                                    class="h-10 w-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if ($user->is_admin)
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">
                                            Admin
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-800">
                                            Customer
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="text-blue-600 hover:text-blue-900">
                                        Edit
                                    </a>
                                    @if ($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                                    No users found for this view.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="sm:hidden divide-y divide-gray-200">
                @if ($users->count())
                    @foreach ($users as $user)
                        <div class="p-4 space-y-4">
                            <div class="flex items-center space-x-4">
                                @if ($user->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}"
                                        alt="{{ $user->name }}" class="h-12 w-12 rounded-full object-cover">
                                @else
                                    <div
                                        class="h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-base font-semibold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-gray-500">User ID</p>
                                    <p class="font-medium text-gray-900">#{{ $user->id }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Role</p>
                                    @if ($user->is_admin)
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">
                                            Admin
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-800">
                                            Customer
                                        </span>
                                    @endif
                                </div>
                                <div class="col-span-2">
                                    <p class="text-gray-500">Created</p>
                                    <p class="text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="inline-flex items-center justify-center rounded-md border border-blue-600 px-3 py-2 text-sm font-semibold text-blue-600 transition hover:bg-blue-600 hover:text-white">
                                    Edit
                                </a>
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                        class="inline-flex"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center rounded-md border border-red-600 px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-600 hover:text-white">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-6 text-center text-sm text-gray-500">
                        No users found for this view.
                    </div>
                @endif
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>

</x-layout>
