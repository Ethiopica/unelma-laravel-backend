<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Settings - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gruppo&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Montserrat", sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-800">Admin Panel</a>
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">Users</a>
                    <a href="{{ route('admin.settings.index') }}" class="text-blue-600 font-semibold">Settings</a>
                    <a href="{{ route('admin.reports.index') }}" class="text-gray-600 hover:text-gray-900">Reports</a>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                        @if(auth()->user()->profile_picture)
                            <img 
                                src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                                alt="{{ auth()->user()->name }}" 
                                class="h-10 w-10 rounded-full object-cover border-2 border-gray-300"
                            >
                        @else
                            <div class="h-10 w-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="text-gray-700">{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button 
                            type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
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

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
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
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                General Settings
                            </h2>

                            <!-- Application Name -->
                            <div class="mb-4">
                                <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Application Name
                                </label>
                                <input 
                                    type="text" 
                                    id="app_name" 
                                    name="app_name" 
                                    value="{{ config('app.name') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Unelma Backend"
                                >
                            </div>

                            <!-- Application Email -->
                            <div class="mb-4">
                                <label for="app_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    System Email
                                </label>
                                <input 
                                    type="email" 
                                    id="app_email" 
                                    name="app_email" 
                                    value="admin@unelma.com"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="admin@example.com"
                                >
                            </div>

                            <!-- Users Per Page -->
                            <div class="mb-4">
                                <label for="users_per_page" class="block text-sm font-medium text-gray-700 mb-2">
                                    Users Per Page
                                </label>
                                <input 
                                    type="number" 
                                    id="users_per_page" 
                                    name="users_per_page" 
                                    value="10"
                                    min="5"
                                    max="100"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                <p class="mt-1 text-sm text-gray-500">Number of users to display per page (5-100)</p>
                            </div>
                        </div>

                        <!-- Feature Toggles -->
                        <div class="mb-8 pb-8 border-b">
                            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Feature Toggles
                            </h2>

                            <!-- Enable Registration -->
                            <div class="mb-4">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input 
                                            type="checkbox" 
                                            id="enable_registration" 
                                            name="enable_registration"
                                            value="1"
                                            checked
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        >
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
                                        <input 
                                            type="checkbox" 
                                            id="maintenance_mode" 
                                            name="maintenance_mode"
                                            value="1"
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                                        >
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
                            <a 
                                href="{{ route('admin.dashboard') }}"
                                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200"
                            >
                                Cancel
                            </a>
                            <button 
                                type="submit"
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 flex items-center space-x-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Save Settings</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- System Information Sidebar -->
            <div class="lg:col-span-1">
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
                            <span class="font-semibold {{ config('app.env') === 'production' ? 'text-green-600' : 'text-yellow-600' }}">
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
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.users.index') }}" class="block w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 rounded-lg transition duration-200 text-center">
                            Manage Users
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="block w-full bg-green-50 hover:bg-green-100 text-green-700 px-4 py-2 rounded-lg transition duration-200 text-center">
                            View Reports
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="block w-full bg-purple-50 hover:bg-purple-100 text-purple-700 px-4 py-2 rounded-lg transition duration-200 text-center">
                            Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>








