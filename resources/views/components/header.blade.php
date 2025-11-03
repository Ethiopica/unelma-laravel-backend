 <nav class="bg-white shadow-lg">
     <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
         <div class="flex justify-between h-16">
             <div class="flex items-center space-x-8">
                 <h1 class="text-xl font-bold text-gray-800 cursor-pointer"
                     onclick="window.location.href='/admin/dashboard'">Admin Panel
                 </h1>
                 <a href="{{ route('admin.dashboard') }}"
                     class="{{ request()->routeIs('admin.dashboard') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Dashboard</a>
                 <a href="{{ route('admin.users.index') }}"
                     class="{{ request()->routeIs('admin.users.index') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Users</a>
                 <a href="{{ route('admin.settings.index') }}"
                     class="{{ request()->routeIs('admin.settings.index') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Settings</a>
                 <a href="{{ route('admin.reports.index') }}"
                     class="{{ request()->routeIs('admin.reports.index') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Reports</a>
                 <a href="{{ route('admin.contact-messages.index') }}"
                     class="{{ request()->routeIs('admin.contact-messages.index') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Messages</a>
             </div>
             <div class="flex items-center space-x-4">
                 <div class="flex items-center space-x-3">
                     @if (auth()->user()->profile_picture)
                         <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                             alt="{{ auth()->user()->name }}"
                             class="h-10 w-10 rounded-full object-cover border-2 border-gray-300">
                     @else
                         <div
                             class="h-10 w-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                             {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                         </div>
                     @endif
                     <span class="text-gray-700">{{ auth()->user()->name }}</span>
                 </div>
                 <form method="POST" action="{{ route('admin.logout') }}">
                     @csrf
                     <button type="submit"
                         class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200">
                         Logout
                     </button>
                 </form>
             </div>
         </div>
     </div>
 </nav>
