<x-layout>
    <x-slot:title>
        Applicant Details - {{ config('app.name') }}
    </x-slot:title>

    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-teal-600 to-teal-700 shadow-lg">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <a href="{{ route('admin.applicants.index') }}" 
                   class="inline-flex items-center text-sm text-teal-100 hover:text-white transition mb-3 sm:mb-4">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    Back to Applicants
                </a>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Application Details</h1>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 sm:mb-6 flex items-start gap-3">
                    <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
                    <span class="text-sm sm:text-base">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Applicant Card -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Profile Header -->
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 p-4 sm:p-6 lg:p-8">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold text-2xl sm:text-3xl lg:text-4xl flex-shrink-0 border-3 border-white/30">
                            {{ strtoupper(substr($applicant->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="text-white">
                            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold">{{ $applicant->name ?? 'N/A' }}</h2>
                            <p class="text-teal-100 mt-1 text-sm sm:text-base">
                                Applied for: <span class="font-semibold text-white">{{ $applicant->career->name ?? 'Unknown Position' }}</span>
                            </p>
                            <p class="text-teal-200 text-xs sm:text-sm mt-2 flex items-center justify-center sm:justify-start gap-1.5">
                                <i class="fa-solid fa-calendar"></i>
                                {{ $applicant->created_at->format('F d, Y') }}
                                <span class="hidden sm:inline">at {{ $applicant->created_at->format('h:i A') }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Details Section -->
                <div class="p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8">
                    <!-- Contact Information -->
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                            <i class="fa-solid fa-address-card text-teal-500 mr-2"></i>
                            Contact Information
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div class="bg-gray-50 rounded-lg sm:rounded-xl p-3 sm:p-4">
                                <p class="text-xs sm:text-sm text-gray-500 mb-1">Email Address</p>
                                <p class="text-gray-900 font-medium text-sm sm:text-base flex items-center gap-2 break-all">
                                    <i class="fa-solid fa-envelope text-gray-400 flex-shrink-0"></i>
                                    {{ $applicant->email ?? 'Not provided' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg sm:rounded-xl p-3 sm:p-4">
                                <p class="text-xs sm:text-sm text-gray-500 mb-1">Application Date</p>
                                <p class="text-gray-900 font-medium text-sm sm:text-base flex items-center gap-2">
                                    <i class="fa-solid fa-calendar-check text-gray-400"></i>
                                    {{ $applicant->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                            <i class="fa-solid fa-folder-open text-teal-500 mr-2"></i>
                            Application Documents
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <!-- CV / Resume -->
                            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 sm:p-5 border border-red-200">
                                <div class="flex items-center gap-3 mb-3 sm:mb-4">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-red-500 flex items-center justify-center text-white flex-shrink-0">
                                        <i class="fa-solid fa-file-pdf text-base sm:text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 text-sm sm:text-base">CV / Resume</h4>
                                        <p class="text-xs sm:text-sm text-gray-500">Curriculum Vitae</p>
                                    </div>
                                </div>
                                @if($applicant->CV)
                                    <div class="flex flex-col gap-2">
                                        <a href="{{ asset('storage/' . $applicant->CV) }}" 
                                           target="_blank"
                                           class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-3 sm:px-4 py-2 sm:py-2.5 text-gray-700 font-medium hover:bg-gray-50 transition border border-gray-200 text-xs sm:text-sm">
                                            <i class="fa-solid fa-eye"></i>
                                            View
                                        </a>
                                        <a href="{{ asset('storage/' . $applicant->CV) }}" 
                                           download="{{ $applicant->name }}_CV"
                                           class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-3 sm:px-4 py-2 sm:py-2.5 text-white font-medium hover:bg-red-700 transition text-xs sm:text-sm">
                                            <i class="fa-solid fa-download"></i>
                                            Download CV
                                        </a>
                                    </div>
                                @else
                                    <div class="bg-white/50 rounded-lg p-4 text-center">
                                        <i class="fa-solid fa-file-circle-xmark text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-gray-500 text-xs sm:text-sm">No CV attached</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Cover Letter -->
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 sm:p-5 border border-blue-200">
                                <div class="flex items-center gap-3 mb-3 sm:mb-4">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-blue-500 flex items-center justify-center text-white flex-shrink-0">
                                        <i class="fa-solid fa-file-lines text-base sm:text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Cover Letter</h4>
                                        <p class="text-xs sm:text-sm text-gray-500">Application Letter</p>
                                    </div>
                                </div>
                                @if($applicant->cover_letter)
                                    <div class="flex flex-col gap-2">
                                        <a href="{{ asset('storage/' . $applicant->cover_letter) }}" 
                                           target="_blank"
                                           class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-3 sm:px-4 py-2 sm:py-2.5 text-gray-700 font-medium hover:bg-gray-50 transition border border-gray-200 text-xs sm:text-sm">
                                            <i class="fa-solid fa-eye"></i>
                                            View
                                        </a>
                                        <a href="{{ asset('storage/' . $applicant->cover_letter) }}" 
                                           download="{{ $applicant->name }}_Cover_Letter"
                                           class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-3 sm:px-4 py-2 sm:py-2.5 text-white font-medium hover:bg-blue-700 transition text-xs sm:text-sm">
                                            <i class="fa-solid fa-download"></i>
                                            Download Letter
                                        </a>
                                    </div>
                                @elseif($applicant->cover_text)
                                    <div class="flex flex-col gap-2">
                                        <button onclick="showCoverLetterModal()"
                                           class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-3 sm:px-4 py-2 sm:py-2.5 text-gray-700 font-medium hover:bg-gray-50 transition border border-gray-200 text-xs sm:text-sm">
                                            <i class="fa-solid fa-eye"></i>
                                            View Text
                                        </button>
                                        <button onclick="downloadCoverLetterAsPDF()"
                                           class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-3 sm:px-4 py-2 sm:py-2.5 text-white font-medium hover:bg-blue-700 transition text-xs sm:text-sm">
                                            <i class="fa-solid fa-download"></i>
                                            Download as PDF
                                        </button>
                                    </div>
                                @else
                                    <div class="bg-white/50 rounded-lg p-4 text-center">
                                        <i class="fa-solid fa-file-circle-xmark text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-gray-500 text-xs sm:text-sm">No cover letter attached</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Cover Letter Text Preview (for legacy text-based cover letters) -->
                    @if($applicant->cover_text && !$applicant->cover_letter)
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                                <i class="fa-solid fa-align-left text-teal-500 mr-2"></i>
                                Cover Letter Content
                            </h3>
                            <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-5 border border-gray-100 max-h-48 sm:max-h-64 overflow-y-auto">
                                <p id="coverLetterText" class="text-gray-700 whitespace-pre-wrap leading-relaxed text-xs sm:text-sm">{{ $applicant->cover_text }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Position Details -->
                    @if($applicant->career)
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                                <i class="fa-solid fa-briefcase text-teal-500 mr-2"></i>
                                Position Applied For
                            </h3>
                            <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-5 border border-gray-100">
                                <h4 class="font-semibold text-gray-900 text-base sm:text-lg">{{ $applicant->career->name }}</h4>
                                @if($applicant->career->location)
                                    <p class="text-gray-600 mt-1 text-sm flex items-center gap-1.5">
                                        <i class="fa-solid fa-location-dot text-gray-400"></i>
                                        {{ $applicant->career->location }}
                                    </p>
                                @endif
                                @if($applicant->career->description)
                                    <p class="text-gray-600 mt-2 text-xs sm:text-sm">{{ Str::limit($applicant->career->description, 200) }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Communication History Section -->
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3 sm:mb-4">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fa-solid fa-comments text-teal-500 mr-2"></i>
                                Communication History
                                @if($applicant->replies && $applicant->replies->count() > 0)
                                    <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $applicant->replies->count() }}
                                    </span>
                                @endif
                            </h3>
                            @if($applicant->email)
                                <button type="button" onclick="openReplyModal()"
                                   class="inline-flex items-center justify-center gap-2 text-sm text-indigo-600 hover:text-indigo-700 font-medium transition">
                                    <i class="fa-solid fa-plus"></i>
                                    New Reply
                                </button>
                            @endif
                        </div>

                        @if($applicant->replies && $applicant->replies->count() > 0)
                            <div class="space-y-3 sm:space-y-4">
                                @foreach($applicant->replies->sortByDesc('sent_at') as $reply)
                                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-indigo-100">
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 sm:gap-4">
                                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white flex-shrink-0">
                                                    <i class="fa-solid fa-paper-plane text-xs sm:text-sm"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex flex-wrap items-center gap-1 sm:gap-2 mb-1">
                                                        <span class="font-semibold text-gray-900 text-xs sm:text-sm">
                                                            {{ $reply->sender->name ?? 'Admin' }}
                                                        </span>
                                                        <span class="text-gray-400 hidden sm:inline">→</span>
                                                        <span class="text-gray-600 text-xs sm:text-sm truncate">{{ $reply->sent_to_email }}</span>
                                                    </div>
                                                    <p class="text-gray-700 text-xs sm:text-sm whitespace-pre-wrap leading-relaxed mt-2">{{ $reply->message }}</p>
                                                </div>
                                            </div>
                                            <div class="text-left sm:text-right flex-shrink-0 pl-11 sm:pl-0">
                                                <p class="text-xs text-gray-500">
                                                    {{ $reply->sent_at->format('M d, Y') }}
                                                </p>
                                                <p class="text-xs text-gray-400">
                                                    {{ $reply->sent_at->format('h:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg sm:rounded-xl p-6 sm:p-8 text-center border border-gray-100">
                                <div class="inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gray-200 text-gray-400 mb-3">
                                    <i class="fa-solid fa-comments text-lg sm:text-xl"></i>
                                </div>
                                <p class="text-gray-500 text-xs sm:text-sm">No replies sent yet</p>
                                @if($applicant->email)
                                    <button type="button" onclick="openReplyModal()"
                                       class="mt-3 inline-flex items-center gap-2 text-xs sm:text-sm text-indigo-600 hover:text-indigo-700 font-medium transition">
                                        <i class="fa-solid fa-paper-plane"></i>
                                        Send First Reply
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="bg-gray-50 px-4 sm:px-6 lg:px-8 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-3 sm:justify-between">
                        <a href="{{ route('admin.applicants.index') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 sm:px-5 py-2.5 text-gray-700 font-medium hover:bg-gray-50 transition text-sm order-2 sm:order-1">
                            <i class="fa-solid fa-arrow-left"></i>
                            Back to List
                        </a>
                        @if($applicant->email)
                            <button type="button" onclick="openReplyModal()"
                               class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 sm:px-5 py-2.5 text-white font-medium hover:bg-indigo-700 transition text-sm order-1 sm:order-2">
                                <i class="fa-solid fa-reply"></i>
                                Reply to Applicant
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reply Modal -->
    @if($applicant->email)
    <div id="replyModal"
        class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="relative bg-white w-full sm:max-w-lg sm:rounded-xl shadow-xl max-h-[90vh] overflow-hidden rounded-t-2xl sm:rounded-2xl" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center p-4 sm:p-5 border-b border-gray-200 sticky top-0 bg-white z-10">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">
                    <i class="fa-solid fa-reply text-indigo-500 mr-2"></i>
                    Send Reply
                </h3>
                <button onclick="closeReplyModal()" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.applicants.reply') }}" method="POST" class="p-4 sm:p-6 space-y-4 overflow-y-auto max-h-[calc(90vh-80px)]">
                @csrf
                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}" />
                <input type="hidden" name="email" value="{{ $applicant->email }}" />

                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">To:</label>
                    <p class="text-gray-900 bg-gray-50 rounded-lg px-3 py-2 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-user text-gray-400"></i>
                        <span class="truncate">{{ $applicant->name }} &lt;{{ $applicant->email }}&gt;</span>
                    </p>
                </div>

                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Position:</label>
                    <p class="text-gray-600 bg-gray-50 rounded-lg px-3 py-2 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-briefcase text-gray-400"></i>
                        <span class="truncate">{{ $applicant->career->name ?? 'N/A' }}</span>
                    </p>
                </div>

                <div>
                    <label for="replyText" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Your Message:</label>
                    <textarea id="replyText" name="reply" rows="5"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        placeholder="Write your reply here..." required></textarea>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3 pt-2">
                    <button type="button" onclick="closeReplyModal()"
                        class="w-full sm:w-auto px-4 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium transition text-sm">
                        Cancel
                    </button>
                    <button type="submit"
                        class="w-full sm:w-auto px-4 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-medium transition text-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        Send Reply
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Cover Letter Text Modal (for legacy text-based cover letters) -->
    @if($applicant->cover_text && !$applicant->cover_letter)
    <div id="coverLetterModal"
        class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="relative bg-white w-full sm:max-w-2xl sm:rounded-xl shadow-xl max-h-[90vh] overflow-hidden rounded-t-2xl sm:rounded-2xl" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center p-4 sm:p-5 border-b border-gray-200 sticky top-0 bg-white z-10">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">
                    <i class="fa-solid fa-file-lines text-blue-500 mr-2"></i>
                    Cover Letter
                </h3>
                <button onclick="closeCoverLetterModal()" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="p-4 sm:p-6 overflow-y-auto max-h-[60vh]">
                <p class="text-gray-700 whitespace-pre-wrap leading-relaxed text-sm">{{ $applicant->cover_text }}</p>
            </div>
            <div class="bg-gray-50 px-4 sm:px-6 py-4 border-t border-gray-200 flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3 sticky bottom-0">
                <button onclick="closeCoverLetterModal()"
                    class="w-full sm:w-auto px-4 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium transition text-sm">
                    Close
                </button>
                <button onclick="downloadCoverLetterAsPDF()"
                    class="w-full sm:w-auto px-4 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-medium transition text-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-download"></i>
                    Download as PDF
                </button>
            </div>
        </div>
    </div>
    @endif

    <script>
        // Reply Modal Functions
        function openReplyModal() {
            document.getElementById('replyModal')?.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeReplyModal() {
            document.getElementById('replyModal')?.classList.add('hidden');
            document.body.style.overflow = '';
        }

        @if($applicant->email)
        document.getElementById('replyModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeReplyModal();
            }
        });
        @endif

        // Cover Letter Modal Functions
        function showCoverLetterModal() {
            document.getElementById('coverLetterModal')?.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeCoverLetterModal() {
            document.getElementById('coverLetterModal')?.classList.add('hidden');
            document.body.style.overflow = '';
        }

        @if($applicant->cover_text && !$applicant->cover_letter)
        document.getElementById('coverLetterModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCoverLetterModal();
            }
        });
        @endif

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeReplyModal();
                closeCoverLetterModal();
            }
        });

        // Download Cover Letter as PDF
        function downloadCoverLetterAsPDF() {
            const coverText = @json($applicant->cover_text ?? '');
            const applicantName = @json($applicant->name ?? 'Applicant');
            const position = @json($applicant->career->name ?? 'Position');
            const date = @json($applicant->created_at->format('F d, Y'));
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Cover Letter - ${applicantName}</title>
                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        body {
                            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                            line-height: 1.7;
                            color: #1f2937;
                            background: #fff;
                            padding: 40px;
                        }
                        .header {
                            border-bottom: 3px solid #006A62;
                            padding-bottom: 20px;
                            margin-bottom: 30px;
                        }
                        .title {
                            font-size: 24px;
                            font-weight: 700;
                            color: #006A62;
                            margin-bottom: 6px;
                        }
                        .subtitle {
                            font-size: 14px;
                            color: #6b7280;
                        }
                        .meta {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 20px;
                            margin-bottom: 30px;
                            padding: 16px;
                            background: #f8fafb;
                            border-radius: 8px;
                            border-left: 4px solid #74D7CB;
                        }
                        .meta-item { flex: 1; min-width: 150px; }
                        .meta-label {
                            font-size: 11px;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                            color: #6b7280;
                            margin-bottom: 4px;
                        }
                        .meta-value {
                            font-size: 14px;
                            font-weight: 600;
                            color: #1f2937;
                        }
                        .content {
                            font-size: 14px;
                            white-space: pre-wrap;
                            color: #374151;
                            line-height: 1.8;
                        }
                        .footer {
                            margin-top: 40px;
                            padding-top: 16px;
                            border-top: 1px solid #e5e7eb;
                            text-align: center;
                            color: #9ca3af;
                            font-size: 11px;
                        }
                        @media print {
                            body { padding: 30px; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1 class="title">Cover Letter</h1>
                        <p class="subtitle">Job Application Document</p>
                    </div>
                    <div class="meta">
                        <div class="meta-item">
                            <div class="meta-label">Applicant Name</div>
                            <div class="meta-value">${applicantName}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Position</div>
                            <div class="meta-value">${position}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Date</div>
                            <div class="meta-value">${date}</div>
                        </div>
                    </div>
                    <div class="content">${coverText}</div>
                    <div class="footer">
                        Generated from Unelma Platforms Admin Panel
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            setTimeout(() => { printWindow.print(); }, 500);
        }
    </script>
</x-layout>
