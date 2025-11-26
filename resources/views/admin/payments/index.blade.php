<x-layout>
    <x-slot:title>
        Subscription Payments - {{ config('app.name') }}
    </x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8 space-y-4 sm:space-y-6 lg:space-y-8">
        <!-- Header Section - Mobile First -->
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Subscription Payments</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Recent subscription activity and billing statuses.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>New Customer</span>
                </a>
                <a href="https://dashboard.stripe.com/" target="_blank"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fa-brands fa-stripe text-xs"></i>
                    <span>Open Stripe</span>
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-blue-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Total</p>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-receipt text-blue-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($summary['total']) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-green-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Active</p>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check-circle text-green-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($summary['active']) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-amber-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Trialing</p>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-clock text-amber-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-amber-600">{{ number_format($summary['trialing']) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-red-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Past Due</p>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-exclamation-triangle text-red-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ number_format($summary['past_due']) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5 col-span-2 md:col-span-1">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Canceled</p>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-ban text-gray-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-600">{{ number_format($summary['canceled']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <!-- Header with Filter - Mobile First -->
            <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-600 w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                            <i class="fa-solid fa-credit-card text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Recent Activity</h2>
                            <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Most recent subscriptions sorted by creation date.</p>
                        </div>
                    </div>

                    <form method="GET"
                        class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                        <label for="status" class="sr-only">Status</label>
                        <select name="status" id="status"
                            class="flex-1 sm:flex-none rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-300">
                            <option value="">All Statuses</option>
                            <option value="active" @selected(request('status') === 'active')>Active</option>
                            <option value="trialing" @selected(request('status') === 'trialing')>Trialing</option>
                            <option value="past_due" @selected(request('status') === 'past_due')>Past Due</option>
                            <option value="canceled" @selected(request('status') === 'canceled')>Canceled</option>
                        </select>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Apply
                        </button>
                    </form>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Customer</th>
                            <th scope="col"
                                class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Plan</th>
                            <th scope="col"
                                class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Quantity</th>
                            <th scope="col"
                                class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Trial Ends</th>
                            <th scope="col"
                                class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Last Updated</th>
                            <th scope="col"
                                class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($subscriptions as $subscription)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-10 w-10 flex items-center justify-center rounded-full bg-indigo-500 text-white font-semibold flex-shrink-0">
                                            {{ strtoupper(substr($subscription->user?->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $subscription->user?->name ?? 'Unknown User' }}</p>
                                            <p class="text-sm text-gray-600 truncate">
                                                {{ $subscription->user?->email ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $subscription->name }}</div>
                                    <div class="text-sm text-gray-600 truncate max-w-xs">
                                        {{ $subscription->stripe_price }}</div>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <span @class([
                                        'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold capitalize border',
                                        'bg-green-100 text-green-800 border-green-200' => $subscription->stripe_status === 'active',
                                        'bg-blue-100 text-blue-800 border-blue-200' => $subscription->stripe_status === 'trialing',
                                        'bg-amber-100 text-amber-800 border-amber-200' =>
                                            $subscription->stripe_status === 'past_due',
                                        'bg-gray-100 text-gray-800 border-gray-200' => !in_array($subscription->stripe_status, [
                                            'active',
                                            'trialing',
                                            'past_due',
                                        ]),
                                    ])>
                                        {{ str_replace('_', ' ', $subscription->stripe_status) }}
                                    </span>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $subscription->quantity ?? '—' }}
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ optional($subscription->trial_ends_at)->format('M j, Y') ?? '—' }}
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span
                                        class="hidden lg:inline">{{ $subscription->updated_at?->format('M j, Y H:i') ?? '—' }}</span>
                                    <span
                                        class="lg:hidden">{{ $subscription->updated_at?->format('M j') ?? '—' }}</span>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <a href="https://dashboard.stripe.com/subscriptions/{{ $subscription->stripe_id }}"
                                            target="_blank"
                                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 whitespace-nowrap">
                                            <span class="hidden lg:inline">View in Stripe</span>
                                            <span class="lg:hidden">Stripe</span>
                                        </a>
                                        <form action="{{ route('admin.payments.destroy', $subscription->id) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this subscription? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center rounded-md border border-[#E3E174] bg-white px-3 py-1.5 text-xs font-semibold text-[#E3E174] transition hover:bg-gray-50 whitespace-nowrap">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                                <span class="hidden lg:inline ml-1">Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 lg:px-6 py-10 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                            <i class="fa-solid fa-receipt"></i>
                                        </div>
                                        <p class="font-semibold text-gray-700">No subscriptions yet</p>
                                        <p class="text-sm text-gray-500 max-w-sm px-4">
                                            Once customers start subscribing through your checkout flow, their payment
                                            details will show up here.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden divide-y divide-gray-200">
                @forelse ($subscriptions as $subscription)
                    <div class="p-4 space-y-3 bg-white">
                        <!-- Customer Info -->
                        <div class="flex items-center gap-3">
                            <div
                                class="h-10 w-10 flex items-center justify-center rounded-full bg-indigo-500 text-white font-semibold flex-shrink-0">
                                {{ strtoupper(substr($subscription->user?->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $subscription->user?->name ?? 'Unknown User' }}</p>
                                <p class="text-xs text-gray-600 truncate">{{ $subscription->user?->email ?? 'N/A' }}
                                </p>
                            </div>
                            <span @class([
                                'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold capitalize flex-shrink-0 border',
                                'bg-green-100 text-green-800 border-green-200' => $subscription->stripe_status === 'active',
                                'bg-blue-100 text-blue-800 border-blue-200' => $subscription->stripe_status === 'trialing',
                                'bg-amber-100 text-amber-800 border-amber-200' =>
                                    $subscription->stripe_status === 'past_due',
                                'bg-gray-100 text-gray-800 border-gray-200' => !in_array($subscription->stripe_status, [
                                    'active',
                                    'trialing',
                                    'past_due',
                                ]),
                            ])>
                                {{ str_replace('_', ' ', $subscription->stripe_status) }}
                            </span>
                        </div>

                        <!-- Plan Details -->
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Plan</p>
                                <p class="font-medium text-gray-900">{{ $subscription->name }}</p>
                                <p class="text-xs text-gray-600 truncate">{{ $subscription->stripe_price }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Quantity</p>
                                <p class="font-medium text-gray-900">{{ $subscription->quantity ?? '—' }}</p>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            @if ($subscription->trial_ends_at)
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Trial Ends</p>
                                    <p class="font-medium text-gray-900">
                                        {{ $subscription->trial_ends_at->format('M j, Y') }}</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Updated</p>
                                <p class="font-medium text-gray-900">
                                    {{ $subscription->updated_at?->format('M j, Y') ?? '—' }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col gap-2 pt-2">
                            <a href="https://dashboard.stripe.com/subscriptions/{{ $subscription->stripe_id }}"
                                target="_blank"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">
                                <i class="fa-brands fa-stripe"></i>
                                View in Stripe
                            </a>
                            <form action="{{ route('admin.payments.destroy', $subscription->id) }}" method="POST"
                                class="w-full"
                                onsubmit="return confirm('Are you sure you want to delete this subscription? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-md border border-[#E3E174] bg-white px-3 py-2 text-xs font-semibold text-[#E3E174] transition hover:bg-gray-50">
                                    <i class="fa-solid fa-trash"></i>
                                    Delete Subscription
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-10 text-center text-sm text-gray-500">
                        <div class="flex flex-col items-center gap-3">
                            <div
                                class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <p class="font-semibold text-gray-700">No subscriptions yet</p>
                            <p class="text-sm text-gray-500 max-w-sm px-4">
                                Once customers start subscribing through your checkout flow, their payment details will
                                show up here.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="px-4 sm:px-6 py-4 border-t border-gray-100">
                {{ $subscriptions->withQueryString()->links() }}
            </div>
        </div>

    </div>
</x-layout>
