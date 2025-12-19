<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- 
        Font Loading Optimization for All Screen Sizes (Mobile + Desktop)
        Improves FCP (First Contentful Paint) and reduces CLS (Cumulative Layout Shift)
    -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Preload critical fonts for faster FCP on all screen sizes -->
    <link rel="preload" href="https://fonts.gstatic.com/s/rajdhani/v15/LDIxapCSOBg7S-QT7q4AOeekWPrP.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://fonts.gstatic.com/s/rajdhani/v15/LDI2apCSOBg7S-QT7pb0EPOqeeHkkbIxyyg.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://fonts.gstatic.com/s/orbitron/v31/yMJMMIlzdpvBhQQL_SC3X9yhF25-T1nyGy6xpmIyXjU1pg.woff2" as="font" type="font/woff2" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* 
         * Font Metric Overrides for Minimal Layout Shift
         * ================================================
         * Calibrated fallback fonts that match web font metrics,
         * preventing text reflow on both mobile and desktop screens.
         * Uses font-display: swap for immediate text visibility (better FCP).
         */
        
        /* Rajdhani with font-display: swap for immediate visibility */
        @font-face {
            font-family: 'Rajdhani';
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/rajdhani/v15/LDIxapCSOBg7S-QT7q4AOeekWPrP.woff2) format('woff2');
        }
        
        @font-face {
            font-family: 'Rajdhani';
            font-style: normal;
            font-weight: 600;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/rajdhani/v15/LDI2apCSOBg7S-QT7pb0EPOqeeHkkbIxyyg.woff2) format('woff2');
        }
        
        /* Orbitron with font-display: swap */
        @font-face {
            font-family: 'Orbitron';
            font-style: normal;
            font-weight: 400 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/orbitron/v31/yMJMMIlzdpvBhQQL_SC3X9yhF25-T1nyGy6xpmIyXjU1pg.woff2) format('woff2');
        }
        
        /* Rajdhani fallback with matched metrics */
        @font-face {
            font-family: 'Rajdhani Fallback';
            src: local('Arial'), local('Helvetica Neue'), local('Helvetica'), local('sans-serif');
            font-display: swap;
            font-weight: 400;
            size-adjust: 105%;
            ascent-override: 100%;
            descent-override: 30%;
            line-gap-override: 5%;
        }
        
        @font-face {
            font-family: 'Rajdhani Fallback';
            src: local('Arial Bold'), local('Helvetica Neue Bold'), local('Arial'), local('sans-serif');
            font-display: swap;
            font-weight: 600;
            size-adjust: 103%;
            ascent-override: 98%;
            descent-override: 28%;
            line-gap-override: 4%;
        }
        
        /* Orbitron fallback with matched metrics */
        @font-face {
            font-family: 'Orbitron Fallback';
            src: local('Arial Black'), local('Impact'), local('Arial Bold'), local('Arial'), local('sans-serif');
            font-display: swap;
            size-adjust: 95%;
            ascent-override: 95%;
            descent-override: 25%;
            line-gap-override: 0%;
        }
        
        /* Prevent FOIT - ensure text is always visible */
        .fonts-loading * {
            visibility: visible !important;
        }
        
        /* Smooth transition when fonts load */
        .fonts-loaded body {
            transition: opacity 0.1s ease-out;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Rajdhani', 'Rajdhani Fallback', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Background Image */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1639322537228-f710d846310a?q=80&w=2832&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
        }

        /* Overlay for better text readability */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
            z-index: -1;
        }

        /* Navigation */
        .navigation {
            position: absolute;
            top: 30px;
            right: 40px;
            z-index: 100;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-link {
            font-family: 'Orbitron', sans-serif;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* Main Content Container */
        .content-wrapper {
            text-align: center;
            color: #ffffff;
            max-width: 900px;
            padding: 40px;
            animation: fadeInUp 1s ease-out;
        }

        /* Main Heading */
        .main-heading {
            font-family: 'Orbitron', sans-serif;
            font-size: 4rem;
            font-weight: 900;
            margin-bottom: 20px;
            text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.8);
            line-height: 1.2;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Highlight text */
        .highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
        }

        /* Subtitle */
        .subtitle {
            font-family: 'Rajdhani', sans-serif;
            font-size: 1.5rem;
            font-weight: 500;
            margin-bottom: 40px;
            text-shadow: 1px 2px 4px rgba(0, 0, 0, 0.8);
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        /* CTA Button */
        .cta-button {
            font-family: 'Orbitron', sans-serif;
            display: inline-block;
            margin-top: 30px;
            padding: 16px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.6);
        }

        /* Footer */
        .footer {
            font-family: 'Rajdhani', sans-serif;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
            font-weight: 500;
            letter-spacing: 1px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-heading {
                font-size: 2.5rem;
            }

            .subtitle {
                font-size: 1.1rem;
            }

            .navigation {
                top: 20px;
                right: 20px;
            }

            .nav-links {
                flex-direction: column;
                gap: 10px;
            }

            .content-wrapper {
                padding: 20px;
            }
        }

        /* Animated particles effect */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
    </style>
    
    <!-- Font loading detection for FCP optimization on all screen sizes -->
    <script>
        document.documentElement.classList.add('fonts-loading');
        if ('fonts' in document) {
            Promise.all([
                document.fonts.load('400 1em Rajdhani'),
                document.fonts.load('600 1em Rajdhani'),
                document.fonts.load('900 1em Orbitron')
            ]).then(function() {
                document.documentElement.classList.remove('fonts-loading');
                document.documentElement.classList.add('fonts-loaded');
            }).catch(function() {
                document.documentElement.classList.remove('fonts-loading');
            });
            // Timeout fallback for slow connections
            setTimeout(function() {
                document.documentElement.classList.remove('fonts-loading');
            }, 3000);
        } else {
            document.documentElement.classList.remove('fonts-loading');
        }
    </script>
</head>

<body>
    <!-- Background Image -->
    <div class="background"></div>

    <!-- Overlay -->
    <div class="overlay"></div>

    <!-- Navigation -->
    @if (Route::has('login'))
        <nav class="navigation">
            <div class="nav-links">
                @auth
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link">
                        Admin Dashboard
                    </a>
                @else
                    <a href="{{ route('admin.login') }}" class="nav-link">
                        Admin Login
                    </a>
                @endauth
            </div>
        </nav>
    @endif

    <!-- Main Content -->
    <div class="content-wrapper">
        <h1 class="main-heading">
            Welcome to <span class="highlight">Unelma</span><br>
            Cloud Backend Server
        </h1>

        <p class="subtitle">
            Powerful API • Secure Authentication • Scalable Infrastructure
        </p>
        @auth
            <a href="{{ route('admin.users.index') }}" class="cta-button">
                Manage Users Dashboard
            </a>
        @else
            <a href="{{ route('admin.login') }}" class="cta-button">
                Manage Users Dashboard
            </a>
        @endauth
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; {{ date('Y') }} React25K@Team 3. All rights reserved.
    </div>

    <!-- Floating Particles (optional decoration) -->
    <div class="particle" style="width: 3px; height: 3px; top: 20%; left: 10%; animation-delay: 0s;"></div>
    <div class="particle" style="width: 4px; height: 4px; top: 60%; left: 80%; animation-delay: 2s;"></div>
    <div class="particle" style="width: 2px; height: 2px; top: 80%; left: 20%; animation-delay: 4s;"></div>
    <div class="particle" style="width: 5px; height: 5px; top: 40%; left: 90%; animation-delay: 1s;"></div>
</body>

</html>
