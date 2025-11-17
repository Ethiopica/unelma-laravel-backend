<x-layout>
    <x-slot:title>
        Admin Dashboard - {{ config('app.name') }}
    </x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Message -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="text-gray-600">Here's what's happening with this platform today.</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
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
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Users Card -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium uppercase">Total Users</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_users'] }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Admin Users Card -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium uppercase">Admin Users</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['admin_users'] }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Customers Card -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium uppercase">Customers</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['customers'] }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('admin.users.create') }}"
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200 flex flex-col items-center text-center">
                    <div
                        class="w-20 h-20 rounded-full border-2 border-blue-300 bg-gradient-to-br from-blue-500 via-blue-600 to-cyan-500 flex items-center justify-center mb-4 shadow-lg">
                        <i class="fas fa-user-plus text-white text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Add User</h4>
                    <p class="text-sm text-gray-600">Create a new user account with specific roles and permissions.</p>
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200 flex flex-col items-center text-center">
                    <div
                        class="w-20 h-20 rounded-full border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 via-green-500 to-teal-500 flex items-center justify-center mb-4 shadow-lg">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Manage Users</h4>
                    <p class="text-sm text-gray-600">View, edit, and manage all user accounts
                        comprehensive controls.</p>
                </a>
                <a href="{{ route('admin.blogs.index') }}"
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200 flex flex-col items-center text-center">
                    <div
                        class="w-20 h-20 rounded-full border-2 border-teal-300 bg-gradient-to-br from-teal-500 via-cyan-500 to-blue-500 flex items-center justify-center mb-4 shadow-lg">
                        <i class="fas fa-blog text-white text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Manage Blog</h4>
                    <p class="text-sm text-gray-600">Create, edit, and publish blog posts to keep our audience engaged
                        and informed.</p>
                </a>
                <a href="{{ route('admin.products.index') }}"
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200 flex flex-col items-center text-center">
                    <div
                        class="w-20 h-20 rounded-full border-2 border-orange-300 bg-gradient-to-br from-orange-500 via-amber-500 to-yellow-500 flex items-center justify-center mb-4 shadow-lg">
                        <i class="fab fa-product-hunt text-white text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Manage Products</h4>
                    <p class="text-sm text-gray-600">Add, update, and organize product catalog with images and
                        detailed descriptions.</p>
                </a>
                <a href="{{ route('admin.services.index') }}"
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200 flex flex-col items-center text-center">
                    <div
                        class="w-20 h-20 rounded-full border-2 border-violet-300 bg-gradient-to-br from-violet-500 via-purple-500 to-fuchsia-500 flex items-center justify-center mb-4 shadow-lg">
                        <i class="fas fa-briefcase text-white text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Manage Services</h4>
                    <p class="text-sm text-gray-600">Showcase our services with detailed information and pricing to
                        attract customers.</p>
                </a>
                <a href="{{ route('admin.contact-messages.index') }}"
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200 flex flex-col items-center text-center relative">
                    <div
                        class="w-20 h-20 rounded-full border-2 border-pink-300 bg-gradient-to-br from-pink-500 via-rose-500 to-red-500 flex items-center justify-center mb-4 shadow-lg">
                        <i class="fas fa-envelope text-white text-2xl"></i>
                    </div>
                    @if ($stats['unread_messages'] > 0)
                        <span
                            class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                            {{ $stats['unread_messages'] }}
                        </span>
                    @endif
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Manage Messages</h4>
                    <p class="text-sm text-gray-600">View and manage customer contact messages and inquiries
                        {{ $stats['unread_messages'] > 0 ? '(' . $stats['unread_messages'] . ' unread)' : '' }}</p>
                </a>
                <a href="{{ route('admin.reports.index') }}"
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200 flex flex-col items-center text-center">
                    <div
                        class="w-20 h-20 rounded-full border-2 border-slate-300 bg-gradient-to-br from-slate-600 via-gray-600 to-zinc-600 flex items-center justify-center mb-4 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">View Reports</h4>
                    <p class="text-sm text-gray-600">Access comprehensive analytics and reports to track our system's
                        performance and usage.</p>
                </a>
                <a href="{{ route('admin.settings.index') }}"
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200 flex flex-col items-center text-center">
                    <div
                        class="w-20 h-20 rounded-full border-2 border-purple-300 bg-gradient-to-br from-purple-600 via-violet-600 to-indigo-600 flex items-center justify-center mb-4 shadow-lg">
                        <i class="fas fa-gear text-white text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Settings</h4>
                    <p class="text-sm text-gray-600">Configure system settings, preferences, and customize our admin
                        panel experience.</p>
                </a>
            </div>
        </div>
    </div>

</x-layout>
