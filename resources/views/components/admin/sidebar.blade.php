@php
    $user = auth()->user();
    $userInitial = $user && $user->name ? strtoupper(mb_substr($user->name, 0, 1)) : '?';
@endphp

<aside class="flex h-full flex-col bg-white text-gray-700 shadow-lg">
    <div class="flex items-center px-6 py-5 border-b border-gray-200">
        <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold tracking-tight text-gray-900">
            Admin Panel
        </a>
        <button type="button" data-close-sidebar
            class="ml-auto inline-flex items-center justify-center rounded-md border border-gray-300 bg-white p-2 text-gray-500 transition hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 lg:hidden"
            aria-label="Close navigation">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
        <div>
            <p class="px-2 text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Overview</p>
            <a href="{{ route('admin.dashboard') }}"
                class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition @if(request()->routeIs('admin.dashboard')) bg-blue-100 text-blue-700 @else text-gray-600 hover:bg-gray-100 hover:text-gray-900 @endif">
                <i class="fa-solid fa-gauge group-hover:scale-110 transition-transform"></i>
                Dashboard
            </a>
        </div>

        @php
            $userFilter = request()->query('filter');
            $isUsersSection = request()->routeIs('admin.users.index');
            $isContentSection = request()->routeIs('admin.products.*') || request()->routeIs('admin.services.*') || request()->routeIs('admin.blogs.*');
        @endphp
        <div x-data="{ openUsers: {{ $isUsersSection ? 'true' : 'false' }}, openContent: {{ $isContentSection ? 'true' : 'false' }} }">
            <p class="px-2 text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Manage</p>
            <a href="{{ route('admin.carrers.index') }}"
                class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition @if(request()->routeIs('admin.carrers.*')) bg-blue-100 text-blue-700 @else text-gray-600 hover:bg-gray-100 hover:text-gray-900 @endif">
                <i class="fa-solid fa-briefcase group-hover:scale-110 transition-transform"></i>
                Vacancies & Careers
            </a>

            <div>
                <button type="button"
                    class="group flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    @click="openUsers = !openUsers"
                    :class="openUsers ? 'bg-blue-50 text-blue-700' : ''">
                    <span class="inline-flex items-center gap-3">
                        <i class="fa-solid fa-users group-hover:scale-110 transition-transform"></i>
                        User Management
                    </span>
                    <svg class="h-4 w-4 transition-transform" :class="openUsers ? 'rotate-180 text-blue-600' : 'text-gray-400'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openUsers" x-transition x-cloak class="mt-1 space-y-1 pl-7">
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition {{ $isUsersSection && ! in_array($userFilter, ['admin', 'customer'], true) ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fa-solid fa-users text-xs"></i>
                        All Users
                    </a>
                    <a href="{{ route('admin.users.index', ['filter' => 'admin']) }}"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition {{ $isUsersSection && $userFilter === 'admin' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fa-solid fa-user-shield text-xs"></i>
                        Admin Users
                    </a>
                    <a href="{{ route('admin.users.index', ['filter' => 'customer']) }}"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition {{ $isUsersSection && $userFilter === 'customer' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fa-solid fa-user-group text-xs"></i>
                        Customers
                    </a>
                </div>
            </div>

            <div>
                <button type="button"
                    class="group flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    @click="openContent = !openContent"
                    :class="openContent ? 'bg-blue-50 text-blue-700' : ''">
                    <span class="inline-flex items-center gap-3">
                        <i class="fa-solid fa-layer-group group-hover:scale-110 transition-transform"></i>
                        Content Management
                    </span>
                    <svg class="h-4 w-4 transition-transform" :class="openContent ? 'rotate-180 text-blue-600' : 'text-gray-400'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openContent" x-transition x-cloak class="mt-1 space-y-1 pl-7">
                    <a href="{{ route('admin.products.index') }}"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition @if(request()->routeIs('admin.products.*')) bg-blue-100 text-blue-700 @else text-gray-600 hover:bg-gray-100 hover:text-gray-900 @endif">
                        <i class="fa-solid fa-box text-xs"></i>
                        Products
                    </a>
                    <a href="{{ route('admin.services.index') }}"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition @if(request()->routeIs('admin.services.*')) bg-blue-100 text-blue-700 @else text-gray-600 hover:bg-gray-100 hover:text-gray-900 @endif">
                        <i class="fa-solid fa-briefcase text-xs"></i>
                        Services
                    </a>
                    <a href="{{ route('admin.blogs.index') }}"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition @if(request()->routeIs('admin.blogs.*')) bg-blue-100 text-blue-700 @else text-gray-600 hover:bg-gray-100 hover:text-gray-900 @endif">
                        <i class="fa-solid fa-blog text-xs"></i>
                        Blogs
                    </a>
                </div>
            </div>
            <a href="{{ route('admin.payments.index') }}"
                class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition @if(request()->routeIs('admin.payments.*')) bg-blue-100 text-blue-700 @else text-gray-600 hover:bg-gray-100 hover:text-gray-900 @endif">
                <i class="fa-solid fa-credit-card group-hover:scale-110 transition-transform"></i>
                Payments
            </a>
            <a href="{{ route('admin.contact-messages.index') }}"
                class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition @if(request()->routeIs('admin.contact-messages.*')) bg-blue-100 text-blue-700 @else text-gray-600 hover:bg-gray-100 hover:text-gray-900 @endif">
                <i class="fa-solid fa-envelope-open-text group-hover:scale-110 transition-transform"></i>
                Messages
            </a>
        </div>

        <div>
            <p class="px-2 text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Insights</p>
            <a href="{{ route('admin.reports.index') }}"
                class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition @if(request()->routeIs('admin.reports.*')) bg-blue-100 text-blue-700 @else text-gray-600 hover:bg-gray-100 hover:text-gray-900 @endif">
                <i class="fa-solid fa-chart-line group-hover:scale-110 transition-transform"></i>
                Reports
            </a>
        </div>

        <div>
            <p class="px-2 text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">System</p>
            <a href="{{ route('admin.settings.index') }}"
                class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition @if(request()->routeIs('admin.settings.*')) bg-blue-100 text-blue-700 @else text-gray-600 hover:bg-gray-100 hover:text-gray-900 @endif">
                <i class="fa-solid fa-gear group-hover:scale-110 transition-transform"></i>
                Settings
            </a>
        </div>
    </nav>

    <div class="mt-auto border-t border-gray-200 px-6 py-5">
        <div class="flex items-center space-x-4">
            @if ($user?->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}"
                    class="h-12 w-12 rounded-full object-cover border border-gray-300">
            @else
                <div
                    class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-lg font-semibold">
                    {{ $userInitial }}
                </div>
            @endif

            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-900">{{ $user?->name }}</p>
                <p class="text-xs text-gray-500">{{ $user?->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.logout') }}" class="mt-4">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Logout
            </button>
        </form>
    </div>
</aside>

