<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Cancelled</title>
    <!-- Font Loading Optimization for All Screen Sizes -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.gstatic.com/s/rajdhani/v15/LDI2apCSOBg7S-QT7pb0EPOqeeHkkbIxyyg.woff2" as="font" type="font/woff2" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Inline @font-face with font-display: swap for immediate visibility */
        @font-face {
            font-family: 'Rajdhani';
            font-style: normal;
            font-weight: 500 600;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/rajdhani/v15/LDI2apCSOBg7S-QT7pb0EPOqeeHkkbIxyyg.woff2) format('woff2');
        }
        
        /* Font metric overrides to reduce layout shift on all screens */
        @font-face {
            font-family: 'Rajdhani Fallback';
            src: local('Arial'), local('Helvetica Neue'), local('Helvetica'), local('sans-serif');
            font-display: swap;
            size-adjust: 105%;
            ascent-override: 100%;
            descent-override: 30%;
            line-gap-override: 5%;
        }
        
        body {
            font-family: 'Rajdhani', 'Rajdhani Fallback', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
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






