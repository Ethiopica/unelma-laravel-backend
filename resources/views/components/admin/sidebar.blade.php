@php
    $user = auth()->user();
    // var_export($user->created_at['endOfTime']);
    // var_export($user->email_verified_at->date);
    $userInitial = $user && $user->name ? strtoupper(mb_substr($user->name, 0, 1)) : '?';
@endphp

<aside class="flex h-full flex-col bg-white text-gray-900 shadow-xl border-r border-gray-200">
    <div class="flex items-center px-6 py-5 border-b border-gray-200 bg-gray-50">
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-2 text-xl font-bold tracking-tight text-gray-900">
            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                <i class="fa-solid fa-shield-halved text-blue-600 text-sm"></i>
            </div>
            <span>Admin Panel</span>
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
            <p class="px-3 text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">
                Overview
            </p>
            <a href="{{ route('admin.dashboard') }}"
                class="group flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition-all duration-200 @if (request()->routeIs('admin.dashboard')) bg-blue-50 text-blue-700 border-l-4 border-blue-600 @else text-gray-700 hover:bg-gray-50 hover:text-gray-900 @endif">
                <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center @if (request()->routeIs('admin.dashboard')) bg-blue-100 text-blue-600 @else bg-gray-100 text-gray-600 group-hover:bg-gray-200 @endif transition-all">
                    <i class="fa-solid fa-gauge-high text-sm"></i>
                </div>
                Dashboard
            </a>
        </div>

        @php
            $userFilter = request()->query('filter');
            $isUsersSection = request()->routeIs('admin.users.index');
            $isContentSection =
                request()->routeIs('admin.products.*') ||
                request()->routeIs('admin.services.*') ||
                request()->routeIs('admin.blogs.*');
        @endphp
        <div x-data="{ openUsers: {{ $isUsersSection ? 'true' : 'false' }}, openContent: {{ $isContentSection ? 'true' : 'false' }} }">
            <p class="px-3 text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">
                Manage
            </p>
            <a href="{{ route('admin.carrers.index') }}"
                class="group flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition-all duration-200 @if (request()->routeIs('admin.carrers.*')) bg-blue-50 text-blue-700 border-l-4 border-blue-600 @else text-gray-700 hover:bg-gray-50 hover:text-gray-900 @endif">
                <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center @if (request()->routeIs('admin.carrers.*')) bg-blue-100 text-blue-600 @else bg-gray-100 text-gray-600 group-hover:bg-gray-200 @endif transition-all">
                    <i class="fa-solid fa-briefcase text-sm"></i>
                </div>
                Vacancies & Careers
            </a>

            <div>
                <button type="button"
                    class="group flex w-full items-center justify-between rounded-lg px-4 py-3 text-left text-sm font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    @click="openUsers = !openUsers"
                    :class="openUsers ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600' :
                        'text-gray-700 hover:bg-gray-50 hover:text-gray-900'">
                    <span class="inline-flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all"
                            :class="openUsers ? 'bg-blue-100 text-blue-600' :
                                'bg-gray-100 text-gray-600 group-hover:bg-gray-200'">
                            <i class="fa-solid fa-users text-sm"></i>
                        </div>
                        User Management
                    </span>
                    <svg class="h-4 w-4 transition-transform duration-200"
                        :class="openUsers ? 'rotate-180 text-blue-600' : 'text-gray-400'" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openUsers" x-transition x-cloak
                    class="mt-2 space-y-1 pl-4 ml-4 border-l-2 border-gray-200">
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ $isUsersSection && !in_array($userFilter, ['admin', 'customer'], true) ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-users text-xs"></i>
                        All Users
                    </a>
                    <a href="{{ route('admin.users.index', ['filter' => 'admin']) }}"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ $isUsersSection && $userFilter === 'admin' ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-user-shield text-xs"></i>
                        Admin Users
                    </a>
                    <a href="{{ route('admin.users.index', ['filter' => 'customer']) }}"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ $isUsersSection && $userFilter === 'customer' ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-user-group text-xs"></i>
                        Customers
                    </a>
                </div>
            </div>

            <div>
                <button type="button"
                    class="group flex w-full items-center justify-between rounded-lg px-4 py-3 text-left text-sm font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    @click="openContent = !openContent"
                    :class="openContent ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600' :
                        'text-gray-700 hover:bg-gray-50 hover:text-gray-900'">
                    <span class="inline-flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all"
                            :class="openContent ? 'bg-blue-100 text-blue-600' :
                                'bg-gray-100 text-gray-600 group-hover:bg-gray-200'">
                            <i class="fa-solid fa-layer-group text-sm"></i>
                        </div>
                        Content Management
                    </span>
                    <svg class="h-4 w-4 transition-transform duration-200"
                        :class="openContent ? 'rotate-180 text-blue-600' : 'text-gray-400'" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openContent" x-transition x-cloak
                    class="mt-2 space-y-1 pl-4 ml-4 border-l-2 border-gray-200">
                    <a href="{{ route('admin.products.index') }}"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 @if (request()->routeIs('admin.products.*')) bg-blue-50 text-blue-700 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif">
                        <i class="fa-solid fa-box text-xs"></i>
                        Products
                    </a>
                    <a href="{{ route('admin.services.index') }}"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 @if (request()->routeIs('admin.services.*')) bg-blue-50 text-blue-700 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif">
                        <i class="fa-solid fa-briefcase text-xs"></i>
                        Services
                    </a>
                    <a href="{{ route('admin.blogs.index') }}"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 @if (request()->routeIs('admin.blogs.*')) bg-blue-50 text-blue-700 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif">
                        <i class="fa-solid fa-blog text-xs"></i>
                        Blogs
                    </a>
                </div>
            </div>
            <a href="{{ route('admin.payments.index') }}"
                class="group flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition-all duration-200 @if (request()->routeIs('admin.payments.*')) bg-blue-50 text-blue-700 border-l-4 border-blue-600 @else text-gray-700 hover:bg-gray-50 hover:text-gray-900 @endif">
                <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-all @if (request()->routeIs('admin.payments.*')) bg-blue-100 text-blue-600 @else bg-gray-100 text-gray-600 group-hover:bg-gray-200 @endif">
                    <i class="fa-solid fa-credit-card text-sm"></i>
                </div>
                Payments
            </a>
            <a href="{{ route('admin.subscribers.index') }}"
                class="group flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition-all duration-200 @if (request()->routeIs('admin.subscribers.*')) bg-blue-50 text-blue-700 border-l-4 border-blue-600 @else text-gray-700 hover:bg-gray-50 hover:text-gray-900 @endif">
                <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-all @if (request()->routeIs('admin.subscribers.*')) bg-blue-100 text-blue-600 @else bg-gray-100 text-gray-600 group-hover:bg-gray-200 @endif">
                    <i class="fa-solid fa-envelope-circle-check text-sm"></i>
                </div>
                Subscribers
            </a>
            <a href="{{ route('admin.contact-messages.index') }}"
                class="group flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition-all duration-200 @if (request()->routeIs('admin.contact-messages.*')) bg-blue-50 text-blue-700 border-l-4 border-blue-600 @else text-gray-700 hover:bg-gray-50 hover:text-gray-900 @endif">
                <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-all @if (request()->routeIs('admin.contact-messages.*')) bg-blue-100 text-blue-600 @else bg-gray-100 text-gray-600 group-hover:bg-gray-200 @endif">
                    <i class="fa-solid fa-envelope-open-text text-sm"></i>
                </div>
                Messages
            </a>
        </div>

        <div>
            <p class="px-3 text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">
                Insights
            </p>
            <a href="{{ route('admin.reports.index') }}"
                class="group flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition-all duration-200 @if (request()->routeIs('admin.reports.*')) bg-blue-50 text-blue-700 border-l-4 border-blue-600 @else text-gray-700 hover:bg-gray-50 hover:text-gray-900 @endif">
                <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-all @if (request()->routeIs('admin.reports.*')) bg-blue-100 text-blue-600 @else bg-gray-100 text-gray-600 group-hover:bg-gray-200 @endif">
                    <i class="fa-solid fa-chart-line text-sm"></i>
                </div>
                Reports
            </a>
        </div>

        <div>
            <p class="px-3 text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">
                System
            </p>
            <a href="{{ route('admin.settings.index') }}"
                class="group flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition-all duration-200 @if (request()->routeIs('admin.settings.*')) bg-blue-50 text-blue-700 border-l-4 border-blue-600 @else text-gray-700 hover:bg-gray-50 hover:text-gray-900 @endif">
                <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-all @if (request()->routeIs('admin.settings.*')) bg-blue-100 text-blue-600 @else bg-gray-100 text-gray-600 group-hover:bg-gray-200 @endif">
                    <i class="fa-solid fa-gear text-sm"></i>
                </div>
                Settings
            </a>
        </div>
    </nav>

    <div class="mt-auto border-t border-gray-200 bg-gray-50 px-6 py-5">
        <div class="flex items-center space-x-3 mb-4">
            @if ($user?->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}"
                    class="h-12 w-12 rounded-xl object-cover border-2 border-gray-200 shadow-md">
            @else
                <div
                    class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-lg font-bold shadow-lg">
                    {{ $userInitial }}
                </div>
            @endif

            <div class="flex-1 min-w-0">
                <div class='flex items-center gap-2'>
                    <p class="text-sm font-bold text-gray-900 truncate">{{ $user?->name }}</p>
                    @if ($user->email_verified_at)
                        <span
                            class="inline-flex justify-center items-center px-2 py-0.5 rounded-full bg-green-100 text-[9px] font-semibold text-green-800 border border-green-200">
                            <i class="fa-solid fa-check-circle text-[8px] mr-1"></i>Verified
                        </span>
                    @else
                        <span
                            class="inline-flex items-center justify-center rounded-full bg-red-100 px-2 py-0.5 text-[9px] font-semibold text-red-800 border border-red-200">
                            <i class="fa-solid fa-exclamation-circle text-[8px] mr-1"></i>Unverified
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-600 truncate">{{ $user?->email }}</p>
            </div>
            <button onclick="openUserModal({{ $user->id }})"
                class="flex-shrink-0 w-8 h-8 rounded-lg bg-white border border-gray-300 hover:bg-gray-50 flex items-center justify-center text-gray-600 hover:text-gray-900 transition-all duration-200">
                <i class="fa-solid fa-gear text-xs"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Logout
            </button>
        </form>
    </div>
</aside>

{{-- //User Account Model here --}}
<div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Your Account Details</h3>
            <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <div class="flex-col gap-6">
            <div class="flex gap-2">
                @if ($user?->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}"
                        class="h-20 w-20 rounded-full object-cover border border-gray-300">
                @else
                    <div
                        class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-lg font-semibold">
                        {{ $userInitial }}
                    </div>
                @endif
                <div>
                    <h2><strong>Name: </strong>{{ $user->name }}</h2>
                    <div>
                        <h2><strong>Email: </strong>{{ $user->email }}</h2>
                        @if (isset($user->email_verified_at))
                            <span
                                class="inline-flex justify-center items-center px-2 py-0.5 rounded-full bg-green-100 text-[9px] font-semibold text-green-800 border border-green-200">
                                <i class="fa-solid fa-check-circle text-[8px] mr-1"></i>Verified
                            </span>
                        @else
                            <a href="{{ route('verify.user') }}"
                                class="bg-blue-50 text-blue-700 p-[2px] rounded-sm">Click to Verify Your
                                email</a>
                        @endif
                    </div>
                    <h2><strong>role: </strong>{{ $user->role }}</h2>
                </div>
            </div>
            {{-- @if (!$user->role == 'super_admin') --}}
            <div class="mt-3">
                <form action="{{ route('admin.users.update', $user->id) }}" class="flex flex-col gap-1"
                    enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name" class="">Name:</label>
                        <input type="text" name="name" id="name" placeholder="Update Your Name"
                            class="rounded-sm px-2">
                    </div>
                    <div>
                        <label for="email">Email:</label>
                        <input type="text" name="email" id="email" placeholder="Update Your email"
                            class="rounded-sm px-2">
                    </div>
                    <div>
                        <div><img src="" alt="Preview Profile Picture" id='image' width="200px" />
                        </div>
                        <label for="photo">Upload Your photo:</label>
                        <input type="file" name="profile_picture" id="photo" placeholder="Upload your photo"
                            onchange="document.getElementById('image').src=window.URL.createObjectURL(this.files[0])">
                    </div>
                    <input type="submit" value="Update"
                        class="p-2 bg-blue-50 text-blue-700 w-fit rounded-md cursor-pointer">
                </form>
            </div>
            {{-- @else
                <h2> You cannot edit the super Admin things here</h2>
            @endif --}}
        </div>
        <div class="flex justify-end space-x-3 mt-6 pt-4">
            <button onclick="closeUserModal()"
                class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-gray-400 transition duration-200">
                Close
            </button>
        </div>
    </div>
</div>


{{-- //script for User Modal --}}
<script>
    function openUserModal(userId) {
        document.getElementById('userModal').classList.remove('hidden');
    }

    function closeUserModal() {
        userModal
        document.getElementById('userModal').classList.add('hidden');
    }
</script>
