<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteName = \App\Models\SiteSetting::get('site_name', 'GARASI62');
        $siteLogo = \App\Models\SiteSetting::get('site_logo', 'img/logo.svg');
        $siteEmail = \App\Models\SiteSetting::get('site_email', \App\Models\SiteSetting::get('footer_email', 'Colorlib@gmail.com'));
        $siteOperationalHours = \App\Models\SiteSetting::get('site_operational_hours', 'Sales: 08:00 am to 18:00 pm');
        
        // Dynamic Social Links Logic
        $socialLinksJson = \App\Models\SiteSetting::get('footer_social_links', '');
        $socialLinks = [];
        if (!empty($socialLinksJson)) {
            $socialLinks = json_decode($socialLinksJson, true) ?? [];
        } else {
            // Legacy Fallback
            $legacyPlatforms = [
                'facebook' => 'site_facebook',
                'twitter' => 'site_twitter',
                'google' => 'site_google',
                'instagram' => 'site_instagram'
            ];
            $defaultUrls = [
                 'facebook' => 'https://www.facebook.com/',
                 'twitter' => 'https://twitter.com/',
                 'google' => 'https://www.google.com/',
                 'instagram' => 'https://www.instagram.com/'
             ];
            foreach ($legacyPlatforms as $icon => $key) {
                $url = \App\Models\SiteSetting::get($key, $defaultUrls[$icon]);
                if ($url) {
                    $socialLinks[] = ['platform' => ucfirst($icon), 'url' => $url, 'icon' => $icon];
                }
            }
        }
    @endphp

    @hasSection('meta')
    @yield('meta')
    @else
    <meta name="description" content="Garasi62 - Temukan Mobil Impianmu Disini">
    <meta name="keywords" content="Garasi62, Jual Beli Mobil, Sewa Mobil, Mobil Bekas, Mobil Baru">

    <!-- Open Graph General -->
    <meta property="og:title" content="{{ $siteName }} - Temukan Mobil Impianmu Disini">
    <meta property="og:description" content="Garasi62 adalah platform terpercaya untuk jual beli dan sewa mobil. Temukan berbagai pilihan mobil berkualitas dengan harga terbaik.">
    <meta property="og:image" content="{{ asset($siteLogo) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="website">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $siteName }} - Temukan Mobil Impianmu Disini">
    <meta name="twitter:description" content="Garasi62 adalah platform terpercaya untuk jual beli dan sewa mobil. Temukan berbagai pilihan mobil berkualitas dengan harga terbaik.">
    <meta name="twitter:image" content="{{ asset($siteLogo) }}">
    @endif

    @hasSection('title')
    <title>@yield('title')</title>
    @else
    <title>{{ $title ?? $siteName }}</title>
    @endif

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('garasi62/css/bootstrap.min.css')}}" type="text/css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('garasi62/css/font-awesome.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('garasi62/css/elegant-icons.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('garasi62/css/nice-select.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('garasi62/css/magnific-popup.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('garasi62/css/jquery-ui.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('garasi62/css/owl.carousel.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('garasi62/css/slicknav.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('garasi62/css/style.css')}}" type="text/css">

    <style>
        @media (max-width: 360px) {
            .hero.spad {
                padding-top: 40px;
            }
        }

        @media (min-width: 361px) and (max-width: 480px) {
            .hero.spad {
                padding-top: 40px;
            }
        }

        /* Extra small devices (portrait phones, <576px) */
        @media (min-width: 481px) and (max-width: 575.98px) {

            /* CSS khusus HP kecil */
            .hero.spad {
                padding-top: 48px;
            }
        }

        /* Small devices (landscape phones, ≥576px and <768px) */
        @media (min-width: 576px) and (max-width: 767.98px) {
            .hero.spad {
                padding-top: 56px;
            }
        }

        /* Medium devices (tablets, ≥768px and <992px) */
        @media (min-width: 768px) and (max-width: 991.98px) {}

        /* Large devices (desktops, ≥992px and <1200px) */
        @media (min-width: 992px) and (max-width: 1199.98px) {}

        /* Extra large devices (large desktops, ≥1200px and <1400px) */
        @media (min-width: 1200px) and (max-width: 1399.98px) {}

        /* Extra extra large devices (ultra wide, ≥1400px) */
        @media (min-width: 1400px) {}

        body {
            padding-top: 100px;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 9999;
        }
    </style>

    @stack('head')
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <nav class="offcanvas__nav">
            <ul>
                <li><a href="/" class="{{ request()->routeIs('home') || request()->routeIs('index') ? 'active' : '' }}">Home</a></li>
                <li><a href="/car" class="{{ request()->routeIs('cars') ? 'active' : '' }}">Cars</a></li>
                <li><a href="/blog" class="{{ request()->routeIs('blog') ? 'active' : '' }}">Blog</a></li>
                <li><a href="/about" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                <li><a href="/contact" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
            </ul>
        </nav>
        <div class="offcanvas__auth">
            @auth
            <a href="{{ route('dashboard') }}" class="site-btn">Dashboard</a>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="site-btn">Keluar</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="site-btn">Masuk</a>
            <a href="{{ route('register') }}" class="site-btn">Daftar</a>
            @endauth
        </div>
        <ul class="offcanvas__widget__add">
            <li><i class="fa fa-clock-o"></i> {{ $siteOperationalHours }}</li>
            <li><i class="fa fa-envelope-o"></i> {{ $siteEmail }}</li>
        </ul>
        <div class="offcanvas__phone__num">
            <i class="fa fa-phone"></i>
            <span>(+12) 345 678 910</span>
        </div>
        <div class="offcanvas__social">
            @foreach($socialLinks as $social)
                <a href="{{ $social['url'] }}" target="_blank" title="{{ $social['platform'] ?? '' }}"><i class="fa fa-{{ $social['icon'] ?? 'link' }}"></i></a>
            @endforeach
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    @include('template.header')
    <!-- Header Section End -->

    @yield('content')

    <!-- Footer Section Begin -->
    @include('template.footer')
    <!-- Footer Section End -->

    <!-- Search Begin -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search End -->

    <!-- Js Plugins -->
    <script src="{{ asset('garasi62/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{ asset('garasi62/js/bootstrap.min.js')}}"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('garasi62/js/jquery.nice-select.min.js')}}"></script>
    <script src="{{ asset('garasi62/js/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('garasi62/js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{ asset('garasi62/js/mixitup.min.js')}}"></script>
    <script src="{{ asset('garasi62/js/jquery.slicknav.js')}}"></script>
    <script src="{{ asset('garasi62/js/owl.carousel.min.js')}}"></script>
    <script src="{{ asset('garasi62/js/main.js')}}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')
</body>

</html>
