<x-layout>
    <x-slot:title>
        Vacancies Management - {{ config('app.name') }}
    </x-slot:title>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8 space-y-4 sm:space-y-6 lg:space-y-8">
        <!-- Header Section - Mobile First -->
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Vacancies Management</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Manage your employee hiring</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <a href="{{ route('admin.carrers.create') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Add New Vacancy</span>
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
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
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if ($carrers->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <!-- Header with Icon - Mobile First -->
                <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0 px-4 sm:px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="inline-flex items-center justify-center rounded-full bg-blue-100 text-blue-600 w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                            <i class="fa-solid fa-briefcase text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">All Vacancies</h2>
                            <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Manage job openings and hiring positions.</p>
                        </div>
                    </div>
                </div>

                <!-- Desktop Grid View -->
                <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-3 gap-4 p-4 sm:p-6">
                    @foreach ($carrers as $carrer)
                        <div class="bg-white rounded-lg border border-gray-200 p-5 hover:shadow-md transition duration-200">
                            <!-- Vacancy Name -->
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $carrer->name }}</h3>

                            <!-- Description -->
                            @if ($carrer->description)
                                <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                    {{ $carrer->description }}
                                </p>
                            @endif

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <a href="{{ route('admin.carrers.edit', $carrer) }}"
                                    class="inline-flex items-center rounded-md border border-blue-200 px-3 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-50">
                                    <i class="fa-solid fa-edit mr-1"></i>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.carrers.destroy', $carrer) }}"
                                    class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this Job?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center rounded-md border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                        <i class="fa-solid fa-trash mr-1"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach ($carrers as $carrer)
                        <div class="p-4 space-y-3">
                            <!-- Vacancy Info -->
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 mb-2">{{ $carrer->name }}</h3>
                                @if ($carrer->description)
                                    <p class="text-sm text-gray-600 line-clamp-3">
                                        {{ $carrer->description }}
                                    </p>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col gap-2 pt-2">
                                <a href="{{ route('admin.carrers.edit', $carrer) }}"
                                   class="w-full inline-flex items-center justify-center gap-2 rounded-md border border-blue-200 px-3 py-2 text-xs font-semibold text-blue-600 transition hover:bg-blue-50">
                                    <i class="fa-solid fa-edit"></i>
                                    Edit Vacancy
                                </a>
                                <form method="POST" action="{{ route('admin.carrers.destroy', $carrer) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this Job?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-md border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                        <i class="fa-solid fa-trash"></i>
                                        Delete Vacancy
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 sm:p-12 text-center">
                <div class="flex flex-col items-center gap-4">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                        <i class="fa-solid fa-briefcase text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">No Vacancies Yet</h3>
                        <p class="text-sm text-gray-500 mb-6">Start by creating your first job opening</p>
                    </div>
                    <a href="{{ route('admin.carrers.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Create First Vacancy</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layout>
