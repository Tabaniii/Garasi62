<header class="header">
    @php
        $siteName = \App\Models\SiteSetting::get('site_name', 'GARASI62');
        $siteEmail = \App\Models\SiteSetting::get('site_email', \App\Models\SiteSetting::get('footer_email', 'Colorlib@gmail.com'));
        $siteOperationalHours = \App\Models\SiteSetting::get('site_operational_hours', 'Sales: 08:00 am to 18:00 pm');
        $siteLogo = \App\Models\SiteSetting::get('site_logo', 'img/logo.svg');
        $sitePhone = \App\Models\SiteSetting::get('footer_phone', '(+12) 345 678 910');
        
        // Dynamic Social Links Logic
        $socialLinksJson = \App\Models\SiteSetting::get('footer_social_links', '');
        $socialLinks = [];
        if (!empty($socialLinksJson)) {
            $socialLinks = json_decode($socialLinksJson, true) ?? [];
        } else {
            // Legacy Header Fallback
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
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <ul class="header__top__widget">
                        <li><i class="fa fa-clock-o"></i> {{ $siteOperationalHours }}</li>
                        <li><i class="fa fa-envelope-o"></i> {{ $siteEmail }}</li>
                    </ul>
                </div>
                <div class="col-lg-5">
                    <div class="header__top__right">
                        <div class="header__top__phone">
                            <i class="fa fa-phone"></i>
                            <span>{{ $sitePhone }}</span>
                        </div>
                        <div class="header__top__social">
                            @foreach($socialLinks as $social)
                                <a href="{{ $social['url'] }}" target="_blank" title="{{ $social['platform'] ?? '' }}"><i class="fa fa-{{ $social['icon'] ?? 'link' }}"></i></a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-2">
                <div class="header__logo">
                    <a href="/"><img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}"></a>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="header__nav d-flex align-items-center justify-content-between w-100">
                    <nav class="header__menu flex-grow-1 text-center">
                        <ul class="d-flex align-items-center justify-content-center list-unstyled mb-0">
                            <li class="mx-3"><a href="/" class="text-white text-uppercase text-decoration-none fw-normal {{ request()->routeIs('home') || request()->routeIs('index') ? 'active' : '' }}" style="{{ request()->routeIs('home') || request()->routeIs('index') ? 'border-bottom: 2px solid #dc3545; padding-bottom: 2px;' : '' }}">Home</a></li>
                            <li class="mx-3"><a href="/car" class="text-white text-uppercase text-decoration-none fw-normal {{ request()->routeIs('cars') ? 'active' : '' }}" style="{{ request()->routeIs('cars') ? 'border-bottom: 2px solid #dc3545; padding-bottom: 2px;' : '' }}">Cars</a></li>
                            <li class="mx-3"><a href="/blog" class="text-white text-uppercase text-decoration-none fw-normal {{ request()->routeIs('blog') ? 'active' : '' }}" style="{{ request()->routeIs('blog') ? 'border-bottom: 2px solid #dc3545; padding-bottom: 2px;' : '' }}">Blog</a></li>
                            <li class="mx-3"><a href="/about" class="text-white text-uppercase text-decoration-none fw-normal {{ request()->routeIs('about') ? 'active' : '' }}" style="{{ request()->routeIs('about') ? 'border-bottom: 2px solid #dc3545; padding-bottom: 2px;' : '' }}">About</a></li>
                            <li class="mx-3"><a href="/contact" class="text-white text-uppercase text-decoration-none fw-normal {{ request()->routeIs('contact') ? 'active' : '' }}" style="{{ request()->routeIs('contact') ? 'border-bottom: 2px solid #dc3545; padding-bottom: 2px;' : '' }}">Contact</a></li>
                        </ul>
                    </nav>
                    <div class="header__nav__widget d-flex align-items-center" style="gap: 12px;">
                        <a href="#" class="search-switch text-white text-decoration-none d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fa fa-search"></i></a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="site-btn" style="white-space: nowrap; text-decoration: none; border-radius: 5px;">Dashboard</a>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                                @csrf
                                <button type="submit" class="site-btn" style="white-space: nowrap; text-decoration: none; border-radius: 5px;">Keluar</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="site-btn" style="white-space: nowrap; text-decoration: none; border-radius: 5px;">Masuk</a>
                            <a href="{{ route('register') }}" class="site-btn" style="white-space: nowrap; text-decoration: none; border-radius: 5px;">Daftar</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        <div class="canvas__open">
            <span class="fa fa-bars"></span>
        </div>
    </div>
    
    @if(request()->routeIs('dashboard'))
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option set-bg" data-setbg="{{ asset('img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Dashboard</h2>
                        <div class="breadcrumb__links">
                            <a href="/"><i class="fa fa-home"></i> Home</a>
                            <span>Dashboard</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->
    @endif
</header>
