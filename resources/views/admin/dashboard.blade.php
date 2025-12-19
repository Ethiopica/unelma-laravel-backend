<x-layout>
    <x-slot:title>
        Admin Dashboard - {{ config('app.name') }}
    </x-slot:title>

    {{-- Christmas Snowflakes Animation --}}
    <style>
        .snowflakes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
            overflow: hidden;
        }
        
        .snowflake {
            position: absolute;
            top: -20px;
            color: #fff;
            font-size: 1em;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.8), 0 0 10px rgba(173, 216, 230, 0.6);
            animation: snowfall linear infinite;
            opacity: 0.9;
            filter: drop-shadow(0 0 3px rgba(255, 255, 255, 0.5));
        }
        
        .snowflake::before {
            content: "❄";
        }
        
        .snowflake:nth-child(2n)::before { content: "❅"; }
        .snowflake:nth-child(3n)::before { content: "❆"; }
        .snowflake:nth-child(5n)::before { content: "✻"; }
        .snowflake:nth-child(7n)::before { content: "✼"; }
        
        @keyframes snowfall {
            0% {
                transform: translateY(-20px) rotate(0deg) translateX(0);
                opacity: 1;
            }
            25% {
                transform: translateY(25vh) rotate(90deg) translateX(15px);
            }
            50% {
                transform: translateY(50vh) rotate(180deg) translateX(-15px);
            }
            75% {
                transform: translateY(75vh) rotate(270deg) translateX(15px);
            }
            100% {
                transform: translateY(100vh) rotate(360deg) translateX(-15px);
                opacity: 0.3;
            }
        }
        
        /* Different speeds and positions for variety */
        .snowflake:nth-child(1) { left: 5%; animation-duration: 8s; animation-delay: 0s; font-size: 0.8em; }
        .snowflake:nth-child(2) { left: 10%; animation-duration: 12s; animation-delay: 1s; font-size: 1.2em; }
        .snowflake:nth-child(3) { left: 15%; animation-duration: 10s; animation-delay: 2s; font-size: 0.9em; }
        .snowflake:nth-child(4) { left: 20%; animation-duration: 14s; animation-delay: 0.5s; font-size: 1.1em; }
        .snowflake:nth-child(5) { left: 25%; animation-duration: 9s; animation-delay: 3s; font-size: 0.7em; }
        .snowflake:nth-child(6) { left: 30%; animation-duration: 11s; animation-delay: 1.5s; font-size: 1.3em; }
        .snowflake:nth-child(7) { left: 35%; animation-duration: 13s; animation-delay: 2.5s; font-size: 0.85em; }
        .snowflake:nth-child(8) { left: 40%; animation-duration: 8s; animation-delay: 4s; font-size: 1em; }
        .snowflake:nth-child(9) { left: 45%; animation-duration: 15s; animation-delay: 0.8s; font-size: 1.15em; }
        .snowflake:nth-child(10) { left: 50%; animation-duration: 10s; animation-delay: 3.5s; font-size: 0.75em; }
        .snowflake:nth-child(11) { left: 55%; animation-duration: 12s; animation-delay: 1.2s; font-size: 1.25em; }
        .snowflake:nth-child(12) { left: 60%; animation-duration: 9s; animation-delay: 2.8s; font-size: 0.95em; }
        .snowflake:nth-child(13) { left: 65%; animation-duration: 14s; animation-delay: 0.3s; font-size: 1.05em; }
        .snowflake:nth-child(14) { left: 70%; animation-duration: 11s; animation-delay: 4.5s; font-size: 0.8em; }
        .snowflake:nth-child(15) { left: 75%; animation-duration: 13s; animation-delay: 1.8s; font-size: 1.2em; }
        .snowflake:nth-child(16) { left: 80%; animation-duration: 8s; animation-delay: 3.2s; font-size: 0.9em; }
        .snowflake:nth-child(17) { left: 85%; animation-duration: 10s; animation-delay: 2.2s; font-size: 1.1em; }
        .snowflake:nth-child(18) { left: 90%; animation-duration: 12s; animation-delay: 0.6s; font-size: 0.85em; }
        .snowflake:nth-child(19) { left: 95%; animation-duration: 9s; animation-delay: 4.2s; font-size: 1.3em; }
        .snowflake:nth-child(20) { left: 3%; animation-duration: 11s; animation-delay: 1.1s; font-size: 0.7em; }
        
        /* Christmas banner styling */
        .christmas-banner {
            background: linear-gradient(135deg, #c41e3a 0%, #1a472a 50%, #c41e3a 100%);
            background-size: 200% 200%;
            animation: gradientShift 5s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Olaf Animation */
        .olaf-container {
            position: fixed;
            bottom: 0;
            left: -120px;
            z-index: 9998;
            animation: olafWalk 20s linear infinite;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .olaf-container:hover {
            animation-play-state: paused;
            transform: scale(1.1);
        }

        .olaf-container:hover .olaf-speech {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes olafWalk {
            0% {
                left: -120px;
                transform: scaleX(1);
            }
            45% {
                left: calc(100% + 20px);
                transform: scaleX(1);
            }
            50% {
                left: calc(100% + 20px);
                transform: scaleX(-1);
            }
            95% {
                left: -120px;
                transform: scaleX(-1);
            }
            100% {
                left: -120px;
                transform: scaleX(1);
            }
        }

        .olaf {
            width: 80px;
            height: 120px;
            position: relative;
            animation: olafBounce 0.5s ease-in-out infinite;
        }

        @keyframes olafBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        /* Olaf's Body Parts */
        .olaf-head {
            width: 45px;
            height: 40px;
            background: linear-gradient(145deg, #ffffff 0%, #e8e8e8 100%);
            border-radius: 50%;
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: inset -3px -3px 8px rgba(0,0,0,0.1), 2px 2px 5px rgba(0,0,0,0.15);
        }

        /* Eyes */
        .olaf-eye {
            width: 8px;
            height: 10px;
            background: #1a1a1a;
            border-radius: 50%;
            position: absolute;
            top: 12px;
            animation: olafBlink 4s infinite;
        }
        .olaf-eye.left { left: 10px; }
        .olaf-eye.right { right: 10px; }
        .olaf-eye::after {
            content: '';
            width: 3px;
            height: 3px;
            background: white;
            border-radius: 50%;
            position: absolute;
            top: 2px;
            left: 2px;
        }

        @keyframes olafBlink {
            0%, 45%, 55%, 100% { transform: scaleY(1); }
            50% { transform: scaleY(0.1); }
        }

        /* Carrot Nose */
        .olaf-nose {
            width: 0;
            height: 0;
            border-left: 20px solid #ff6b35;
            border-top: 4px solid transparent;
            border-bottom: 4px solid transparent;
            position: absolute;
            top: 18px;
            left: 50%;
            transform: translateX(-50%) rotate(5deg);
            filter: drop-shadow(1px 1px 1px rgba(0,0,0,0.2));
        }

        /* Smile */
        .olaf-smile {
            width: 20px;
            height: 10px;
            border: 2px solid #1a1a1a;
            border-top: none;
            border-radius: 0 0 20px 20px;
            position: absolute;
            bottom: 8px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Eyebrows - twig style */
        .olaf-brow {
            width: 12px;
            height: 3px;
            background: #4a3728;
            position: absolute;
            top: 6px;
            border-radius: 2px;
        }
        .olaf-brow.left { left: 8px; transform: rotate(-10deg); }
        .olaf-brow.right { right: 8px; transform: rotate(10deg); }

        /* Middle body */
        .olaf-body-mid {
            width: 55px;
            height: 45px;
            background: linear-gradient(145deg, #ffffff 0%, #e0e0e0 100%);
            border-radius: 50%;
            position: absolute;
            top: 32px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: inset -3px -3px 8px rgba(0,0,0,0.1), 2px 2px 5px rgba(0,0,0,0.15);
        }

        /* Coal buttons */
        .olaf-button {
            width: 6px;
            height: 6px;
            background: #1a1a1a;
            border-radius: 50%;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        .olaf-button:nth-child(1) { top: 10px; }
        .olaf-button:nth-child(2) { top: 20px; }
        .olaf-button:nth-child(3) { top: 30px; }

        /* Bottom body */
        .olaf-body-bottom {
            width: 65px;
            height: 50px;
            background: linear-gradient(145deg, #ffffff 0%, #d8d8d8 100%);
            border-radius: 50%;
            position: absolute;
            top: 68px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: inset -3px -3px 8px rgba(0,0,0,0.1), 2px 2px 5px rgba(0,0,0,0.15);
        }

        /* Twig Arms */
        .olaf-arm {
            width: 35px;
            height: 4px;
            background: linear-gradient(90deg, #5d4037 0%, #3e2723 100%);
            position: absolute;
            top: 50px;
            border-radius: 2px;
        }
        .olaf-arm.left {
            left: -10px;
            transform: rotate(-20deg);
            transform-origin: right center;
            animation: olafWaveLeft 2s ease-in-out infinite;
        }
        .olaf-arm.right {
            right: -10px;
            transform: rotate(20deg);
            transform-origin: left center;
            animation: olafWaveRight 2s ease-in-out infinite 0.5s;
        }

        /* Arm fingers/twigs */
        .olaf-arm::before, .olaf-arm::after {
            content: '';
            width: 10px;
            height: 3px;
            background: #5d4037;
            position: absolute;
            border-radius: 2px;
        }
        .olaf-arm.left::before {
            left: 0;
            top: -5px;
            transform: rotate(30deg);
        }
        .olaf-arm.left::after {
            left: 0;
            top: 5px;
            transform: rotate(-30deg);
        }
        .olaf-arm.right::before {
            right: 0;
            top: -5px;
            transform: rotate(-30deg);
        }
        .olaf-arm.right::after {
            right: 0;
            top: 5px;
            transform: rotate(30deg);
        }

        @keyframes olafWaveLeft {
            0%, 100% { transform: rotate(-20deg); }
            50% { transform: rotate(-40deg); }
        }

        @keyframes olafWaveRight {
            0%, 100% { transform: rotate(20deg); }
            50% { transform: rotate(40deg); }
        }

        /* Feet */
        .olaf-foot {
            width: 18px;
            height: 8px;
            background: linear-gradient(145deg, #ffffff 0%, #d0d0d0 100%);
            border-radius: 50%;
            position: absolute;
            bottom: -2px;
            box-shadow: 1px 2px 3px rgba(0,0,0,0.2);
        }
        .olaf-foot.left { left: 12px; animation: olafFootLeft 0.5s ease-in-out infinite; }
        .olaf-foot.right { right: 12px; animation: olafFootRight 0.5s ease-in-out infinite 0.25s; }

        @keyframes olafFootLeft {
            0%, 100% { transform: rotate(-5deg); }
            50% { transform: rotate(5deg); }
        }
        @keyframes olafFootRight {
            0%, 100% { transform: rotate(5deg); }
            50% { transform: rotate(-5deg); }
        }

        /* Hair twigs */
        .olaf-hair {
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
        }
        .olaf-hair::before, .olaf-hair::after, .olaf-hair span {
            content: '';
            display: block;
            width: 3px;
            height: 12px;
            background: #5d4037;
            border-radius: 2px;
            position: absolute;
        }
        .olaf-hair::before {
            left: -6px;
            transform: rotate(-15deg);
        }
        .olaf-hair::after {
            right: -6px;
            transform: rotate(15deg);
        }
        .olaf-hair span {
            left: 0;
            transform: translateX(-50%);
        }

        /* Speech bubble */
        .olaf-speech {
            position: absolute;
            top: -60px;
            left: 50%;
            transform: translateX(-50%) translateY(10px);
            background: white;
            padding: 10px 16px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            opacity: 0;
            transition: all 0.3s ease;
            color: #333;
            z-index: 9999;
            min-width: 180px;
            text-align: center;
        }
        .olaf-speech::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            border: 8px solid transparent;
            border-top-color: white;
            border-bottom: none;
        }

        /* Snow trail */
        .olaf-container::after {
            content: '❄';
            position: absolute;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 10px;
            opacity: 0.5;
            animation: snowTrail 0.5s ease-out infinite;
        }

        @keyframes snowTrail {
            0% { opacity: 0.5; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(10px); }
        }
    </style>
    
    {{-- Olaf Character --}}
    <div class="olaf-container" id="olafContainer" onclick="olafSpeak()">
        <div class="olaf-speech" id="olafSpeech">🎄 Click me! ⛄</div>
        <div class="olaf">
            {{-- Head --}}
            <div class="olaf-head">
                <div class="olaf-brow left"></div>
                <div class="olaf-brow right"></div>
                <div class="olaf-eye left"></div>
                <div class="olaf-eye right"></div>
                <div class="olaf-nose"></div>
                <div class="olaf-smile"></div>
                <div class="olaf-hair"><span></span></div>
            </div>
            
            {{-- Arms --}}
            <div class="olaf-arm left"></div>
            <div class="olaf-arm right"></div>
            
            {{-- Middle Body --}}
            <div class="olaf-body-mid">
                <div class="olaf-button"></div>
                <div class="olaf-button"></div>
                <div class="olaf-button"></div>
            </div>
            
            {{-- Bottom Body --}}
            <div class="olaf-body-bottom">
                <div class="olaf-foot left"></div>
                <div class="olaf-foot right"></div>
            </div>
        </div>
    </div>

    {{-- Snowflakes Container --}}
    <div class="snowflakes" aria-hidden="true">
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
        <div class="snowflake"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8 space-y-4 sm:space-y-6 lg:space-y-8">
        
        {{-- Christmas Banner with Jingle Bell Sound --}}
        <div id="christmasBanner" class="christmas-banner rounded-xl p-4 sm:p-6 text-white shadow-lg cursor-pointer transition-transform hover:scale-[1.02]">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3 sm:gap-4">
                    <span class="text-3xl sm:text-4xl">🎄</span>
                    <div class="text-center sm:text-left">
                        <h2 class="text-lg sm:text-xl font-bold">Merry Christmas & Happy Holidays!</h2>
                        <p class="text-sm opacity-90">Wishing you joy and success this festive season 🎅</p>
                    </div>
                    <span class="text-3xl sm:text-4xl hidden sm:block">🎁</span>
                </div>
                <div class="flex gap-2 text-2xl sm:text-3xl">
                    <span>⛄</span>
                    <span>🦌</span>
                    <span id="jingleBell" class="animate-bounce">🔔</span>
                </div>
            </div>
        </div>
        
        {{-- Jingle Bell Audio --}}
        <audio id="jingleBellSound" preload="auto">
            <source src="https://www.soundjay.com/misc/sounds/bell-ringing-05.mp3" type="audio/mpeg">
        </audio>

        <!-- Header Section - Mobile First -->
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}! 🎄</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Here's what's happening with our platform today.</p>
            </div>
        </div>

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

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
            <!-- Total Users Card -->
            <div class="bg-white rounded-lg shadow-sm border border-blue-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Total Users</p>
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-users text-blue-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                <p class="text-xs text-gray-600 mt-1">All registered users</p>
            </div>

            <!-- Admin Users Card -->
            <div class="bg-white rounded-lg shadow-sm border border-green-200 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Admin Users</p>
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-user-shield text-green-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($stats['admin_users']) }}</p>
                <p class="text-xs text-gray-600 mt-1">Administrative access</p>
            </div>

            <!-- Customers Card -->
            <div class="bg-white rounded-lg shadow-sm border border-purple-200 p-4 sm:p-5 col-span-2 md:col-span-1">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Customers</p>
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-user-group text-purple-600 text-sm sm:text-base"></i>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($stats['customers']) }}</p>
                <p class="text-xs text-gray-600 mt-1">Active customers</p>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <!-- Header with Icon - Mobile First -->
            <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div
                        class="inline-flex items-center justify-center rounded-full bg-blue-100 text-blue-600 w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                        <i class="fa-solid fa-bolt text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Quick Actions</h2>
                        <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Access frequently used features and
                            management tools.</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="p-4 sm:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <a href="{{ route('admin.users.create') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div
                            class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center mb-3 group-hover:bg-blue-200 transition-colors">
                            <i class="fa-solid fa-user-plus text-blue-600 text-lg"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Add User</h4>
                        <p class="text-xs text-gray-600">Create a new user account</p>
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div
                            class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center mb-3 group-hover:bg-blue-200 transition-colors">
                            <i class="fa-solid fa-users text-blue-600 text-lg"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Manage Users</h4>
                        <p class="text-xs text-gray-600">View and manage all users</p>
                    </a>
                    <a href="{{ route('admin.blogs.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div
                            class="w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center mb-3 group-hover:bg-purple-200 transition-colors">
                            <i class="fa-solid fa-blog text-purple-600 text-lg"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Manage Blog</h4>
                        <p class="text-xs text-gray-600">Create and edit blog posts</p>
                    </a>
                    <a href="{{ route('admin.products.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div
                            class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center mb-3 group-hover:bg-indigo-200 transition-colors">
                            <i class="fa-solid fa-box text-indigo-600 text-lg"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Manage Products</h4>
                        <p class="text-xs text-gray-600">Add and update products</p>
                    </a>
                    <a href="{{ route('admin.services.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div
                            class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center mb-3 group-hover:bg-green-200 transition-colors">
                            <i class="fa-solid fa-briefcase text-green-600 text-lg"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Manage Services</h4>
                        <p class="text-xs text-gray-600">Showcase your services</p>
                    </a>
                    <a href="{{ route('admin.contact-messages.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center relative">
                        <div
                            class="w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center mb-3 group-hover:bg-amber-200 transition-colors">
                            <i class="fa-solid fa-envelope text-amber-600 text-lg"></i>
                        </div>
                        @if ($stats['unread_messages'] > 0)
                            <span
                                class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                                {{ $stats['unread_messages'] }}
                            </span>
                        @endif
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Manage Messages</h4>
                        <p class="text-xs text-gray-600">View customer inquiries</p>
                    </a>
                    <a href="{{ route('admin.subscribers.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div
                            class="w-14 h-14 rounded-full bg-cyan-100 flex items-center justify-center mb-3 group-hover:bg-cyan-200 transition-colors">
                            <i class="fa-solid fa-envelope-circle-check text-cyan-600 text-lg"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Newsletter Subscribers</h4>
                        <p class="text-xs text-gray-600">Manage newsletter subscribers</p>
                    </a>
                    <a href="{{ route('admin.reports.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div
                            class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center mb-3 group-hover:bg-green-200 transition-colors">
                            <i class="fa-solid fa-chart-line text-green-600 text-lg"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">View Reports</h4>
                        <p class="text-xs text-gray-600">System analytics</p>
                    </a>
                    <a href="{{ route('admin.settings.index') }}"
                        class="group bg-gray-50 rounded-lg border border-gray-200 p-4 sm:p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col items-center text-center">
                        <div
                            class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mb-3 group-hover:bg-gray-200 transition-colors">
                            <i class="fa-solid fa-gear text-gray-600 text-lg"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-1">Settings</h4>
                        <p class="text-xs text-gray-600">Configure system</p>
                    </a>
                </div>
            </div>
        </div>

    {{-- Snow Toggle Button --}}
    <button onclick="toggleSnow()" id="snowToggle"
        class="fixed bottom-4 right-4 z-[10000] bg-white/90 backdrop-blur-sm border border-gray-200 rounded-full p-3 shadow-lg hover:shadow-xl transition-all duration-300 group"
        title="Toggle Snow">
        <span id="snowIcon" class="text-xl">❄️</span>
    </button>

    <script>
        // Olaf's quotes
        const olafQuotes = [
            "Chúc Mừng Giáng Sinh! 🇻🇳🎄",
            "क्रिसमसको शुभकामना! 🇳🇵🎄",
            "እንኳን አደረሳችሁ! 🇪🇹🎄"
        ];

        let olafTimeout = null;
        function olafSpeak() {
            const speech = document.getElementById('olafSpeech');
            if (!speech) return;
            
            // Clear any existing timeout
            if (olafTimeout) clearTimeout(olafTimeout);
            
            const randomQuote = olafQuotes[Math.floor(Math.random() * olafQuotes.length)];
            speech.textContent = randomQuote;
            speech.style.opacity = '1';
            speech.style.visibility = 'visible';
            speech.style.transform = 'translateX(-50%) translateY(0)';
            
            // Hide after 5 seconds
            olafTimeout = setTimeout(() => {
                speech.style.opacity = '0';
                speech.style.transform = 'translateX(-50%) translateY(10px)';
            }, 5000);
        }

        // Random Olaf speech every 15-30 seconds
        function randomOlafSpeak() {
            const delay = Math.random() * 15000 + 15000; // 15-30 seconds
            setTimeout(() => {
                olafSpeak();
                randomOlafSpeak();
            }, delay);
        }

        function toggleSnow() {
            const snowflakes = document.querySelector('.snowflakes');
            const olaf = document.getElementById('olafContainer');
            const snowIcon = document.getElementById('snowIcon');
            const banner = document.querySelector('.christmas-banner');
            
            if (snowflakes.style.display === 'none') {
                snowflakes.style.display = 'block';
                if (olaf) olaf.style.display = 'block';
                snowIcon.textContent = '❄️';
                if (banner) banner.style.display = 'block';
                localStorage.setItem('snowEnabled', 'true');
            } else {
                snowflakes.style.display = 'none';
                if (olaf) olaf.style.display = 'none';
                snowIcon.textContent = '☀️';
                if (banner) banner.style.display = 'none';
                localStorage.setItem('snowEnabled', 'false');
            }
        }

        // Remember snow preference
        document.addEventListener('DOMContentLoaded', function() {
            const snowEnabled = localStorage.getItem('snowEnabled');
            if (snowEnabled === 'false') {
                document.querySelector('.snowflakes').style.display = 'none';
                document.getElementById('snowIcon').textContent = '☀️';
                const banner = document.querySelector('.christmas-banner');
                if (banner) banner.style.display = 'none';
                const olaf = document.getElementById('olafContainer');
                if (olaf) olaf.style.display = 'none';
            } else {
                // Start random Olaf speeches
                randomOlafSpeak();
                // Initial greeting after 3 seconds
                setTimeout(olafSpeak, 3000);
            }
            
            // Jingle Bell Sound on Banner Hover
            const christmasBanner = document.getElementById('christmasBanner');
            const jingleBellSound = document.getElementById('jingleBellSound');
            const jingleBell = document.getElementById('jingleBell');
            let canPlaySound = true;
            
            if (christmasBanner && jingleBellSound) {
                // Set volume
                jingleBellSound.volume = 0.3;
                
                christmasBanner.addEventListener('mouseenter', function() {
                    if (canPlaySound && snowEnabled !== 'false') {
                        // Reset and play sound
                        jingleBellSound.currentTime = 0;
                        jingleBellSound.play().catch(e => console.log('Audio play blocked:', e));
                        
                        // Add shake animation to bell
                        if (jingleBell) {
                            jingleBell.style.animation = 'none';
                            setTimeout(() => {
                                jingleBell.style.animation = 'jingleShake 0.5s ease-in-out 3';
                            }, 10);
                        }
                        
                        // Cooldown to prevent sound spam
                        canPlaySound = false;
                        setTimeout(() => {
                            canPlaySound = true;
                        }, 2000);
                    }
                });
            }
        });
        
        // Jingle bell shake animation
        const jingleStyle = document.createElement('style');
        jingleStyle.textContent = `
            @keyframes jingleShake {
                0%, 100% { transform: rotate(0deg); }
                25% { transform: rotate(15deg); }
                50% { transform: rotate(-15deg); }
                75% { transform: rotate(10deg); }
            }
        `;
        document.head.appendChild(jingleStyle);
    </script>

</x-layout>
