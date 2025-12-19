<x-layout>
    <x-slot:title>
        Payments - {{ config('app.name') }}
    </x-slot:title>

    <style>
        /* Dark mode styling for Past Due, Failed, Refund, Revenue numbers */
        html[data-theme="dark"] .stat-past-due,
        html[data-theme="dark"] .stat-failed,
        html[data-theme="dark"] .stat-refund,
        html[data-theme="dark"] .stat-revenue {
            color: #75D7CB !important;
        }
        /* Dark mode styling for icon pads */
        html[data-theme="dark"] .icon-pad-past-due,
        html[data-theme="dark"] .icon-pad-failed,
        html[data-theme="dark"] .icon-pad-refund,
        html[data-theme="dark"] .icon-pad-revenue,
        html[data-theme="dark"] .icon-pad-canceled {
            background-color: #173832 !important;
        }
        /* Dark mode styling for icons */
        html[data-theme="dark"] .icon-past-due,
        html[data-theme="dark"] .icon-failed,
        html[data-theme="dark"] .icon-refund,
        html[data-theme="dark"] .icon-revenue {
            color: #75D7CB !important;
        }
    </style>

    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-3 sm:py-6 lg:py-8 space-y-3 sm:space-y-6">
        
        <!-- Header Section - 375px Optimized -->
        <div class="space-y-3">
            <div>
                <h1 class="text-lg sm:text-2xl font-bold text-gray-900">Payments</h1>
                <p class="text-xs sm:text-base text-gray-600 mt-0.5">Subscriptions & purchases</p>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-blue-600 px-3 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                    <i class="fa-solid fa-user-plus text-[10px]"></i>
                    <span>New Customer</span>
                </a>
                <a href="https://dashboard.stripe.com/" target="_blank"
                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs sm:text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    <i class="fa-brands fa-stripe text-[10px]"></i>
                    <span>Stripe</span>
                </a>
            </div>
        </div>

        <!-- Alert Messages - Compact -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-400 text-green-700 px-3 py-2 rounded text-xs sm:text-sm">
                <div class="flex items-center">
                    <i class="fa-solid fa-check-circle mr-2 text-xs"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-400 text-red-700 px-3 py-2 rounded text-xs sm:text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabs - 375px: Scrollable horizontal -->
        <div class="border-b border-gray-200 -mx-3 px-3 sm:mx-0 sm:px-0 overflow-x-auto">
            <nav class="flex space-x-4 sm:space-x-8 min-w-max" aria-label="Tabs">
                <a href="{{ route('admin.payments.index', ['type' => 'all'] + request()->except('type')) }}"
                    class="@if($type === 'all') border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-2.5 sm:py-4 px-1 border-b-2 font-medium text-xs sm:text-sm flex items-center gap-1.5">
                    <span>All</span>
                </a>
                <a href="{{ route('admin.payments.index', ['type' => 'subscriptions'] + request()->except('type')) }}"
                    class="@if($type === 'subscriptions') border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-2.5 sm:py-4 px-1 border-b-2 font-medium text-xs sm:text-sm flex items-center gap-1.5">
                    <span>Subs</span>
                    @if($type === 'all' || $type === 'subscriptions')
                        <span class="bg-gray-100 text-gray-900 py-0.5 px-1.5 sm:px-2 rounded-full text-[10px] sm:text-xs font-medium">
                            {{ $subscriptionSummary['total'] ?? 0 }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.payments.index', ['type' => 'purchases'] + request()->except('type')) }}"
                    class="@if($type === 'purchases') border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-2.5 sm:py-4 px-1 border-b-2 font-medium text-xs sm:text-sm flex items-center gap-1.5">
                    <span>One-Time</span>
                    @if($type === 'all' || $type === 'purchases')
                        <span class="bg-gray-100 text-gray-900 py-0.5 px-1.5 sm:px-2 rounded-full text-[10px] sm:text-xs font-medium">
                            {{ $purchaseSummary['total'] ?? 0 }}
                        </span>
                    @endif
                </a>
            </nav>
        </div>

        <!-- Subscription Summary Cards - 375px Optimized -->
        @if($type === 'all' || $type === 'subscriptions')
            @if(!empty($subscriptionSummary))
                <div class="grid grid-cols-2 gap-2 sm:gap-4 md:grid-cols-3 lg:grid-cols-5">
                    <div class="bg-white rounded-lg shadow-sm border border-blue-200 p-3 sm:p-5">
                        <div class="flex items-center justify-between mb-1 sm:mb-3">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500 tracking-wide">Total</p>
                            <div class="w-8 h-8 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-receipt text-blue-600 text-xs sm:text-base"></i>
                            </div>
                        </div>
                        <p class="text-xl sm:text-3xl font-bold text-gray-900">{{ number_format($subscriptionSummary['total']) }}</p>
                        <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5">Subscriptions</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-green-200 p-3 sm:p-5">
                        <div class="flex items-center justify-between mb-1 sm:mb-3">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500 tracking-wide">Active</p>
                            <div class="w-8 h-8 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-check text-green-600 text-xs sm:text-base"></i>
                            </div>
                        </div>
                        <p class="text-xl sm:text-3xl font-bold text-green-600">{{ number_format($subscriptionSummary['active']) }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-amber-200 p-3 sm:p-5">
                        <div class="flex items-center justify-between mb-1 sm:mb-3">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500 tracking-wide">Trial</p>
                            <div class="w-8 h-8 sm:w-12 sm:h-12 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-clock text-amber-600 text-xs sm:text-base"></i>
                            </div>
                        </div>
                        <p class="text-xl sm:text-3xl font-bold text-amber-600">{{ number_format($subscriptionSummary['trialing']) }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-red-200 p-3 sm:p-5">
                        <div class="flex items-center justify-between mb-1 sm:mb-3">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500 tracking-wide">Past Due</p>
                            <div class="icon-pad-past-due w-8 h-8 sm:w-12 sm:h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="icon-past-due fa-solid fa-exclamation text-red-600 text-xs sm:text-base"></i>
                            </div>
                        </div>
                        <p class="stat-past-due text-xl sm:text-3xl font-bold text-red-600">{{ number_format($subscriptionSummary['past_due']) }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-5 col-span-2 md:col-span-1">
                        <div class="flex items-center justify-between mb-1 sm:mb-3">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500 tracking-wide">Canceled</p>
                            <div class="icon-pad-canceled w-8 h-8 sm:w-12 sm:h-12 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-ban text-gray-600 text-xs sm:text-base"></i>
                            </div>
                        </div>
                        <p class="text-xl sm:text-3xl font-bold text-gray-600">{{ number_format($subscriptionSummary['canceled']) }}</p>
                    </div>
                </div>
            @endif
        @endif

        <!-- Purchase Summary Cards - 375px Optimized -->
        @if($type === 'all' || $type === 'purchases')
            @if(!empty($purchaseSummary))
                <div class="grid grid-cols-2 gap-2 sm:gap-4 md:grid-cols-4 lg:grid-cols-5">
                    <div class="bg-white rounded-lg shadow-sm border border-purple-200 p-3 sm:p-5">
                        <div class="flex items-center justify-between mb-1 sm:mb-3">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500 tracking-wide">Total</p>
                            <div class="w-8 h-8 sm:w-12 sm:h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-cart-shopping text-purple-600 text-xs sm:text-base"></i>
                            </div>
                        </div>
                        <p class="text-xl sm:text-3xl font-bold text-gray-900">{{ number_format($purchaseSummary['total']) }}</p>
                        <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5">Purchases</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-green-200 p-3 sm:p-5">
                        <div class="flex items-center justify-between mb-1 sm:mb-3">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500 tracking-wide">Done</p>
                            <div class="w-8 h-8 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-check text-green-600 text-xs sm:text-base"></i>
                            </div>
                        </div>
                        <p class="text-xl sm:text-3xl font-bold text-green-600">{{ number_format($purchaseSummary['completed']) }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-3 sm:p-5">
                        <div class="flex items-center justify-between mb-1 sm:mb-3">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500 tracking-wide">Refund</p>
                            <div class="icon-pad-refund w-8 h-8 sm:w-12 sm:h-12 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="icon-refund fa-solid fa-rotate-left text-orange-600 text-xs sm:text-base"></i>
                            </div>
                        </div>
                        <p class="stat-refund text-xl sm:text-3xl font-bold text-orange-600">{{ number_format($purchaseSummary['refunded']) }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-red-200 p-3 sm:p-5">
                        <div class="flex items-center justify-between mb-1 sm:mb-3">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500 tracking-wide">Failed</p>
                            <div class="icon-pad-failed w-8 h-8 sm:w-12 sm:h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="icon-failed fa-solid fa-xmark text-red-600 text-xs sm:text-base"></i>
                            </div>
                        </div>
                        <p class="stat-failed text-xl sm:text-3xl font-bold text-red-600">{{ number_format($purchaseSummary['failed']) }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-emerald-200 p-3 sm:p-5 col-span-2 md:col-span-1">
                        <div class="flex items-center justify-between mb-1 sm:mb-3">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase text-gray-500 tracking-wide">Revenue</p>
                            <div class="icon-pad-revenue w-8 h-8 sm:w-12 sm:h-12 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="icon-revenue fa-solid fa-dollar-sign text-emerald-600 text-xs sm:text-base"></i>
                            </div>
                        </div>
                        <p class="stat-revenue text-lg sm:text-2xl font-bold text-emerald-600">${{ number_format($totalRevenue ?? 0, 2) }}</p>
                        <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5">Total</p>
                    </div>
                </div>
            @endif
        @endif

        <!-- Subscriptions Section -->
        @if(($type === 'all' || $type === 'subscriptions') && $subscriptions)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header - 375px Optimized -->
                <div class="px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-100">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-credit-card text-sm sm:text-base"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h2 class="text-sm sm:text-lg font-semibold text-gray-900">Subscriptions</h2>
                            <p class="text-[10px] sm:text-sm text-gray-500">Recurring payments</p>
                        </div>
                    </div>

                    <!-- Filter - Compact -->
                    <form method="GET" action="{{ route('admin.payments.index') }}" class="flex gap-1.5 sm:gap-2">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <select name="status" id="status"
                            class="flex-1 min-w-0 rounded-lg border border-gray-300 bg-white text-gray-700 text-xs sm:text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-300 px-2 py-1.5 sm:py-2">
                            <option value="">All Status</option>
                            <option value="active" @selected($status === 'active')>Active</option>
                            <option value="trialing" @selected($status === 'trialing')>Trialing</option>
                            <option value="past_due" @selected($status === 'past_due')>Past Due</option>
                            <option value="canceled" @selected($status === 'canceled')>Canceled</option>
                        </select>
                        <button type="submit"
                            class="rounded-lg bg-blue-600 px-3 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                    </form>
                </div>

                <!-- Mobile Card View - 375px Optimized -->
                <div class="md:hidden divide-y divide-gray-100">
                    @forelse ($subscriptions as $subscription)
                        <div class="p-3 sm:p-4">
                            <!-- Customer Header -->
                            <div class="flex items-center gap-2.5 mb-2.5">
                                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 text-white font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($subscription->user?->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-1.5 mb-0.5">
                                        <p class="text-xs sm:text-sm font-semibold text-gray-900 truncate">
                                            {{ $subscription->user?->name ?? 'Unknown' }}
                                        </p>
                                        <span @class([
                                            'inline-flex items-center rounded-full px-1.5 py-0.5 text-[9px] sm:text-[10px] font-semibold capitalize status-badge',
                                            'bg-green-100 text-green-700' => $subscription->stripe_status === 'active',
                                            'bg-blue-100 text-blue-700' => $subscription->stripe_status === 'trialing',
                                            'bg-amber-100 text-amber-700' => $subscription->stripe_status === 'past_due',
                                            'bg-gray-100 text-gray-600' => !in_array($subscription->stripe_status, ['active', 'trialing', 'past_due']),
                                        ])>
                                            {{ str_replace('_', ' ', $subscription->stripe_status) }}
                                        </span>
                                    </div>
                                    <p class="text-[10px] sm:text-xs text-gray-500 truncate">{{ $subscription->user?->email ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- Details Grid -->
                            <div class="grid grid-cols-2 gap-2 mb-2.5">
                                <div class="bg-gray-50 rounded-md px-2.5 py-2">
                                    <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase tracking-wide mb-0.5">Plan</p>
                                    <p class="text-[11px] sm:text-xs font-medium text-gray-900 truncate">{{ $subscription->name }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-md px-2.5 py-2">
                                    <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase tracking-wide mb-0.5">Amount</p>
                                    <p class="text-[11px] sm:text-xs font-medium text-gray-900">
                                        @if($subscription->amount)
                                            ${{ number_format($subscription->amount, 2) }}
                                        @else
                                            —
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="https://dashboard.stripe.com/subscriptions/{{ $subscription->stripe_id }}" target="_blank"
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">
                                    <i class="fa-brands fa-stripe text-[10px]"></i>
                                    Stripe
                                </a>
                                <form action="{{ route('admin.payments.destroy', $subscription->id) }}?type=subscription" method="POST"
                                    onsubmit="return confirm('Delete this subscription?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100">
                                        <i class="fa-solid fa-trash text-[10px]"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="px-3 py-10 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-receipt text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-700 text-sm">No subscriptions</p>
                                    <p class="text-xs text-gray-500 mt-1">Subscriptions will appear here.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Plan</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Amount</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Updated</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($subscriptions as $subscription)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 flex items-center justify-center rounded-full bg-indigo-500 text-white font-semibold flex-shrink-0">
                                                {{ strtoupper(substr($subscription->user?->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $subscription->user?->name ?? 'Unknown' }}</p>
                                                <p class="text-sm text-gray-500 truncate">{{ $subscription->user?->email ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $subscription->name }}</div>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <span @class([
                                            'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold capitalize border status-badge',
                                            'bg-green-100 text-green-800 border-green-200' => $subscription->stripe_status === 'active',
                                            'bg-blue-100 text-blue-800 border-blue-200' => $subscription->stripe_status === 'trialing',
                                            'bg-amber-100 text-amber-800 border-amber-200' => $subscription->stripe_status === 'past_due',
                                            'bg-gray-100 text-gray-800 border-gray-200' => !in_array($subscription->stripe_status, ['active', 'trialing', 'past_due']),
                                        ])>
                                            {{ str_replace('_', ' ', $subscription->stripe_status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                        @if($subscription->amount)
                                            ${{ number_format($subscription->amount, 2) }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $subscription->updated_at?->format('M j, Y') ?? '—' }}
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex items-center gap-2">
                                            <a href="https://dashboard.stripe.com/subscriptions/{{ $subscription->stripe_id }}" target="_blank"
                                                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">
                                                Stripe
                                            </a>
                                            <form action="{{ route('admin.payments.destroy', $subscription->id) }}?type=subscription" method="POST" class="inline"
                                                onsubmit="return confirm('Delete this subscription?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center rounded-md border border-red-300 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 lg:px-6 py-10 text-center text-sm text-gray-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                                <i class="fa-solid fa-receipt"></i>
                                            </div>
                                            <p class="font-semibold text-gray-700">No subscriptions yet</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($subscriptions->hasPages())
                    <div class="px-3 sm:px-6 py-3 sm:py-4 border-t border-gray-100">
                        {{ $subscriptions->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        @endif

        <!-- Purchases Section -->
        @if(($type === 'all' || $type === 'purchases') && $purchases)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header - 375px Optimized -->
                <div class="px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-100">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-cart-shopping text-sm sm:text-base"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h2 class="text-sm sm:text-lg font-semibold text-gray-900">One-Time Payments</h2>
                            <p class="text-[10px] sm:text-sm text-gray-500">Single purchases</p>
                        </div>
                    </div>

                    <!-- Filter - Compact -->
                    <form method="GET" action="{{ route('admin.payments.index') }}" class="flex gap-1.5 sm:gap-2">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <select name="status" id="purchase_status"
                            class="flex-1 min-w-0 rounded-lg border border-gray-300 bg-white text-gray-700 text-xs sm:text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-300 px-2 py-1.5 sm:py-2">
                            <option value="">All Status</option>
                            <option value="completed" @selected($status === 'completed')>Completed</option>
                            <option value="refunded" @selected($status === 'refunded')>Refunded</option>
                            <option value="failed" @selected($status === 'failed')>Failed</option>
                        </select>
                        <button type="submit"
                            class="rounded-lg bg-blue-600 px-3 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                    </form>
                </div>

                <!-- Mobile Card View - 375px Optimized -->
                <div class="md:hidden divide-y divide-gray-100">
                    @forelse ($purchases as $purchase)
                        <div class="p-3 sm:p-4">
                            <!-- Customer Header -->
                            <div class="flex items-center gap-2.5 mb-2.5">
                                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-br from-purple-400 to-purple-600 text-white font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($purchase->user?->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-1.5 mb-0.5">
                                        <p class="text-xs sm:text-sm font-semibold text-gray-900 truncate">
                                            {{ $purchase->user?->name ?? 'Unknown' }}
                                        </p>
                                        <span @class([
                                            'inline-flex items-center rounded-full px-1.5 py-0.5 text-[9px] sm:text-[10px] font-semibold capitalize status-badge',
                                            'bg-green-100 text-green-700' => $purchase->status === 'completed',
                                            'bg-orange-100 text-orange-700' => $purchase->status === 'refunded',
                                            'bg-red-100 text-red-700' => $purchase->status === 'failed',
                                        ])>
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </div>
                                    <p class="text-[10px] sm:text-xs text-gray-500 truncate">{{ $purchase->user?->email ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- Details Grid -->
                            <div class="grid grid-cols-3 gap-2 mb-2.5">
                                <div class="bg-gray-50 rounded-md px-2 py-2">
                                    <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase tracking-wide mb-0.5">Item</p>
                                    <p class="text-[11px] sm:text-xs font-medium text-gray-900 truncate">
                                        @if($purchase->product)
                                            {{ $purchase->product->name }}
                                        @elseif($purchase->service)
                                            {{ $purchase->service->name }}
                                        @elseif($purchase->plan)
                                            {{ $purchase->plan->name }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-md px-2 py-2">
                                    <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase tracking-wide mb-0.5">Paid</p>
                                    <p class="text-[11px] sm:text-xs font-medium text-gray-900">${{ number_format($purchase->amount, 2) }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-md px-2 py-2">
                                    <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase tracking-wide mb-0.5">Qty</p>
                                    <p class="text-[11px] sm:text-xs font-medium text-gray-900">{{ $purchase->quantity }}</p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                @if($purchase->stripe_payment_intent_id)
                                    <a href="https://dashboard.stripe.com/payments/{{ $purchase->stripe_payment_intent_id }}" target="_blank"
                                        class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">
                                        <i class="fa-brands fa-stripe text-[10px]"></i>
                                        Stripe
                                    </a>
                                @endif
                                <form action="{{ route('admin.payments.destroy', $purchase->id) }}?type=purchase" method="POST"
                                    onsubmit="return confirm('Delete this purchase?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100">
                                        <i class="fa-solid fa-trash text-[10px]"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="px-3 py-10 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-700 text-sm">No purchases</p>
                                    <p class="text-xs text-gray-500 mt-1">Purchases will appear here.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Item</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Amount</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($purchases as $purchase)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 flex items-center justify-center rounded-full bg-purple-500 text-white font-semibold flex-shrink-0">
                                                {{ strtoupper(substr($purchase->user?->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $purchase->user?->name ?? 'Unknown' }}</p>
                                                <p class="text-sm text-gray-500 truncate">{{ $purchase->user?->email ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            @if($purchase->product)
                                                {{ $purchase->product->name }}
                                            @elseif($purchase->service)
                                                {{ $purchase->service->name }}
                                            @elseif($purchase->plan)
                                                {{ $purchase->plan->name }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <span @class([
                                            'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold capitalize border status-badge',
                                            'bg-green-100 text-green-800 border-green-200' => $purchase->status === 'completed',
                                            'bg-orange-100 text-orange-800 border-orange-200' => $purchase->status === 'refunded',
                                            'bg-red-100 text-red-800 border-red-200' => $purchase->status === 'failed',
                                        ])>
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                        ${{ number_format($purchase->amount, 2) }}
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $purchase->purchased_at?->format('M j, Y') ?? '—' }}
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex items-center gap-2">
                                            @if($purchase->stripe_payment_intent_id)
                                                <a href="https://dashboard.stripe.com/payments/{{ $purchase->stripe_payment_intent_id }}" target="_blank"
                                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">
                                                    Stripe
                                                </a>
                                            @endif
                                            <form action="{{ route('admin.payments.destroy', $purchase->id) }}?type=purchase" method="POST" class="inline"
                                                onsubmit="return confirm('Delete this purchase?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center rounded-md border border-red-300 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 lg:px-6 py-10 text-center text-sm text-gray-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                                <i class="fa-solid fa-cart-shopping"></i>
                                            </div>
                                            <p class="font-semibold text-gray-700">No purchases yet</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($purchases->hasPages())
                    <div class="px-3 sm:px-6 py-3 sm:py-4 border-t border-gray-100">
                        {{ $purchases->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        @endif

    </div>
</x-layout>
