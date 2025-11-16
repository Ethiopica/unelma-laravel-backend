<x-layout>
    <x-slot:title>
        Contact Messages - {{ config('app.name') }}
    </x-slot:title>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8 space-y-4 sm:space-y-6 lg:space-y-8">
        <!-- Header Section - Mobile First -->
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Contact Messages</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">View and manage customer contact messages and inquiries</p>
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

        <!-- Summary Cards - Mobile First Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5">
                <p class="text-xs font-semibold uppercase text-gray-500">Total Messages</p>
                <p class="mt-2 sm:mt-3 text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($stats['total_messages']) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-red-200 p-4 sm:p-5">
                <p class="text-xs font-semibold uppercase text-gray-500">Unread</p>
                <p class="mt-2 sm:mt-3 text-2xl sm:text-3xl font-bold text-red-600">{{ number_format($stats['unread_messages']) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-green-200 p-4 sm:p-5 col-span-2 md:col-span-1">
                <p class="text-xs font-semibold uppercase text-gray-500">Read</p>
                <p class="mt-2 sm:mt-3 text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($stats['total_messages'] - $stats['unread_messages']) }}</p>
            </div>
        </div>

        <!-- Messages Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            @if ($messages->count() > 0)
                <!-- Header with Icon - Mobile First -->
                <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0 px-4 sm:px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="inline-flex items-center justify-center rounded-full bg-blue-100 text-blue-600 w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                            <i class="fa-solid fa-envelope text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">All Messages</h2>
                            <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Customer inquiries and contact form submissions.</p>
                        </div>
                    </div>
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($messages as $message)
                                <tr class="hover:bg-gray-50 transition {{ !$message->is_read ? 'bg-blue-50' : '' }}">
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium text-gray-900">#{{ $message->id }}</span>
                                            @if (!$message->is_read)
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-700">
                                                    New
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">{{ $message->name }}</div>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 truncate max-w-xs">{{ $message->email }}</div>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        @if ($message->is_read)
                                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-700">
                                                Read
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold bg-red-100 text-red-700">
                                                Unread
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="hidden lg:inline">{{ $message->created_at->format('M j, Y H:i') }}</span>
                                        <span class="lg:hidden">{{ $message->created_at->format('M j') }}</span>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" onclick="openMessageModal({{ $message->id }})"
                                                class="inline-flex items-center rounded-lg border border-blue-200 px-2 lg:px-3 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-50 whitespace-nowrap">
                                                <span class="hidden lg:inline">View</span>
                                                <span class="lg:hidden">View</span>
                                            </button>
                                            <button type="button" onclick="openReplyModal({{ $message->id }})"
                                                class="inline-flex items-center rounded-lg border border-emerald-200 px-2 lg:px-3 py-1.5 text-xs font-semibold text-emerald-600 transition hover:bg-emerald-50 whitespace-nowrap">
                                                <span class="hidden lg:inline">Reply</span>
                                                <span class="lg:hidden">Reply</span>
                                            </button>
                                            @if (!$message->is_read)
                                                <form action="{{ route('admin.contact-messages.read', $message->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center rounded-lg border border-green-200 px-2 lg:px-3 py-1.5 text-xs font-semibold text-green-600 transition hover:bg-green-50 whitespace-nowrap">
                                                        <span class="hidden lg:inline">Mark Read</span>
                                                        <span class="lg:hidden">Read</span>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.contact-messages.destroy', $message->id) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this message?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center rounded-lg border border-red-200 px-2 lg:px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50 whitespace-nowrap">
                                                    <span class="hidden lg:inline">Delete</span>
                                                    <span class="lg:hidden">Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach ($messages as $message)
                        <div class="p-4 space-y-3 {{ !$message->is_read ? 'bg-blue-50' : '' }}">
                            <!-- Message Info -->
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-sm font-semibold text-gray-900">#{{ $message->id }}</p>
                                        @if (!$message->is_read)
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-700">
                                                New
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        {{ $message->created_at->format('M j, Y') }} · <span class="text-gray-400">{{ $message->created_at->format('h:i A') }}</span>
                                    </p>
                                </div>
                                @if ($message->is_read)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-700 flex-shrink-0">
                                        Read
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold bg-red-100 text-red-700 flex-shrink-0">
                                        Unread
                                    </span>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="space-y-2 text-sm">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">From</p>
                                    <p class="font-medium text-gray-900">{{ $message->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Email</p>
                                    <p class="text-gray-900 break-all">{{ $message->email }}</p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col gap-2 pt-2">
                                <button type="button" onclick="openMessageModal({{ $message->id }})"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-blue-200 px-3 py-2 text-xs font-semibold text-blue-600 transition hover:bg-blue-50">
                                    <i class="fa-solid fa-eye"></i>
                                    View Message
                                </button>
                                <button type="button" onclick="openReplyModal({{ $message->id }})"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-200 px-3 py-2 text-xs font-semibold text-emerald-600 transition hover:bg-emerald-50">
                                    <i class="fa-solid fa-reply"></i>
                                    Reply
                                </button>
                                @if (!$message->is_read)
                                    <form action="{{ route('admin.contact-messages.read', $message->id) }}"
                                        method="POST" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-green-200 px-3 py-2 text-xs font-semibold text-green-600 transition hover:bg-green-50">
                                            <i class="fa-solid fa-check"></i>
                                            Mark as Read
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.contact-messages.destroy', $message->id) }}"
                                    method="POST" class="w-full"
                                    onsubmit="return confirm('Are you sure you want to delete this message?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                        <i class="fa-solid fa-trash"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-4 sm:px-6 py-4 border-t border-gray-100">
                    {{ $messages->withQueryString()->links() }}
                </div>
            @else
                <div class="px-4 sm:px-6 py-10 text-center text-sm text-gray-500">
                    <div class="flex flex-col items-center gap-3">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                            <i class="fa-solid fa-envelope text-xl"></i>
                        </div>
                        <p class="font-semibold text-gray-700">No messages</p>
                        <p class="text-sm text-gray-500 max-w-sm px-4">
                            No contact messages have been received yet.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Message View Modal -->
    <div id="messageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Message Details</h3>
                <button onclick="closeMessageModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="mt-4">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">From</label>
                        <p id="modalEmail" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <p id="modalName" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message</label>
                        <p id="modalMessage" class="mt-1 text-sm text-gray-900 whitespace-pre-wrap"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date</label>
                        <p id="modalDate" class="mt-1 text-sm text-gray-900 inline"></p>
                        <span id="modalDateTimeDiff"
                            class='ml-2 text-sm text-lime-500 bg-yellow-300 p-2 rounded-3xl'></span>
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button onclick="closeMessageModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">
                    Close
                </button>
                <form id="markAsReadForm" action="" method="POST" class="inline" style="display: none;">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                        Mark as Read
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Reply Modal -->
    <div id="replyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Send Reply</h3>
                <button onclick="closeReplyModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="replyForm" action="{{ route('admin.contact-messages.reply') }}" method="POST"
                class="mt-4">
                @csrf
                <input type="hidden" id="replyEmail" name="email" />
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">To</label>
                        <p id="replyTo" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label for="replyText" class="block text-sm font-medium text-gray-700">Your Reply</label>
                        <textarea id="replyText" name="reply" rows="5"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeReplyModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition duration-200">
                        Send Reply
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const messages = @json($messages->items());

        function openMessageModal(messageId) {
            const message = messages.find(m => m.id === messageId);
            if (!message) {
                alert('Message not found in current page');
                return;
            }

            displayMessage(message);
        }

        function displayMessage(message) {
            document.getElementById('modalEmail').textContent = message.email;
            document.getElementById('modalName').textContent = message.name;
            document.getElementById('modalMessage').textContent = message.message;
            let messagedDate = new Date(message.created_at);
            let todaysDate = new Date();
            let dateElementHTML = document.getElementById('modalDate');
            let modalDateTimeDiff = document.getElementById('modalDateTimeDiff');
            const diffMs = todaysDate - messagedDate; // Milliseconds here
            const diffSec = diffMs / 1000;
            const diffMin = diffSec / 60;
            const diffHours = diffMin / 60;
            const diffDays = diffHours / 24;
            const diffMonths = diffDays / 30;
            const diffYears = diffDays / 365;
            if (diffYears < 1) {
                if (diffMonths < 1) {
                    if (diffDays > 1) {
                        dateElementHTML.textContent = messagedDate.toLocaleString();
                        modalDateTimeDiff.textContent = Math.floor(diffDays) + " days ago";
                    } else {
                        if (diffHours > 1) {
                            dateElementHTML.textContent = messagedDate.toLocaleString();
                            modalDateTimeDiff.textContent = Math.floor(diffMin) + " minutes ago";
                        } else {

                            dateElementHTML.textContent = messagedDate.toLocaleString();
                            modalDateTimeDiff.textContent = Math.floor(diffHours) + "hours ago";
                        }
                    }
                } else {
                    dateElementHTML.textContent = messagedDate.toLocaleString();
                    modalDateTimeDiff.textContent = Math.floor(diffMonths) + "months ago";
                }
            } else {
                dateElementHTML.textContent = messagedDate.toLocaleString();
                modalDateTimeDiff.textContent = Math.floor(diffYears) + "year ago";

            }




            // Show/hide mark as read button
            const markAsReadForm = document.getElementById('markAsReadForm');
            if (!message.is_read) {
                markAsReadForm.action = `/admin/contact-messages/${message.id}/read`;
                markAsReadForm.style.display = 'inline-block';
            } else {
                markAsReadForm.style.display = 'none';
            }

            document.getElementById('messageModal').classList.remove('hidden');
        }

        function closeMessageModal() {
            document.getElementById('messageModal').classList.add('hidden');
        }

        function openReplyModal(messageId) {
            const message = messages.find(m => m.id === messageId);
            if (!message) {
                alert('Message not found in current page');
                return;
            }
            document.getElementById('replyEmail').value = message.email;
            document.getElementById('replyTo').textContent = `${message.name} <${message.email}>`;
            document.getElementById('replyText').value = '';
            document.getElementById('replyModal').classList.remove('hidden');
        }

        function closeReplyModal() {
            document.getElementById('replyModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('messageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMessageModal();
            }
        });
        document.getElementById('replyModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReplyModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMessageModal();
                closeReplyModal();
            }
        });
    </script>

</x-layout>
