<x-layout>
    <x-slot:title>
        Contact Messages - {{ config('app.name') }}
    </x-slot:title>

    <x-header />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Contact Messages</h1>
                <p class="text-gray-600 mt-1">View and manage customer contact messages and inquiries</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
                <p class="text-gray-600 text-sm font-medium uppercase">Total Messages</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_messages'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-red-500">
                <p class="text-gray-600 text-sm font-medium uppercase">Unread Messages</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['unread_messages'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
                <p class="text-gray-600 text-sm font-medium uppercase">Read Messages</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_messages'] - $stats['unread_messages'] }}</p>
            </div>
        </div>

        <!-- Messages Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($messages->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($messages as $message)
                                <tr class="hover:bg-gray-50 {{ !$message->is_read ? 'bg-blue-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900">#{{ $message->id }}</span>
                                            @if (!$message->is_read)
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    New
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $message->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $message->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($message->is_read)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Read
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Unread
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $message->created_at->format('M d, Y') }}
                                        <br>
                                        <span class="text-xs text-gray-400">{{ $message->created_at->format('h:i A') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button 
                                                type="button" 
                                                onclick="openMessageModal({{ $message->id }})"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 text-sm"
                                            >
                                                View
                                            </button>
                                            <button 
                                                type="button" 
                                                onclick="openReplyModal({{ $message->id }})"
                                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition duration-200 text-sm"
                                            >
                                                Reply
                                            </button>
                                            @if (!$message->is_read)
                                                <form action="{{ route('admin.contact-messages.read', $message->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 text-sm">
                                                        Mark as Read
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 text-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $messages->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No messages</h3>
                    <p class="mt-1 text-sm text-gray-500">No contact messages have been received yet.</p>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
                        <p id="modalDate" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button 
                    onclick="closeMessageModal()" 
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200"
                >
                    Close
                </button>
                <form id="markAsReadForm" action="" method="POST" class="inline" style="display: none;">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="replyForm" action="{{ route('admin.contact-messages.reply') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" id="replyEmail" name="email" />
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">To</label>
                        <p id="replyTo" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label for="replyText" class="block text-sm font-medium text-gray-700">Your Reply</label>
                        <textarea id="replyText" name="reply" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                    <button 
                        type="button"
                        onclick="closeReplyModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200"
                    >
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition duration-200">
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
            document.getElementById('modalDate').textContent = new Date(message.created_at).toLocaleString();
            
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
