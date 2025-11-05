<!-- resources/views/emails/user-created.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Created Successfully</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">
    <div class="max-w-md mx-auto mt-20 bg-white shadow-md rounded-lg p-6 text-center">
        <h1 class="text-2xl font-bold text-green-600 mb-4">🎉 User Created Successfully!</h1>
        <p class="text-gray-700 mb-4">
            Hello {{ $user->name }},
        </p>
        <p class="text-gray-700 mb-4">
            Your account has been successfully created by an administrator. You can now log in and start exploring our platform.
        </p>
        <p class="text-gray-700 mb-2">
            <strong>Email:</strong> {{ $user->email }}
        </p>
        <p class="text-gray-700 mb-4">
            <strong>Account Type:</strong> {{ $user->is_admin ? 'Administrator' : 'Regular User' }}
        </p>
        <a href="{{ url('/admin/login') }}"
            class="inline-block mt-6 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Go to Login
        </a>
    </div>
</body>

</html>
