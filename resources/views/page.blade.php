<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }} - {{ config('app.name') }}</title>
    
    <!-- SEO Meta Tags -->
    @if($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif
    
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Futuristic Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Rajdhani', sans-serif;
        }
        .page-title {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .content-area {
            font-family: 'Rajdhani', sans-serif;
            font-size: 1.125rem;
            line-height: 1.8;
        }
        .content-area h1, .content-area h2, .content-area h3 {
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }
        .content-area h1 { font-size: 2rem; }
        .content-area h2 { font-size: 1.5rem; }
        .content-area h3 { font-size: 1.25rem; }
        .content-area p {
            margin-bottom: 1rem;
        }
        .content-area img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
        }
        .nav-link {
            font-family: 'Orbitron', sans-serif;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo/Brand -->
                <a href="/" class="flex items-center space-x-2">
                    <span class="text-2xl font-bold nav-link bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        Unelma
                    </span>
                </a>
                
                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    @php
                        $navPages = \App\Models\Page::where('is_published', true)
                            ->orderBy('order')
                            ->get();
                    @endphp
                    
                    @foreach($navPages as $navPage)
                        <a 
                            href="{{ route('page.show', $navPage->slug) }}" 
                            class="nav-link text-gray-700 hover:text-blue-600 transition duration-200 {{ $navPage->slug === $page->slug ? 'text-blue-600 font-bold' : '' }}"
                        >
                            {{ $navPage->title }}
                        </a>
                    @endforeach
                    
                    <a href="/admin/login" class="nav-link bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                        Admin
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-gray-700" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden pb-4">
                @foreach($navPages as $navPage)
                    <a 
                        href="{{ route('page.show', $navPage->slug) }}" 
                        class="block nav-link text-gray-700 hover:text-blue-600 py-2 {{ $navPage->slug === $page->slug ? 'text-blue-600 font-bold' : '' }}"
                    >
                        {{ $navPage->title }}
                    </a>
                @endforeach
                <a href="/admin/login" class="block nav-link text-blue-600 py-2 mt-2 font-bold">
                    Admin Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Featured Image (if exists) -->
    @if($page->featured_image)
        <div class="w-full h-64 md:h-96 overflow-hidden bg-gray-900">
            <img 
                src="{{ asset('storage/' . $page->featured_image) }}" 
                alt="{{ $page->title }}" 
                class="w-full h-full object-cover opacity-90"
            >
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Title -->
        <header class="mb-8">
            <h1 class="page-title text-4xl md:text-5xl text-gray-900 mb-4">
                {{ $page->title }}
            </h1>
            
            @if($page->meta_description)
                <p class="text-xl text-gray-600 leading-relaxed">
                    {{ $page->meta_description }}
                </p>
            @endif
            
            <div class="mt-4 flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Last updated: {{ $page->updated_at->format('F j, Y') }}
            </div>
        </header>

        <!-- Page Content -->
        <article class="bg-white rounded-xl shadow-lg p-8 md:p-12">
            <div class="content-area prose prose-lg max-w-none text-gray-800">
                {!! $page->content !!}
            </div>
        </article>

        <!-- Product Cards (Only for Products Page) -->
        @if($page->slug === 'products')
            @php
                $products = \App\Models\Product::where('is_active', true)
                    ->orderBy('order')
                    ->orderBy('created_at', 'desc')
                    ->get();
            @endphp

            @if($products->count() > 0)
                <div class="mt-8">
                    <h2 class="page-title text-3xl md:text-4xl text-gray-900 mb-8 text-center">
                        Our Product Offers
                    </h2>
                    
                    <!-- Product Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($products as $product)
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl">
                                <!-- Product Image -->
                                @php
                                    $productImage = $product->image_local_url ?? ($product->image ? '/storage/' . $product->image : null);
                                @endphp
                                @if($productImage)
                                    <div class="h-64 overflow-hidden bg-gray-200">
                                        <img 
                                            src="{{ $productImage }}" 
                                            alt="{{ $product->name }}"
                                            class="w-full h-full object-cover"
                                        >
                                    </div>
                                @else
                                    <div class="h-64 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                        <svg class="w-24 h-24 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Product Info -->
                                <div class="p-6">
                                    <!-- Featured Badge -->
                                    @if($product->is_featured)
                                        <div class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full mb-3" style="font-family: 'Orbitron', sans-serif; letter-spacing: 1px;">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            FEATURED
                                        </div>
                                    @endif
                                    
                                    <!-- Product Name -->
                                    <h3 class="text-xl font-bold text-gray-900 mb-3" style="font-family: 'Orbitron', sans-serif; letter-spacing: 1px;">
                                        {{ $product->name }}
                                    </h3>
                                    
                                    <!-- Product Description -->
                                    @if($product->description)
                                        <p class="text-gray-600 mb-4 line-clamp-3">
                                            {{ $product->description }}
                                        </p>
                                    @endif
                                    
                                    <!-- Price and Action -->
                                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                                        <div>
                                            <span class="text-sm text-gray-500 uppercase" style="font-family: 'Orbitron', sans-serif; letter-spacing: 1px;">Price</span>
                                            <p class="text-3xl font-bold text-blue-600" style="font-family: 'Orbitron', sans-serif;">
                                                €{{ number_format($product->price, 2) }}
                                            </p>
                                        </div>
                                        <button class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg" style="font-family: 'Orbitron', sans-serif; letter-spacing: 1px; text-transform: uppercase; font-size: 0.875rem;">
                                            Learn More
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mt-8">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-blue-700 font-medium" style="font-family: 'Rajdhani', sans-serif;">
                            No products available at the moment. Check back soon!
                        </p>
                    </div>
                </div>
            @endif
        @endif

        <!-- Contact Form (Only for Contact Page) -->
        @if($page->slug === 'contact')
            <div class="bg-white rounded-xl shadow-lg p-8 md:p-12 mt-8">
                <h2 class="page-title text-2xl md:text-3xl text-gray-900 mb-6">
                    Send Us a Message
                </h2>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="font-bold">Success!</p>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Contact Form -->
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2" style="font-family: 'Orbitron', sans-serif; letter-spacing: 1px;">
                            YOUR NAME <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 @error('name') border-red-500 @enderror"
                            placeholder="Enter your full name"
                        >
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2" style="font-family: 'Orbitron', sans-serif; letter-spacing: 1px;">
                            YOUR EMAIL <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 @error('email') border-red-500 @enderror"
                            placeholder="your.email@example.com"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Message Field -->
                    <div>
                        <label for="message" class="block text-sm font-bold text-gray-700 mb-2" style="font-family: 'Orbitron', sans-serif; letter-spacing: 1px;">
                            YOUR MESSAGE <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="6"
                            required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 resize-none @error('message') border-red-500 @enderror"
                            placeholder="Write your message here... (minimum 10 characters)"
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">
                            Please provide as much detail as possible so we can better assist you.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit"
                            class="w-full md:w-auto px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-lg transition duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2"
                            style="font-family: 'Orbitron', sans-serif; letter-spacing: 2px; text-transform: uppercase;"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>Send Message</span>
                        </button>
                    </div>
                </form>

                <!-- Contact Info (Optional) -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                        <div>
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900" style="font-family: 'Orbitron', sans-serif;">Email</h3>
                            <p class="text-gray-600 mt-1">info@unelma.com</p>
                        </div>
                        <div>
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900" style="font-family: 'Orbitron', sans-serif;">Phone</h3>
                            <p class="text-gray-600 mt-1">+358 123 456 789</p>
                        </div>
                        <div>
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 rounded-full mb-3">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900" style="font-family: 'Orbitron', sans-serif;">Location</h3>
                            <p class="text-gray-600 mt-1">Helsinki, Finland</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Navigation Buttons -->
        <div class="mt-8 flex justify-between items-center">
            <a 
                href="/" 
                class="nav-link inline-flex items-center text-gray-600 hover:text-blue-600 transition duration-200"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Home
            </a>
            
            @php
                $allPages = \App\Models\Page::where('is_published', true)
                    ->orderBy('order')
                    ->get();
                $currentIndex = $allPages->search(function($p) use ($page) {
                    return $p->id === $page->id;
                });
                $nextPage = $allPages->get($currentIndex + 1);
            @endphp
            
            @if($nextPage)
                <a 
                    href="{{ route('page.show', $nextPage->slug) }}" 
                    class="nav-link inline-flex items-center text-blue-600 hover:text-blue-700 transition duration-200"
                >
                    Next: {{ $nextPage->title }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Brand -->
                <div>
                    <h3 class="page-title text-2xl mb-4 bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                        Unelma
                    </h3>
                    <p class="text-gray-400">
                        Cloud Backend Server Platform
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="nav-link text-lg mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        @foreach($allPages->take(5) as $footerPage)
                            <li>
                                <a href="{{ route('page.show', $footerPage->slug) }}" class="text-gray-400 hover:text-white transition duration-200">
                                    {{ $footerPage->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <!-- Admin Access -->
                <div>
                    <h4 class="nav-link text-lg mb-4">Admin</h4>
                    <a href="/admin/login" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 nav-link">
                        Admin Dashboard
                    </a>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} React25K@Team 3. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>

