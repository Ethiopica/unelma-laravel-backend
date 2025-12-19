<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=General+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
            if (document.body) {
                document.body.setAttribute('data-theme', savedTheme);
            } else {
                document.addEventListener('DOMContentLoaded', function() {
                    document.body.setAttribute('data-theme', savedTheme);
                });
            }
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        [x-cloak] {
            display: none !important;
        }
        body {
            font-family: "Poppins", "General Sans", "Space Grotesk", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-weight: 400;
            font-size: 17px;
        }

        .admin-shell {
            font-family: "Poppins", "General Sans", "Space Grotesk", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 16px;
            letter-spacing: -0.01em;
            font-feature-settings: "ss01", "ss02", "cv02", "kern";
        }

        .admin-shell h1,
        .admin-shell h2,
        .admin-shell h3,
        .admin-shell .section-heading,
        .admin-shell .card-title {
            font-family: "Poppins", "Space Grotesk", "General Sans", sans-serif;
            font-weight: 600;
            letter-spacing: -0.02em;
        }

        .admin-shell .card-title-subtle,
        .admin-shell .text-muted-modern {
            font-family: "Poppins", "General Sans", "Space Grotesk", sans-serif;
            font-weight: 500;
            letter-spacing: 0.015em;
        }

        /* Theme Variables - Dark mode default */
        :root,
        html,
        :root[data-theme="dark"],
        html[data-theme="dark"] {
            --color-primary: #27413C;
            --color-secondary: #102B27;
            --color-font: #75D7CB;
            --color-surface: #142F2A;
            --color-surface-alt: #0B1F1C;
            --color-border: rgba(117, 215, 203, 0.25);
            --color-muted: rgba(117, 215, 203, 0.7);
        }

        /* Theme Variables - Light mode */
        :root[data-theme="light"],
        html[data-theme="light"] {
            --color-primary: #E8F5F3;
            --color-secondary: #F0FDFA;
            --color-font: #102B27;
            --color-surface: #FFFFFF;
            --color-surface-alt: #F9FAFB;
            --color-border: rgba(16, 43, 39, 0.2);
            --color-muted: rgba(16, 43, 39, 0.6);
        }

        html[data-theme="dark"] body,
        body[data-theme="dark"],
        html[data-theme="dark"] .admin-shell,
        body[data-theme="dark"] .admin-shell {
            background-color: var(--color-secondary);
            color: var(--color-font);
        }

        html[data-theme="light"] body,
        body[data-theme="light"],
        html[data-theme="light"] .admin-shell,
        body[data-theme="light"] .admin-shell {
            background-color: var(--color-secondary);
            color: var(--color-font);
        }

        html[data-theme="dark"] .bg-gray-100,
        body[data-theme="dark"] .bg-gray-100,
        html[data-theme="dark"] .admin-shell .bg-gray-100 {
            background-color: var(--color-surface-alt) !important;
            color: var(--color-font) !important;
        }

        html[data-theme="light"] .bg-gray-100,
        body[data-theme="light"] .bg-gray-100,
        html[data-theme="light"] .admin-shell .bg-gray-100 {
            background-color: var(--color-secondary) !important;
            color: var(--color-font) !important;
        }

        html[data-theme="dark"] .bg-\[#102B27\],
        body[data-theme="dark"] .bg-\[#102B27\],
        html[data-theme="dark"] main.bg-\[#102B27\] {
            background-color: #102B27 !important;
        }

        html[data-theme="light"] .bg-\[#102B27\],
        body[data-theme="light"] .bg-\[#102B27\],
        html[data-theme="light"] main.bg-\[#102B27\] {
            background-color: var(--color-secondary) !important;
        }

        html[data-theme="dark"] .bg-\[#27413C\],
        body[data-theme="dark"] .bg-\[#27413C\] {
            background-color: var(--color-primary) !important;
        }

        html[data-theme="light"] .bg-\[#27413C\],
        body[data-theme="light"] .bg-\[#27413C\] {
            background-color: var(--color-surface) !important;
        }

        html[data-theme="dark"] .text-\[#75D7CB\],
        body[data-theme="dark"] .text-\[#75D7CB\],
        html[data-theme="dark"] [class*="text-[#75D7CB]"]:not([class*="/"]) {
            color: var(--color-font) !important;
        }

        html[data-theme="light"] .text-\[#75D7CB\],
        body[data-theme="light"] .text-\[#75D7CB\],
        html[data-theme="light"] [class*="text-[#75D7CB]"]:not([class*="/"]) {
            color: var(--color-font) !important;
        }

        html[data-theme="dark"] .bg-white,
        html[data-theme="dark"] .bg-gray-50,
        html[data-theme="dark"] .bg-gray-100 {
            background-color: var(--color-surface) !important;
            color: var(--color-font) !important;
            border-color: var(--color-border) !important;
        }

        html[data-theme="light"] .bg-white,
        html[data-theme="light"] .bg-gray-50,
        html[data-theme="light"] .bg-gray-100 {
            background-color: var(--color-surface) !important;
            color: var(--color-font) !important;
            border-color: var(--color-border) !important;
        }

        html[data-theme="dark"] .text-gray-900,
        html[data-theme="dark"] .text-gray-800,
        html[data-theme="dark"] .text-gray-700,
        html[data-theme="dark"] .text-gray-600,
        html[data-theme="dark"] .text-gray-500 {
            color: var(--color-font) !important;
        }

        html[data-theme="light"] .text-gray-900,
        html[data-theme="light"] .text-gray-800,
        html[data-theme="light"] .text-gray-700,
        html[data-theme="light"] .text-gray-600,
        html[data-theme="light"] .text-gray-500 {
            color: var(--color-font) !important;
        }

        /* Active/Selected states in dark mode - use #1F5D54 */
        html[data-theme="dark"] .bg-blue-50,
        html[data-theme="dark"] [class*="bg-blue-50"] {
            background-color: #1F5D54 !important;
        }

        html[data-theme="dark"] .bg-blue-100,
        html[data-theme="dark"] [class*="bg-blue-100"] {
            background-color: #1F5D54 !important;
        }

        html[data-theme="dark"] .border-blue-600,
        html[data-theme="dark"] [class*="border-blue-600"] {
            border-color: #1F5D54 !important;
        }

        html[data-theme="dark"] .text-blue-700,
        html[data-theme="dark"] [class*="text-blue-700"] {
            color: var(--color-font) !important;
        }

        html[data-theme="dark"] .text-blue-600,
        html[data-theme="dark"] [class*="text-blue-600"] {
            color: var(--color-font) !important;
        }

        /* Status badge text colors in dark mode - use #75D7CB */
        html[data-theme="dark"] .status-badge,
        body[data-theme="dark"] .status-badge {
            color: #75D7CB !important;
        }

        /* Favorite type text color in light mode - use black */
        html[data-theme="light"] .favorite-type,
        body[data-theme="light"] .favorite-type {
            color: #000000 !important;
        }

        /* Content management cards - highlight controls in dark mode */
        html[data-theme="dark"] .product-price,
        html[data-theme="dark"] .product-edit-link,
        html[data-theme="dark"] .service-edit-link,
        html[data-theme="dark"] .blog-edit-link,
        html[data-theme="dark"] .blog-edit-button {
            color: #74D7CB !important;
        }

        html[data-theme="dark"] .product-edit-link svg,
        html[data-theme="dark"] .service-edit-link svg,
        html[data-theme="dark"] .blog-edit-link svg,
        html[data-theme="dark"] .blog-edit-button svg {
            color: #74D7CB !important;
        }

        html[data-theme="dark"] .blog-edit-button {
            border-color: rgba(116, 215, 203, 0.6) !important;
        }

        /* Delete action styling */
        .content-delete-action {
            color: #E3E174;
            border-color: rgba(227, 225, 116, 0.65);
            transition: color 0.2s ease, border-color 0.2s ease;
        }

        .content-delete-action svg {
            color: inherit;
        }

        .content-delete-action:hover {
            color: #d4d262;
            border-color: rgba(227, 225, 116, 0.8);
        }

        html[data-theme="light"] .content-delete-action {
            color: #DC2626 !important;
            border-color: rgba(220, 38, 38, 0.6) !important;
        }

        html[data-theme="light"] .content-delete-action svg {
            color: #DC2626 !important;
        }

        html[data-theme="light"] .content-delete-action:hover {
            color: #B91C1C !important;
            border-color: rgba(185, 28, 28, 0.75) !important;
        }

        /* Account owner card theming */
        html[data-theme="dark"] .account-owner-card {
            background: linear-gradient(135deg, #1B3C38, #0F2422 60%, #0B1B1A);
            border: 1px solid rgba(117, 215, 203, 0.25);
            color: var(--color-font);
            box-shadow: 0 25px 50px -12px rgba(7, 21, 18, 0.6);
        }

        html[data-theme="light"] .account-owner-card {
            background: linear-gradient(120deg, #E8F5F3, #FFFFFF 65%, #F0FDFA);
            border: 1px solid rgba(16, 43, 39, 0.15);
            color: var(--color-font);
            box-shadow: 0 15px 35px -20px rgba(16, 43, 39, 0.4);
        }

        html[data-theme="dark"] .account-owner-card .text-white,
        html[data-theme="dark"] .account-owner-card .text-white\/70,
        html[data-theme="dark"] .account-owner-card .text-white\/80,
        html[data-theme="dark"] .account-owner-card [class*="text-white"] {
            color: var(--color-font) !important;
        }

        html[data-theme="light"] .account-owner-card .text-white,
        html[data-theme="light"] .account-owner-card .text-white\/70,
        html[data-theme="light"] .account-owner-card .text-white\/80,
        html[data-theme="light"] .account-owner-card [class*="text-white"] {
            color: #102B27 !important;
        }

        html[data-theme="dark"] .account-owner-card .bg-white\/20,
        html[data-theme="dark"] .account-owner-card .bg-white\/15,
        html[data-theme="dark"] .account-owner-card .bg-white\/90,
        html[data-theme="dark"] .account-owner-card [class*="bg-white"],
        html[data-theme="dark"] .bg-white\/90 {
            background-color: rgba(117, 215, 203, 0.9) !important;
            color: var(--color-secondary) !important;
            border-color: rgba(117, 215, 203, 0.35) !important;
        }

        html[data-theme="light"] .account-owner-card .bg-white\/20,
        html[data-theme="light"] .account-owner-card .bg-white\/15,
        html[data-theme="light"] .account-owner-card .bg-white\/90,
        html[data-theme="light"] .account-owner-card [class*="bg-white"],
        html[data-theme="light"] .bg-white\/90 {
            background-color: rgba(255, 255, 255, 0.9) !important;
            color: #102B27 !important;
            border-color: rgba(16, 43, 39, 0.2) !important;
        }

        html[data-theme="dark"] .account-owner-card .border-white\/30,
        html[data-theme="dark"] .account-owner-card [class*="border-white"] {
            border-color: rgba(117, 215, 203, 0.35) !important;
        }

        html[data-theme="light"] .account-owner-card .border-white\/30,
        html[data-theme="light"] .account-owner-card [class*="border-white"] {
            border-color: rgba(16, 43, 39, 0.2) !important;
        }

        html[data-theme="dark"] .account-owner-card .bg-emerald-100\/30 {
            background-color: rgba(16, 185, 129, 0.25) !important;
            color: var(--color-font) !important;
        }

        html[data-theme="light"] .account-owner-card .bg-emerald-100\/30 {
            background-color: rgba(16, 185, 129, 0.15) !important;
            color: #0E2723 !important;
        }

        /* Apply account owner card button styles to text-indigo-700 buttons (used in vacancy buttons) */
        html[data-theme="dark"] .text-indigo-700 {
            color: var(--color-secondary) !important;
        }

        html[data-theme="light"] .text-indigo-700 {
            color: #102B27 !important;
        }

        /* Hover state for bg-white/90 buttons (matches account owner card button hover) */
        html[data-theme="dark"] .bg-white\/90:hover {
            background-color: rgba(117, 215, 203, 1) !important;
        }

        html[data-theme="light"] .bg-white\/90:hover {
            background-color: rgba(255, 255, 255, 1) !important;
        }

        /* Dark mode hover effects - use #123530 */
        html[data-theme="dark"] .hover\:bg-gray-50:hover,
        html[data-theme="dark"] .hover\:bg-gray-100:hover,
        html[data-theme="dark"] .hover\:bg-white:hover,
        html[data-theme="dark"] tr:hover,
        html[data-theme="dark"] tbody tr:hover,
        html[data-theme="dark"] a:hover:not(.nav-link):not(.cta-button):not(.content-delete-action),
        html[data-theme="dark"] button:hover:not(.content-delete-action):not([class*="bg-red"]):not([class*="bg-blue"]),
        html[data-theme="dark"] [class*="hover:bg-gray"]:hover,
        html[data-theme="dark"] [class*="hover:bg-white"]:hover {
            background-color: #123530 !important;
        }

        /* Dark mode hover for sidebar and navigation items */
        html[data-theme="dark"] .group:hover:not([class*="bg-blue"]):not([class*="bg-red"]),
        html[data-theme="dark"] nav a:hover:not(.nav-link):not(.cta-button),
        html[data-theme="dark"] aside a:hover:not([class*="bg-blue"]),
        html[data-theme="dark"] aside button:hover:not([class*="bg-red"]),
        html[data-theme="dark"] .group-hover\:bg-gray-200.group:hover,
        html[data-theme="dark"] [class*="group-hover:bg-gray"]:hover {
            background-color: #123530 !important;
        }

        /* Dark mode hover for icon containers in sidebar */
        html[data-theme="dark"] .group:hover [class*="group-hover:bg-gray"] {
            background-color: #123530 !important;
        }

        /* Dashboard specific dark mode styling */
        /* Summary cards borders in dark mode */
        html[data-theme="dark"] .border-blue-200,
        html[data-theme="dark"] .border-green-200,
        html[data-theme="dark"] .border-purple-200,
        html[data-theme="dark"] .border-amber-200,
        html[data-theme="dark"] .border-cyan-200,
        html[data-theme="dark"] .border-indigo-200 {
            border-color: var(--color-border) !important;
        }

        /* Icon background colors in dark mode - make them more subtle */
        html[data-theme="dark"] .bg-blue-100,
        html[data-theme="dark"] .bg-green-100,
        html[data-theme="dark"] .bg-purple-100,
        html[data-theme="dark"] .bg-amber-100,
        html[data-theme="dark"] .bg-cyan-100,
        html[data-theme="dark"] .bg-indigo-100 {
            background-color: rgba(31, 93, 84, 0.3) !important;
        }

        /* Icon text colors in dark mode */
        html[data-theme="dark"] .text-blue-600,
        html[data-theme="dark"] .text-green-600,
        html[data-theme="dark"] .text-purple-600,
        html[data-theme="dark"] .text-amber-600,
        html[data-theme="dark"] .text-cyan-600,
        html[data-theme="dark"] .text-indigo-600 {
            color: var(--color-font) !important;
        }

        /* Group hover icon backgrounds in dark mode */
        html[data-theme="dark"] .group-hover\:bg-blue-200:hover,
        html[data-theme="dark"] .group-hover\:bg-green-200:hover,
        html[data-theme="dark"] .group-hover\:bg-purple-200:hover,
        html[data-theme="dark"] .group-hover\:bg-amber-200:hover,
        html[data-theme="dark"] .group-hover\:bg-cyan-200:hover,
        html[data-theme="dark"] .group-hover\:bg-indigo-200:hover {
            background-color: rgba(31, 93, 84, 0.5) !important;
        }

        /* Success message in dark mode */
        html[data-theme="dark"] .bg-green-100 {
            background-color: rgba(31, 93, 84, 0.2) !important;
            border-color: rgba(31, 93, 84, 0.5) !important;
        }

        html[data-theme="dark"] .text-green-700 {
            color: var(--color-font) !important;
        }

        html[data-theme="dark"] .border-green-400 {
            border-color: rgba(31, 93, 84, 0.6) !important;
        }

        /* Quick action card hover borders in dark mode */
        html[data-theme="dark"] .hover\:border-blue-300:hover {
            border-color: var(--color-border) !important;
        }

        /* Category badge styling for dark mode - use #75D7CB */
        html[data-theme="dark"] .category-badge {
            background-color: rgba(31, 93, 84, 0.4) !important;
            color: #75D7CB !important;
            border: 1px solid rgba(117, 215, 203, 0.3);
        }

        html[data-theme="light"] .category-badge {
            background-color: #F3E8FF !important;
            color: #6B21A8 !important;
        }

        /* Blog comments link/button styling */
        html[data-theme="dark"] .blog-comments-link,
        html[data-theme="dark"] .blog-comments-button {
            color: #75D7CB !important;
            border-color: rgba(117, 215, 203, 0.5) !important;
        }

        html[data-theme="dark"] .blog-comments-link svg {
            color: #75D7CB !important;
        }

        html[data-theme="light"] .blog-comments-link,
        html[data-theme="light"] .blog-comments-button {
            color: #7C3AED !important;
            border-color: #7C3AED !important;
        }

        html[data-theme="light"] .blog-comments-link svg {
            color: #7C3AED !important;
        }

        /* Form input styling for dark mode */
        html[data-theme="dark"] input[type="text"],
        html[data-theme="dark"] input[type="email"],
        html[data-theme="dark"] input[type="number"],
        html[data-theme="dark"] input[type="password"],
        html[data-theme="dark"] input[type="file"],
        html[data-theme="dark"] textarea,
        html[data-theme="dark"] select {
            background-color: var(--color-surface-alt) !important;
            color: #75D7CB !important;
            border-color: var(--color-border) !important;
        }

        html[data-theme="dark"] input[type="text"]::placeholder,
        html[data-theme="dark"] input[type="email"]::placeholder,
        html[data-theme="dark"] input[type="number"]::placeholder,
        html[data-theme="dark"] input[type="password"]::placeholder,
        html[data-theme="dark"] textarea::placeholder {
            color: rgba(117, 215, 203, 0.5) !important;
        }

        html[data-theme="dark"] input[type="text"]:focus,
        html[data-theme="dark"] input[type="email"]:focus,
        html[data-theme="dark"] input[type="number"]:focus,
        html[data-theme="dark"] input[type="password"]:focus,
        html[data-theme="dark"] textarea:focus,
        html[data-theme="dark"] select:focus {
            border-color: #75D7CB !important;
            box-shadow: 0 0 0 2px rgba(117, 215, 203, 0.2) !important;
        }

        /* Labels styling for dark mode */
        html[data-theme="dark"] label {
            color: #75D7CB !important;
        }

        /* Section headings in forms */
        html[data-theme="dark"] .border-t {
            border-color: var(--color-border) !important;
        }

        /* Checkbox styling for dark mode */
        html[data-theme="dark"] input[type="checkbox"] {
            background-color: var(--color-surface-alt) !important;
            border-color: var(--color-border) !important;
        }

        html[data-theme="dark"] input[type="checkbox"]:checked {
            background-color: #1F5D54 !important;
            border-color: #75D7CB !important;
        }

        /* Breadcrumb navigation styling for dark mode */
        html[data-theme="dark"] .breadcrumb-nav,
        html[data-theme="dark"] .breadcrumb-nav a,
        html[data-theme="dark"] .breadcrumb-nav span {
            color: #75D7CB !important;
        }

        html[data-theme="dark"] .breadcrumb-nav a:hover {
            color: #A8E8E0 !important;
        }

        /* Section heading h3 styling for dark mode */
        html[data-theme="dark"] h3.text-lg {
            color: #75D7CB !important;
        }

        /* Helper text styling for dark mode */
        html[data-theme="dark"] p.text-sm.text-gray-500,
        html[data-theme="dark"] .text-sm.text-gray-500 {
            color: rgba(117, 215, 203, 0.7) !important;
        }

        /* Error text styling - keep red for visibility */
        html[data-theme="dark"] .text-red-500,
        html[data-theme="dark"] .text-red-600 {
            color: #F87171 !important;
        }

        /* Blog info section in edit form */
        html[data-theme="dark"] .bg-gray-50.rounded-lg.p-4 p.text-gray-600 {
            color: rgba(117, 215, 203, 0.7) !important;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen" data-theme="dark">
    @php
        $routeName = optional(request()->route())->getName();
        $adminSidebarExclusions = ['admin.login'];
        $shouldUseAdminShell =
            request()->is('admin/*') &&
            !request()->is('admin/login') &&
            !request()->is('admin/password*') &&
            !in_array($routeName, $adminSidebarExclusions, true);
    @endphp

    @if ($shouldUseAdminShell)
        @php($authUser = auth()->user())
        <div class="admin-shell min-h-screen bg-gray-100">
            <div class="fixed top-4 right-4 z-40">
                <x-admin.theme-toggle />
            </div>
            <div class="flex min-h-screen">
                <div id="admin-sidebar"
                    class="fixed inset-y-0 left-0 z-40 w-72 transform bg-white transition-transform duration-300 ease-in-out -translate-x-full shadow-lg lg:translate-x-0 lg:static lg:inset-auto lg:transform-none">
                    <x-admin.sidebar />
                </div>

                <div class="flex-1 flex flex-col min-h-screen">
                    <header
                        class="flex items-center justify-between border-b border-gray-200 bg-white px-4 py-3 shadow-sm lg:hidden">
                        <div class="flex items-center gap-3">
                            <button type="button" id="admin-sidebar-open"
                                class="rounded-md border border-gray-300 bg-white p-2 text-gray-600 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                aria-label="Open navigation">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <span class="text-lg font-semibold text-gray-900">Admin Panel</span>
                        </div>
                        @if ($authUser)
                            {{-- Show this logout button only on large screens; mobile uses sidebar/account area --}}
                            <form method="POST" action="{{ route('admin.logout') }}" class="hidden lg:block">
                                @csrf
                                <button type="submit"
                                    class="rounded-md bg-red-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    Logout
                                </button>
                            </form>
                        @endif
                    </header>

                    <main class="flex-1 bg-gray-100 overflow-y-auto">
                        <div class="px-4 py-6 sm:px-6 lg:px-10">
                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>

            <div id="admin-sidebar-backdrop"
                class="fixed inset-0 z-30 hidden bg-gray-900/50 backdrop-blur-sm lg:hidden"></div>
        </div>
    @else
        {{ $slot }}
    @endif

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    @if ($shouldUseAdminShell)
        <script src="{{ asset('js/theme-switcher.js') }}" defer></script>
    @endif
    <script>
        (function() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('admin-sidebar-backdrop');
            const openButton = document.getElementById('admin-sidebar-open');

            if (!sidebar || !backdrop || !openButton) {
                return;
            }

            const closeButtons = sidebar.querySelectorAll('[data-close-sidebar]');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }

            openButton.addEventListener('click', openSidebar);
            backdrop.addEventListener('click', closeSidebar);

            closeButtons.forEach((button) => {
                button.addEventListener('click', closeSidebar);
            });

            sidebar.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        closeSidebar();
                    }
                });
            });

            window.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.add('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        })();
    </script>
    @stack('scripts')
</body>

</html>
