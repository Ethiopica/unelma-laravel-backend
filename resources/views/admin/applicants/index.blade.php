<x-layout>
    <x-slot:title>
        Job Applicants - {{ config('app.name') }}
    </x-slot:title>

    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-teal-600 to-teal-700 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Job Applicants</h1>
                        <p class="text-teal-100 text-sm sm:text-base mt-1">Review and manage applications from candidates</p>
                    </div>
                    <a href="{{ route('admin.careers.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/20 backdrop-blur-sm px-4 py-2.5 sm:px-5 sm:py-3 text-white font-semibold hover:bg-white/30 transition text-sm sm:text-base border border-white/30">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Back to Vacancies</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 sm:mb-6 flex items-start gap-3">
                    <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
                    <span class="text-sm sm:text-base">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 sm:mb-6 flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
                    <span class="text-sm sm:text-base">{{ session('error') }}</span>
                </div>
            @endif

            @if ($applicants->count() > 0)
                <!-- Stats Bar -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5 mb-4 sm:mb-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center text-white">
                                <i class="fa-solid fa-users text-base sm:text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-base sm:text-lg font-semibold text-gray-900">All Applicants</h2>
                                <p class="text-xs sm:text-sm text-gray-500">{{ $applicants->total() }} total applications</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <span class="hidden sm:inline text-gray-500">Showing</span>
                            <span class="px-2.5 py-1 bg-teal-100 text-teal-700 rounded-full font-medium text-xs sm:text-sm">
                                {{ $applicants->firstItem() ?? 0 }}-{{ $applicants->lastItem() ?? 0 }} of {{ $applicants->total() }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Applicants Grid/List -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6">
                    @foreach ($applicants as $applicant)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                            <!-- Card Header -->
                            <div class="bg-gradient-to-r from-teal-500 to-teal-600 p-4 sm:p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold text-lg sm:text-xl flex-shrink-0 border-2 border-white/30">
                                        {{ strtoupper(substr($applicant->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-base sm:text-lg font-semibold text-white truncate">{{ $applicant->name ?? 'N/A' }}</h3>
                                        <p class="text-teal-100 text-xs sm:text-sm truncate">{{ $applicant->career->name ?? 'Unknown Position' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-4 sm:p-5 space-y-3 sm:space-y-4">
                                <!-- Contact Info -->
                                <div class="space-y-2">
                                    @if($applicant->email)
                                        <div class="flex items-center gap-2 text-gray-600">
                                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                <i class="fa-solid fa-envelope text-gray-400 text-sm"></i>
                                            </div>
                                            <span class="text-xs sm:text-sm truncate">{{ $applicant->email }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-calendar text-gray-400 text-sm"></i>
                                        </div>
                                        <span class="text-xs sm:text-sm">{{ $applicant->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="pt-3 sm:pt-4 border-t border-gray-100 space-y-2">
                                    <a href="{{ route('admin.applicants.show', $applicant->id) }}"
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-teal-600 px-4 py-2.5 text-white font-medium hover:bg-teal-700 transition text-sm">
                                        <i class="fa-solid fa-eye"></i>
                                        View Application
                                    </a>
                                    <div class="grid grid-cols-2 gap-2">
                                        @if($applicant->CV)
                                            <a href="{{ asset('storage/' . $applicant->CV) }}" 
                                               download
                                               class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-red-600 font-medium hover:bg-red-100 transition text-xs sm:text-sm">
                                                <i class="fa-solid fa-file-pdf"></i>
                                                <span>CV</span>
                                            </a>
                                        @else
                                            <div class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-gray-400 text-xs sm:text-sm">
                                                <i class="fa-solid fa-file-pdf"></i>
                                                <span>No CV</span>
                                            </div>
                                        @endif
                                        @if($applicant->cover_letter)
                                            <a href="{{ asset('storage/' . $applicant->cover_letter) }}" 
                                               download
                                               class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-blue-600 font-medium hover:bg-blue-100 transition text-xs sm:text-sm">
                                                <i class="fa-solid fa-file-lines"></i>
                                                <span>Letter</span>
                                            </a>
                                        @else
                                            <div class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-gray-400 text-xs sm:text-sm">
                                                <i class="fa-solid fa-file-lines"></i>
                                                <span>No Letter</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($applicants->hasPages())
                    <div class="mt-6 sm:mt-8">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
                            {{ $applicants->links() }}
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 sm:p-12 lg:p-16 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mx-auto mb-4 sm:mb-6">
                            <i class="fa-solid fa-users text-2xl sm:text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl lg:text-2xl font-semibold text-gray-700 mb-2 sm:mb-3">No Applicants Yet</h3>
                        <p class="text-sm sm:text-base text-gray-500 mb-6 sm:mb-8">Applications will appear here when candidates apply for your job openings.</p>
                        <a href="{{ route('admin.careers.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-teal-600 px-6 py-3 text-white font-semibold hover:bg-teal-700 transition text-sm sm:text-base">
                            <i class="fa-solid fa-briefcase"></i>
                            <span>Manage Job Vacancies</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout>
