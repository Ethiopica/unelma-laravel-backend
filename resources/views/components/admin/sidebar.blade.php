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
                Newsletter Subscribers
            </a>
            <a href="{{ route('admin.contact-messages.index') }}"
                class="group flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition-all duration-200 @if (request()->routeIs('admin.contact-messages.*')) bg-blue-50 text-blue-700 border-l-4 border-blue-600 @else text-gray-700 hover:bg-gray-50 hover:text-gray-900 @endif">
                <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-all @if (request()->routeIs('admin.contact-messages.*')) bg-blue-100 text-blue-600 @else bg-gray-100 text-gray-600 group-hover:bg-gray-200 @endif">
                    <i class="fa-solid fa-envelope-open-text text-sm"></i>
                </div>
                Messages
            </a>
            <a href="{{ route('admin.favorites.index') }}"
                class="group flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition-all duration-200 @if (request()->routeIs('admin.favorites.*')) bg-blue-50 text-blue-700 border-l-4 border-blue-600 @else text-gray-700 hover:bg-gray-50 hover:text-gray-900 @endif">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all @if (request()->routeIs('admin.favorites.*')) bg-blue-100 text-blue-600 @else bg-gray-100 text-gray-600 group-hover:bg-gray-200 @endif">
                    <i class="fa-solid fa-heart text-sm"></i>
                </div>
                Favorites
            </a>
            <a href="{{ route('admin.ratings.index') }}"
                class="group flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition-all duration-200 @if (request()->routeIs('admin.ratings.*')) bg-blue-50 text-blue-700 border-l-4 border-blue-600 @else text-gray-700 hover:bg-gray-50 hover:text-gray-900 @endif">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all @if (request()->routeIs('admin.ratings.*')) bg-blue-100 text-blue-600 @else bg-gray-100 text-gray-600 group-hover:bg-gray-200 @endif">
                    <i class="fa-solid fa-star text-sm"></i>
                </div>
                Ratings
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

{{-- User Account Modal --}}
<div id="userModal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeUserModal()"></div>
    
    {{-- Modal Container --}}
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            {{-- Modal Content --}}
            <div class="relative w-full max-w-lg transform rounded-2xl bg-white shadow-2xl transition-all">
                {{-- Header --}}
                <div class="relative px-6 pt-6 pb-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900">Account Settings</h3>
                        <button onclick="closeUserModal()" 
                            class="rounded-full p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Profile Section --}}
                <div class="px-6 pb-6">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                        {{-- Avatar --}}
                        <div class="relative flex-shrink-0">
                            @if ($user?->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}"
                                    class="h-20 w-20 sm:h-24 sm:w-24 rounded-2xl object-cover border-4 border-white shadow-lg">
                            @else
                                <div class="h-20 w-20 sm:h-24 sm:w-24 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-2xl sm:text-3xl font-bold shadow-lg border-4 border-white">
                                    {{ $userInitial }}
                                </div>
                            @endif
                            {{-- Online indicator --}}
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-3 border-white rounded-full shadow"></div>
                        </div>

                        {{-- User Info --}}
                        <div class="flex-1 text-center sm:text-left min-w-0">
                            <h4 class="text-lg font-bold text-gray-900 truncate">{{ $user->name }}</h4>
                            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                            
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-2">
                                {{-- Role Badge --}}
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                    <i class="fa-solid fa-shield-halved mr-1.5 text-[10px]"></i>
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                                
                                {{-- Verification Badge --}}
                                @if ($user->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class="fa-solid fa-check-circle mr-1.5 text-[10px]"></i>
                                        Verified
                                    </span>
                                @else
                                    <a href="{{ route('verify.send') }}" 
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 hover:bg-amber-200 transition-colors">
                                        <i class="fa-solid fa-exclamation-circle mr-1.5 text-[10px]"></i>
                                        Verify Email
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="border-t border-gray-200"></div>

                {{-- Update Form --}}
                <div class="px-6 py-6">
                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Update Profile</h4>
                    
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        {{-- Name Input --}}
                        <div>
                            <label for="modal_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Full Name
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-user text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" name="name" id="modal_name" 
                                    value="{{ $user->name }}"
                                    placeholder="Enter your name"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow text-sm">
                            </div>
                        </div>

                        {{-- Email Input --}}
                        <div>
                            <label for="modal_email" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-envelope text-gray-400 text-sm"></i>
                                </div>
                                <input type="email" name="email" id="modal_email" 
                                    value="{{ $user->email }}"
                                    placeholder="Enter your email"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow text-sm">
                            </div>
                        </div>

                        {{-- Profile Picture Upload --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Profile Picture
                            </label>
                            <div class="flex items-center gap-4">
                                {{-- Preview --}}
                                <div class="flex-shrink-0">
                                    <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : '' }}" 
                                        alt="Preview" 
                                        id="modal_image_preview"
                                        class="h-16 w-16 rounded-xl object-cover border-2 border-gray-200 bg-gray-100 {{ $user->profile_picture ? '' : 'hidden' }}">
                                    <div id="modal_image_placeholder" 
                                        class="h-16 w-16 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50 {{ $user->profile_picture ? 'hidden' : '' }}">
                                        <i class="fa-solid fa-image text-gray-400"></i>
                                    </div>
                                </div>
                                
                                {{-- Upload Button --}}
                                <div class="flex-1">
                                    <label for="modal_photo" 
                                        class="flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                                        <i class="fa-solid fa-cloud-arrow-up text-gray-500"></i>
                                        <span class="text-sm text-gray-700">Choose file</span>
                                    </label>
                                    <input type="file" name="profile_picture" id="modal_photo" 
                                        accept="image/*"
                                        class="hidden"
                                        onchange="handleImagePreview(this)">
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-col-reverse sm:flex-row gap-3 pt-4">
                            <button type="button" onclick="closeUserModal()"
                                class="flex-1 sm:flex-none px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-1 sm:flex-none px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script for User Modal --}}
<script>
    function openUserModal(userId) {
        const modal = document.getElementById('userModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeUserModal() {
        const modal = document.getElementById('userModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function handleImagePreview(input) {
        const preview = document.getElementById('modal_image_preview');
        const placeholder = document.getElementById('modal_image_placeholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeUserModal();
        }
    });
</script>
