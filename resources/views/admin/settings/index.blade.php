<x-layout>
    <x-slot:title>
        Settings " Unelma"
    </x-slot:title>
    
    <!-- Main Content -->
    @php
        $section = request('section', 'system');
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
                <span>/</span>
                <span class="text-gray-900">Settings</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">System Settings</h1>
            <p class="text-gray-600 mt-1">Configure your application settings</p>
        </div>

        <!-- Section Switcher -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Manage Settings</h2>
                <p class="text-sm text-gray-500">Switch between global system settings and your personal account.</p>
            </div>
            <form action="{{ route('admin.settings.index') }}" method="GET" class="w-full sm:w-auto">
                <label for="settings-section" class="sr-only">Choose section</label>
                <select id="settings-section" name="section"
                        class="w-full sm:w-64 rounded-lg border border-gray-300 bg-white py-2.5 px-4 text-sm font-medium text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        onchange="this.form.submit()">
                    <option value="system" @selected($section === 'system')>System Settings</option>
                    <option value="account" @selected($section === 'account')>Account Settings</option>
                </select>
            </form>
        </div>

        <!-- Success/Error Messages -->
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

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if ($section === 'system')
            <!-- Settings Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Settings Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <form method="POST" action="{{ route('admin.settings.update') }}">
                            @csrf
                            @method('PUT')

                            <!-- General Settings -->
                            <div class="mb-8">
                                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    General Settings
                                </h2>

                                <!-- Application Name -->
                                <div class="mb-4">
                                    <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Application Name
                                    </label>
                                    <input type="text" id="app_name" name="app_name" value="{{ config('app.name') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Unelma Backend">
                                </div>

                                <!-- Application Email -->
                                <div class="mb-4">
                                    <label for="app_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        System Email
                                    </label>
                                    <input type="email" id="app_email" name="app_email" value="admin@unelma.com"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="admin@example.com">
                                </div>

                                <!-- Users Per Page -->
                                <div class="mb-4">
                                    <label for="users_per_page" class="block text-sm font-medium text-gray-700 mb-2">
                                        Users Per Page
                                    </label>
                                    <input type="number" id="users_per_page" name="users_per_page" value="10"
                                        min="5" max="100"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <p class="mt-1 text-sm text-gray-500">Number of users to display per page (5-100)
                                    </p>
                                </div>
                            </div>

                            <!-- Feature Toggles -->
                            <div class="mb-8 pb-8 border-b">
                                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                        </path>
                                    </svg>
                                    Feature Toggles
                                </h2>

                                <!-- Enable Registration -->
                                <div class="mb-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="enable_registration" name="enable_registration"
                                                value="1" checked
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3">
                                            <label for="enable_registration" class="font-medium text-gray-700">
                                                Enable User Registration
                                            </label>
                                            <p class="text-sm text-gray-500">
                                                Allow new users to register via the API
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Maintenance Mode -->
                                <div class="mb-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="maintenance_mode" name="maintenance_mode"
                                                value="1"
                                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3">
                                            <label for="maintenance_mode" class="font-medium text-gray-700">
                                                Maintenance Mode
                                            </label>
                                            <p class="text-sm text-gray-500">
                                                Put the application in maintenance mode
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('admin.dashboard') }}"
                                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Save Settings</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- System Information Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Account Settings Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Account</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ auth()->user()->name ?? 'Admin User' }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                            </div>
                        </div>

                        <a href="{{ route('admin.users.edit', auth()->user() ?? 1) }}"
                            class="flex items-center justify-between border border-blue-100 rounded-xl px-4 py-3 hover:border-blue-300 hover:shadow-sm transition">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                                    <i class="fa-solid fa-user-gear"></i>
                                </span>
                                <div>
                                    <p class="font-semibold text-gray-900">Profile Details</p>
                                    <p class="text-xs text-gray-500">Update personal information</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-arrow-up-right-from-square text-blue-400"></i>
                        </a>

                        <a href="{{ route('admin.settings.index', ['section' => 'account']) }}"
                            class="flex items-center justify-between border border-indigo-100 rounded-xl px-4 py-3 hover:border-indigo-300 hover:shadow-sm transition">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                                    <i class="fa-solid fa-sliders"></i>
                                </span>
                                <div>
                                    <p class="font-semibold text-gray-900">Account Settings</p>
                                    <p class="text-xs text-gray-500">Manage your account</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-arrow-up-right-from-square text-indigo-400"></i>
                        </a>
                    </div>
                </div>

                    <!-- System Info Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">System Information</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Laravel Version:</span>
                            <span class="font-semibold text-gray-800">{{ app()->version() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">PHP Version:</span>
                            <span class="font-semibold text-gray-800">{{ PHP_VERSION }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Environment:</span>
                            <span
                                class="font-semibold {{ config('app.env') === 'production' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ ucfirst(config('app.env')) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Debug Mode:</span>
                            <span class="font-semibold {{ config('app.debug') ? 'text-red-600' : 'text-green-600' }}">
                                {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                    </div>
                </div>

                    <!-- Quick Actions Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 space-y-4">
                    <h3 class="text-lg font-bold text-gray-800">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center justify-between border border-blue-100 rounded-xl px-4 py-3 hover:border-blue-300 hover:shadow-sm transition">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                                    <i class="fa-solid fa-users"></i>
                                </span>
                                <div>
                                    <p class="font-semibold text-gray-900">User Management</p>
                                    <p class="text-xs text-gray-500">Roles & permissions</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-arrow-up-right-from-square text-blue-400"></i>
                        </a>
                        <a href="{{ route('admin.reports.index') }}"
                            class="flex items-center justify-between border border-emerald-100 rounded-xl px-4 py-3 hover:border-emerald-300 hover:shadow-sm transition">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                                    <i class="fa-solid fa-chart-line"></i>
                                </span>
                                <div>
                                    <p class="font-semibold text-gray-900">Reports</p>
                                    <p class="text-xs text-gray-500">Usage & billing</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-arrow-up-right-from-square text-emerald-400"></i>
                        </a>
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center justify-between border border-purple-100 rounded-xl px-4 py-3 hover:border-purple-300 hover:shadow-sm transition">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-purple-50 text-purple-600">
                                    <i class="fa-solid fa-gauge"></i>
                                </span>
                                <div>
                                    <p class="font-semibold text-gray-900">Go to Dashboard</p>
                                    <p class="text-xs text-gray-500">Overview & insights</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-arrow-up-right-from-square text-purple-400"></i>
                        </a>
                    </div>
                    </div>
                </div>
            </div>
        @else
                <!-- Account Settings View -->
            <div class="space-y-6">
                <div class="account-owner-card bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 rounded-2xl bg-white/20 flex items-center justify-center text-3xl font-semibold">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm uppercase tracking-[0.3em] text-white/70">Account owner</p>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-3xl font-semibold">{{ auth()->user()->name ?? 'Admin User' }}</h3>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100/30 px-3 py-1 text-xs font-semibold text-emerald-50">
                                        <span class="size-2 rounded-full bg-emerald-300 animate-pulse"></span>
                                        Verified
                                    </span>
                                </div>
                                <p class="text-white/80">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('admin.users.edit', auth()->user() ?? 1) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/90 px-5 py-3 text-indigo-700 font-semibold hover:bg-white transition">
                                <i class="fa-solid fa-pen-to-square"></i>
                                Edit profile
                            </a>
                            <a href="https://dashboard.stripe.com/"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/15 px-5 py-3 text-white font-semibold border border-white/30 hover:bg-white/25 transition">
                                <i class="fa-brands fa-stripe"></i>
                                Billing portal
                            </a>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center gap-3 mb-5">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                                    <i class="fa-solid fa-user-gear text-lg"></i>
                                </span>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Profile Details</h3>
                                    <p class="text-sm text-gray-500">Basic information that identifies your account</p>
                                </div>
                            </div>

                            <dl class="space-y-4 text-sm text-gray-700">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <dt class="font-medium text-gray-500">Full name</dt>
                                    <dd class="font-semibold text-gray-900">{{ auth()->user()->name ?? 'Admin User' }}</dd>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <dt class="font-medium text-gray-500">Email address</dt>
                                    <dd class="font-semibold text-gray-900">{{ auth()->user()->email ?? 'admin@example.com' }}</dd>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <dt class="font-medium text-gray-500">Phone</dt>
                                    <dd class="font-semibold text-gray-900">{{ auth()->user()->phone ?? '+00 (000) 000-0000' }}</dd>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <dt class="font-medium text-gray-500">Timezone</dt>
                                    <dd class="font-semibold text-gray-900">{{ config('app.timezone') ?? 'UTC' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center gap-3 mb-5">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                                    <i class="fa-solid fa-shield-halved text-lg"></i>
                                </span>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Security Overview</h3>
                                    <p class="text-sm text-gray-500">Protect your account with these recommendations</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div class="rounded-2xl border border-gray-100 p-4">
                                    <p class="text-gray-500 mb-1">Multi-factor auth</p>
                                    <p class="font-semibold text-gray-900">Enabled</p>
                                    <p class="text-xs text-gray-500 mt-1">SMS + Authenticator app</p>
                                </div>
                                <div class="rounded-2xl border border-gray-100 p-4">
                                    <p class="text-gray-500 mb-1">Password updated</p>
                                    <p class="font-semibold text-gray-900">{{ now()->subMonths(2)->format('M j, Y') }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Consider refreshing regularly</p>
                                </div>
                                <div class="rounded-2xl border border-gray-100 p-4">
                                    <p class="text-gray-500 mb-1">Login alerts</p>
                                    <p class="font-semibold text-gray-900">Enabled for new devices</p>
                                </div>
                                <div class="rounded-2xl border border-gray-100 p-4">
                                    <p class="text-gray-500 mb-1">Backup codes</p>
                                    <p class="font-semibold text-gray-900">3 of 5 unused</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center gap-3 mb-5">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-50 text-purple-600">
                                    <i class="fa-solid fa-bell text-lg"></i>
                                </span>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                                    <p class="text-sm text-gray-500">Email and in-app alerts</p>
                                </div>
                            </div>

                            <ul class="space-y-3 text-sm text-gray-700">
                                <li class="flex items-center justify-between">
                                    <span>Weekly product tips</span>
                                    <span class="inline-flex items-center text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                                        Enabled
                                    </span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span>System incidents</span>
                                    <span class="inline-flex items-center text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                                        SMS + Email
                                    </span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span>Billing alerts</span>
                                    <span class="inline-flex items-center text-xs font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">
                                        Critical only
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center gap-3 mb-5">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-orange-600">
                                    <i class="fa-solid fa-right-to-bracket text-lg"></i>
                                </span>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Active Sessions</h3>
                                    <p class="text-sm text-gray-500">Signed-in devices</p>
                                </div>
                            </div>

                            <div class="space-y-4 text-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">MacOS · Safari</p>
                                        <p class="text-xs text-gray-500">Last active: just now</p>
                                    </div>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                        Current
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">iOS · Mobile App</p>
                                        <p class="text-xs text-gray-500">Last active: 2h ago</p>
                                    </div>
                                    <button class="text-xs font-semibold text-rose-600 hover:text-rose-700">
                                        Sign out
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layout>
