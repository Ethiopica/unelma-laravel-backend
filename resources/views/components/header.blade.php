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
                 <a href="{{ route('admin.products.index') }}"
                     class="{{ request()->routeIs('admin.products.index') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Products</a>
                 <a href="{{ route('admin.blogs.index') }}"
                     class="{{ request()->routeIs('admin.blogs.index') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Blogs</a>
                 <a href="{{ route('admin.services.index') }}"
                     class="{{ request()->routeIs('admin.services.index') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Services</a>
             </div>
             {{-- //Button To open that Box --}}
             <div class="flex">
                 <button id='menu-button'>
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                         <path stroke-linecap="round" stroke-linejoin="round"
                             d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                     </svg>
                 </button>
             </div>
             {{-- Menu Bar- This will display After Button CLick --}}
             <div class='border border-black border-solid absolute top-14 right-64 w-[300px] bg-slate-200 shadow-lg text-black flex flex-col hidden rounded-xl overflow-hidden'
                 id='menu-bar'>
                 <div class="flex flex-col gap-2 items-center space-x-4 w-full p-2">
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
                         <span class="text-gray-700 ">{{ auth()->user()->name }}</span>
                     </div>
                     <a href="{{ route('admin.carrers.index') }}"
                         class="{{ request()->routeIs('admin.carrers.index') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Vacancies|Carrers</a>
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
     </div>
 </nav>

 {{-- Script for that Menu Bar --}}
 <script>
     let menuBar = document.getElementById('menu-bar');
     console.log(menuBar);
     let menuOpenButton = document.getElementById('menu-button');
     menuOpenButton.addEventListener('click', () => {
         menuBar.classList.toggle('hidden')
         //  menuBar.classList.toggle('flex-col')
         console.log('Button Clicked')
     });
     console.log(menuBar);
 </script>
