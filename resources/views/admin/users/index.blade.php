<x-layout>
    <x-slot:title>
        User Management - {{ config('app.name') }}
    </x-slot:title>
    @php
        $filter = $filter ?? 'all';
    @endphp
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8 space-y-4 sm:space-y-6 lg:space-y-8">
        <!-- Header Section - Mobile First -->
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">User Management</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Manage all users in the system</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <a href="{{ route('admin.users.create') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Add New User</span>
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
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
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Summary Cards - Mobile First Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs font-semibold uppercase text-gray-500">Total Users</p>
                        <p class="mt-2 sm:mt-3 text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                    </div>
                    <div class="bg-gray-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-green-200 p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs font-semibold uppercase text-gray-500">Admin Users</p>
                        <p class="mt-2 sm:mt-3 text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($stats['admin_users']) }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-blue-200 p-4 sm:p-5 col-span-2 md:col-span-1">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs font-semibold uppercase text-gray-500">Customers</p>
                        <p class="mt-2 sm:mt-3 text-2xl sm:text-3xl font-bold text-blue-600">{{ number_format($stats['customers']) }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        @if ($filter !== 'all')
            <div class="mb-6">
                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700">
                    Showing: {{ $filter === 'admin' ? 'Admin Users' : 'Customers' }}
                </span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <!-- Header with Filter - Mobile First -->
            <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0 px-4 sm:px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="inline-flex items-center justify-center rounded-full bg-blue-100 text-blue-600 w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                        <i class="fa-solid fa-users text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">All Users</h2>
                        <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Manage user accounts and permissions.</p>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->id }}</td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        @if($user->profile_picture)
                                            <img 
                                                src="{{ asset('storage/' . $user->profile_picture) }}" 
                                                alt="{{ $user->name }}" 
                                                class="h-10 w-10 rounded-full object-cover flex-shrink-0"
                                            >
                                        @else
                                            <div class="h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 font-semibold flex-shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 truncate max-w-xs">{{ $user->email }}</div>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    @if ($user->is_admin)
                                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-700">
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold bg-gray-200 text-gray-700">
                                            Customer
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="hidden lg:inline">{{ $user->created_at->format('M j, Y H:i') }}</span>
                                    <span class="lg:hidden">{{ $user->created_at->format('M j') }}</span>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="inline-flex items-center rounded-md border border-blue-200 px-2 lg:px-3 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-50 whitespace-nowrap">
                                            <span class="hidden lg:inline">Edit</span>
                                            <span class="lg:hidden">Edit</span>
                                        </a>
                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-md border border-red-200 px-2 lg:px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50 whitespace-nowrap">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 lg:px-6 py-10 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                            <i class="fa-solid fa-users"></i>
                                        </div>
                                        <p class="font-semibold text-gray-700">No users found</p>
                                        <p class="text-sm text-gray-500 max-w-sm px-4">
                                            No users match the current filter criteria.
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
                @forelse ($users as $user)
                    <div class="p-4 space-y-3">
                        <!-- User Info -->
                        <div class="flex items-center gap-3">
                            @if($user->profile_picture)
                                <img 
                                    src="{{ asset('storage/' . $user->profile_picture) }}" 
                                    alt="{{ $user->name }}" 
                                    class="h-10 w-10 rounded-full object-cover flex-shrink-0"
                                >
                            @else
                                <div class="h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 font-semibold flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                            @if ($user->is_admin)
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-700 flex-shrink-0">
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold bg-gray-200 text-gray-700 flex-shrink-0">
                                    Customer
                                </span>
                            @endif
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">User ID</p>
                                <p class="font-medium text-gray-900">#{{ $user->id }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Created</p>
                                <p class="font-medium text-gray-900">{{ $user->created_at->format('M j, Y') }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col gap-2 pt-2">
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="w-full inline-flex items-center justify-center gap-2 rounded-md border border-blue-200 px-3 py-2 text-xs font-semibold text-blue-600 transition hover:bg-blue-50">
                                <i class="fa-solid fa-edit"></i>
                                Edit User
                            </a>
                            @if ($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-md border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                        <i class="fa-solid fa-trash"></i>
                                        Delete User
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-10 text-center text-sm text-gray-500">
                        <div class="flex flex-col items-center gap-3">
                            <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <p class="font-semibold text-gray-700">No users found</p>
                            <p class="text-sm text-gray-500 max-w-sm px-4">
                                No users match the current filter criteria.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="px-4 sm:px-6 py-4 border-t border-gray-100">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-layout>
