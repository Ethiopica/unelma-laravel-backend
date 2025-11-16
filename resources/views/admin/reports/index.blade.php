<x-layout>
    <x-slot:title>
        System Reports - {{ config('app.name') }}
    </x-slot:title>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8 space-y-4 sm:space-y-6 lg:space-y-8">
        <!-- Header Section - Mobile First -->
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">System Reports</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">View comprehensive system statistics and analytics</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <a href="{{ route('admin.reports.export') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <i class="fa-solid fa-download text-xs"></i>
                    <span>Export Report</span>
                </a>
            </div>
        </div>

        <!-- Summary Cards - Mobile First Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow-sm border border-blue-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Total Users</p>
                    <div class="bg-blue-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <i class="fa-solid fa-users text-blue-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($userStats['total_users']) }}</p>
            </div>

            <!-- Admin Users -->
            <div class="bg-white rounded-lg shadow-sm border border-green-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Admin Users</p>
                    <div class="bg-green-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <i class="fa-solid fa-shield-halved text-green-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($userStats['admin_users']) }}</p>
            </div>

            <!-- Customers -->
            <div class="bg-white rounded-lg shadow-sm border border-purple-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Customers</p>
                    <div class="bg-purple-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <i class="fa-solid fa-user text-purple-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ number_format($userStats['customers']) }}</p>
            </div>

            <!-- Verified Users -->
            <div class="bg-white rounded-lg shadow-sm border border-amber-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Verified Users</p>
                    <div class="bg-amber-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <i class="fa-solid fa-check-circle text-amber-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-amber-600">{{ number_format($userStats['verified_users']) }}</p>
            </div>
        </div>

        <!-- Registration Statistics -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
            <!-- Today -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Today</h3>
                    <div class="bg-blue-100 rounded-full p-2 flex-shrink-0">
                        <i class="fa-solid fa-clock text-blue-600 text-sm"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($userStats['today_registrations']) }}</p>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">New registrations</p>
            </div>

            <!-- This Week -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">This Week</h3>
                    <div class="bg-green-100 rounded-full p-2 flex-shrink-0">
                        <i class="fa-solid fa-calendar-week text-green-600 text-sm"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($userStats['this_week_registrations']) }}</p>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">New registrations</p>
            </div>

            <!-- This Month -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">This Month</h3>
                    <div class="bg-purple-100 rounded-full p-2 flex-shrink-0">
                        <i class="fa-solid fa-calendar text-purple-600 text-sm"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($userStats['this_month_registrations']) }}</p>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">New registrations</p>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Recent Users -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="inline-flex items-center justify-center rounded-full bg-blue-100 text-blue-600 w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                            <i class="fa-solid fa-users text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Recent Users</h2>
                            <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Latest user registrations</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="space-y-3">
                        @forelse($recentUsers as $user)
                            <div
                                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-center space-x-3">
                                    @if ($user->profile_picture)
                                        <img src="{{ asset('storage/' . $user->profile_picture) }}"
                                            alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div
                                            class="h-10 w-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if ($user->is_admin)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Admin
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            User
                                        </span>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No recent users</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Monthly Growth -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="inline-flex items-center justify-center rounded-full bg-green-100 text-green-600 w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                            <i class="fa-solid fa-chart-line text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Monthly User Growth</h2>
                            <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">User registration trends</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="space-y-3">
                        @forelse($monthlyGrowth as $month)
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-sm font-medium text-gray-700">{{ \Carbon\Carbon::parse($month->month . '-01')->format('F Y') }}</span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-32 bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full"
                                            style="width: {{ min(($month->count / max($monthlyGrowth->pluck('count')->toArray())) * 100, 100) }}%">
                                        </div>
                                    </div>
                                    <span
                                        class="text-sm font-bold text-gray-800 w-8 text-right">{{ $month->count }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No data available</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
            <!-- Users with Profile Pictures -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <div class="flex-1">
                        <p class="text-xs font-semibold uppercase text-gray-500 mb-1">Users with Profile Pictures</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($userStats['users_with_pictures']) }}</p>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1">
                            {{ $userStats['total_users'] > 0 ? round(($userStats['users_with_pictures'] / $userStats['total_users']) * 100, 1) : 0 }}%
                            of total users
                        </p>
                    </div>
                    <div class="bg-indigo-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <i class="fa-solid fa-image text-indigo-600 text-sm sm:text-base"></i>
                    </div>
                </div>
            </div>

            <!-- Verification Rate -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <div class="flex-1">
                        <p class="text-xs font-semibold uppercase text-gray-500 mb-1">Email Verification Rate</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900">
                            {{ $userStats['total_users'] > 0 ? round(($userStats['verified_users'] / $userStats['total_users']) * 100, 1) : 0 }}%
                        </p>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1">
                            {{ number_format($userStats['verified_users']) }} out of {{ number_format($userStats['total_users']) }} users
                        </p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                        <i class="fa-solid fa-envelope-circle-check text-yellow-600 text-sm sm:text-base"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
