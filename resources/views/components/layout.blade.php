<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    @php
        $routeName = optional(request()->route())->getName();
        $adminSidebarExclusions = ['admin.login'];
        $shouldUseAdminShell = request()->is('admin/*') && ! request()->is('admin/login') && ! request()->is('admin/password*') && ! in_array($routeName, $adminSidebarExclusions, true);
    @endphp

    @if ($shouldUseAdminShell)
        @php($authUser = auth()->user())
        <div class="min-h-screen bg-gray-100">
            <div class="flex min-h-screen">
                <div id="admin-sidebar"
                    class="fixed inset-y-0 left-0 z-40 w-72 transform bg-white transition-transform duration-300 ease-in-out -translate-x-full shadow-lg lg:translate-x-0 lg:static lg:inset-auto lg:transform-none">
                    <x-admin.sidebar />
                </div>

                <div class="flex-1 flex flex-col min-h-screen">
                    <header class="flex items-center justify-between border-b border-gray-200 bg-white px-4 py-3 shadow-sm lg:hidden">
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

            <div id="admin-sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-gray-900/50 backdrop-blur-sm lg:hidden"></div>
        </div>
    @else
        {{ $slot }}
    @endif

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script>
        (function () {
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
