<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=General+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            font-family: "General Sans", "Space Grotesk", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-weight: 400;
            font-size: 17px;
        }

        .admin-shell {
            font-family: "General Sans", "Space Grotesk", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 16px;
            letter-spacing: -0.01em;
            font-feature-settings: "ss01", "ss02", "cv02", "kern";
        }

        .admin-shell h1,
        .admin-shell h2,
        .admin-shell h3,
        .admin-shell .section-heading,
        .admin-shell .card-title {
            font-family: "Space Grotesk", "General Sans", sans-serif;
            font-weight: 600;
            letter-spacing: -0.02em;
        }

        .admin-shell .card-title-subtle,
        .admin-shell .text-muted-modern {
            font-family: "General Sans", "Space Grotesk", sans-serif;
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
                                <form method="POST" action="{{ route('admin.logout') }}">
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
