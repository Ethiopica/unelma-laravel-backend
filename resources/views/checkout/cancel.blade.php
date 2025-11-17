<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Cancelled</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Rajdhani', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4">
<div class="max-w-md w-full space-y-6 bg-white p-8 rounded-xl shadow-lg text-center">
    <h1 class="text-3xl font-semibold text-gray-900">Checkout Cancelled</h1>
    <p class="text-gray-600">
        Your subscription checkout was cancelled. You can start again whenever you’re ready.
    </p>
    <a href="{{ url('/') }}"
       class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        Return Home
    </a>
</div>
</body>
</html>






