<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>


</head>

<body class="bg-gray-100 min-h-screen">
    {{ $slot }}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>


</body>

</html>
