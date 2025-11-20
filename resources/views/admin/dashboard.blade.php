<x-layout>
    <x-slot:title>
        Admin Dashboard - {{ config('app.name') }}
    </x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8 space-y-4 sm:space-y-6 lg:space-y-8">
        <!-- Header Section - Mobile First -->
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Here's what's happening with our platform today.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded mb-4">
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

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
            <!-- Total Users Card -->
            <div class="bg-white rounded-lg shadow-sm border border-blue-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Total Users</p>
                    <div class="bg-blue-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <i class="fa-solid fa-users text-blue-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                <p class="text-xs text-gray-600 mt-1">All registered users</p>
            </div>

            <!-- Admin Users Card -->
            <div class="bg-white rounded-lg shadow-sm border border-green-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Admin Users</p>
                    <div class="bg-green-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <i class="fa-solid fa-user-shield text-green-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($stats['admin_users']) }}</p>
                <p class="text-xs text-gray-600 mt-1">Administrative access</p>
            </div>

            <!-- Customers Card -->
            <div class="bg-white rounded-lg shadow-sm border border-purple-200 p-4 sm:p-5 col-span-2 md:col-span-1">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Customers</p>
                    <div class="bg-purple-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <i class="fa-solid fa-user-group text-purple-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($stats['customers']) }}</p>
                <p class="text-xs text-gray-600 mt-1">Active customers</p>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <!-- Header with Icon - Mobile First -->
            <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="inline-flex items-center justify-center rounded-full bg-blue-100 text-blue-600 w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                        <i class="fa-solid fa-bolt text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Quick Actions</h2>
                        <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Access frequently used features and management tools.</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="p-4 sm:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <a href="{{ route('admin.users.create') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-lg bg-blue-100 flex items-center justify-center mb-3 group-hover:bg-blue-200 transition-colors">
                            <i class="fa-solid fa-user-plus text-blue-600 text-xl"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Add User</h4>
                        <p class="text-xs text-gray-600">Create a new user account</p>
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-lg bg-blue-100 flex items-center justify-center mb-3 group-hover:bg-blue-200 transition-colors">
                            <i class="fa-solid fa-users text-blue-600 text-xl"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Manage Users</h4>
                        <p class="text-xs text-gray-600">View and manage all users</p>
                    </a>
                    <a href="{{ route('admin.blogs.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-lg bg-purple-100 flex items-center justify-center mb-3 group-hover:bg-purple-200 transition-colors">
                            <i class="fa-solid fa-blog text-purple-600 text-xl"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Manage Blog</h4>
                        <p class="text-xs text-gray-600">Create and edit blog posts</p>
                    </a>
                    <a href="{{ route('admin.products.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-lg bg-indigo-100 flex items-center justify-center mb-3 group-hover:bg-indigo-200 transition-colors">
                            <i class="fa-solid fa-box text-indigo-600 text-xl"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Manage Products</h4>
                        <p class="text-xs text-gray-600">Add and update products</p>
                    </a>
                    <a href="{{ route('admin.services.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-lg bg-green-100 flex items-center justify-center mb-3 group-hover:bg-green-200 transition-colors">
                            <i class="fa-solid fa-briefcase text-green-600 text-xl"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Manage Services</h4>
                        <p class="text-xs text-gray-600">Showcase your services</p>
                    </a>
                    <a href="{{ route('admin.contact-messages.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center relative">
                        <div class="w-16 h-16 rounded-lg bg-amber-100 flex items-center justify-center mb-3 group-hover:bg-amber-200 transition-colors">
                            <i class="fa-solid fa-envelope text-amber-600 text-xl"></i>
                        </div>
                        @if ($stats['unread_messages'] > 0)
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                                {{ $stats['unread_messages'] }}
                            </span>
                        @endif
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Manage Messages</h4>
                        <p class="text-xs text-gray-600">View customer inquiries</p>
                    </a>
                    <a href="{{ route('admin.subscribers.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-lg bg-cyan-100 flex items-center justify-center mb-3 group-hover:bg-cyan-200 transition-colors">
                            <i class="fa-solid fa-envelope-circle-check text-cyan-600 text-xl"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Newsletter Subscribers</h4>
                        <p class="text-xs text-gray-600">Manage subscribers</p>
                    </a>
                    <a href="{{ route('admin.reports.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-lg bg-green-100 flex items-center justify-center mb-3 group-hover:bg-green-200 transition-colors">
                            <i class="fa-solid fa-chart-line text-green-600 text-xl"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">View Reports</h4>
                        <p class="text-xs text-gray-600">System analytics</p>
                    </a>
                    <a href="{{ route('admin.settings.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center mb-3 group-hover:bg-gray-200 transition-colors">
                            <i class="fa-solid fa-gear text-gray-600 text-xl"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Settings</h4>
                        <p class="text-xs text-gray-600">Configure system</p>
                    </a>
                </div>
            </div>
        </div>

</x-layout>
