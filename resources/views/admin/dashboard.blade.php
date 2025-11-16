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

        <!-- Summary Cards - Mobile First Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
            <!-- Total Users Card -->
            <div class="bg-white rounded-lg shadow-sm border border-blue-200 p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs font-semibold uppercase text-gray-500">Total Users</p>
                        <p class="mt-2 sm:mt-3 text-2xl sm:text-3xl font-bold text-blue-600">
                            {{ number_format($stats['total_users']) }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Admin Users Card -->
            <div class="bg-white rounded-lg shadow-sm border border-green-200 p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs font-semibold uppercase text-gray-500">Admin Users</p>
                        <p class="mt-2 sm:mt-3 text-2xl sm:text-3xl font-bold text-green-600">
                            {{ number_format($stats['admin_users']) }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Customers Card -->
            <div class="bg-white rounded-lg shadow-sm border border-purple-200 p-4 sm:p-5 col-span-2 md:col-span-1">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs font-semibold uppercase text-gray-500">Customers</p>
                        <p class="mt-2 sm:mt-3 text-2xl sm:text-3xl font-bold text-purple-600">
                            {{ number_format($stats['customers']) }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-purple-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <!-- Header with Icon - Mobile First -->
            <div
                class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0 px-4 sm:px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div
                        class="inline-flex items-center justify-center rounded-full bg-blue-100 text-blue-600 w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                        <i class="fa-solid fa-bolt text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Quick Actions</h2>
                        <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Access frequently used features and
                            management tools.</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="p-4 sm:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <a href="{{ route('admin.users.create') }}"
                        class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition duration-200 flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-blue-500 bg-blue-600 flex items-center justify-center mb-4 shadow-sm">
                            <i class="fas fa-user-plus text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Add User</h4>
                        <p class="text-xs sm:text-sm text-gray-600">Create a new user account with specific roles and
                            permissions.</p>
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition duration-200 flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-teal-500 bg-teal-600 flex items-center justify-center mb-4 shadow-sm">
                            <i class="fas fa-users text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Manage Users</h4>
                        <p class="text-xs sm:text-sm text-gray-600">View, edit, and manage all user accounts with
                            comprehensive controls.</p>
                    </a>
                    <a href="{{ route('admin.blogs.index') }}"
                        class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition duration-200 flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-cyan-500 bg-cyan-600 flex items-center justify-center mb-4 shadow-sm">
                            <i class="fas fa-blog text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Manage Blog</h4>
                        <p class="text-xs sm:text-sm text-gray-600">Create, edit, and publish blog posts to keep our
                            audience engaged and informed.</p>
                    </a>
                    <a href="{{ route('admin.products.index') }}"
                        class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition duration-200 flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-orange-500 bg-orange-600 flex items-center justify-center mb-4 shadow-sm">
                            <i class="fab fa-product-hunt text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Manage Products</h4>
                        <p class="text-xs sm:text-sm text-gray-600">Add, update, and organize product catalog with
                            images and detailed descriptions.</p>
                    </a>
                    <a href="{{ route('admin.services.index') }}"
                        class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition duration-200 flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-purple-500 bg-purple-600 flex items-center justify-center mb-4 shadow-sm">
                            <i class="fas fa-briefcase text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Manage Services</h4>
                        <p class="text-xs sm:text-sm text-gray-600">Showcase our services with detailed information and
                            pricing to attract customers.</p>
                    </a>
                    <a href="{{ route('admin.contact-messages.index') }}"
                        class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition duration-200 flex flex-col items-center text-center relative">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-red-500 bg-red-600 flex items-center justify-center mb-4 shadow-sm">
                            <i class="fas fa-envelope text-white text-xl sm:text-2xl"></i>
                        </div>
                        @if ($stats['unread_messages'] > 0)
                            <span
                                class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center border-2 border-white">
                                {{ $stats['unread_messages'] }}
                            </span>
                        @endif
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Manage Messages</h4>
                        <p class="text-xs sm:text-sm text-gray-600">View and manage customer contact messages and
                            inquiries
                            {{ $stats['unread_messages'] > 0 ? '(' . $stats['unread_messages'] . ' unread)' : '' }}</p>
                    </a>
                    <a href="{{ route('admin.reports.index') }}"
                        class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition duration-200 flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-gray-500 bg-gray-600 flex items-center justify-center mb-4 shadow-sm">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">View Reports</h4>
                        <p class="text-xs sm:text-sm text-gray-600">Access comprehensive analytics and reports to track
                            our system's performance and usage.</p>
                    </a>
                    <a href="{{ route('admin.settings.index') }}"
                        class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition duration-200 flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-indigo-500 bg-indigo-600 flex items-center justify-center mb-4 shadow-sm">
                            <i class="fas fa-gear text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Settings</h4>
                        <p class="text-xs sm:text-sm text-gray-600">Configure system settings, preferences, and
                            customize our admin panel experience.</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-layout>
