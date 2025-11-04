<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit User - {{ config('app.name') }}</title>
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
                    <a href="{{ route('admin.settings.index') }}" class="text-gray-600 hover:text-gray-900">Settings</a>
                    <a href="{{ route('admin.reports.index') }}" class="text-gray-600 hover:text-gray-900">Reports</a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">{{ auth()->user()->name }}</span>
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
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.users.index') }}" class="hover:text-gray-900">Users</a>
                <span>/</span>
                <span class="text-gray-900">Edit</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Edit User</h1>
            <p class="text-gray-600 mt-1">Update user account information</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $user->name) }}"
                        required 
                        autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        placeholder="John Doe"
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $user->email) }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                        placeholder="john@example.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Profile Picture Field -->
                <div>
                    <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-2">
                        Profile Picture
                    </label>
                    
                    @if($user->profile_picture)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Current Profile Picture:</p>
                            <img 
                                src="{{ asset('storage/' . $user->profile_picture) }}" 
                                alt="{{ $user->name }}'s profile picture" 
                                class="w-24 h-24 rounded-full object-cover border-2 border-gray-300"
                            >
                        </div>
                    @endif
                    
                    <input 
                        type="file" 
                        id="profile_picture" 
                        name="profile_picture"
                        accept="image/jpeg,image/png,image/jpg,image/gif"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('profile_picture') border-red-500 @enderror"
                    >
                    @error('profile_picture')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $user->profile_picture ? 'Upload a new image to replace the current one. ' : '' }}
                        Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB
                    </p>
                </div>

                <!-- Password Section -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                    <p class="text-sm text-gray-600 mb-4">Leave blank to keep current password</p>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            New Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                            placeholder="••••••••"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Must be at least 8 characters</p>
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm New Password
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <!-- Admin Checkbox -->
                <div class="border-t pt-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input 
                                type="checkbox" 
                                id="is_admin" 
                                name="is_admin"
                                value="1"
                                {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                        </div>
                        <div class="ml-3">
                            <label for="is_admin" class="font-medium text-gray-700">
                                Admin User
                            </label>
                            <p class="text-sm text-gray-500">
                                Grant this user admin privileges to access the admin panel
                            </p>
                        </div>
                    </div>
                </div>

                <!-- User Info -->
                <div class="border-t pt-6 text-sm text-gray-600">
                    <p><strong>User ID:</strong> {{ $user->id }}</p>
                    <p><strong>Created:</strong> {{ $user->created_at->format('M d, Y h:i A') }}</p>
                    <p><strong>Last Updated:</strong> {{ $user->updated_at->format('M d, Y h:i A') }}</p>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                    <a 
                        href="{{ route('admin.users.index') }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200"
                    >
                        Cancel
                    </a>
                    <button 
                        type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200"
                    >
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

