<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <ul class="header__top__widget">
                        <li><i class="fa fa-clock-o"></i> Sales: 08:00 am to 18:00 pm</li>
                        <li><i class="fa fa-envelope-o"></i> info@garasi62.co.id</li>
                    </ul>
                </div>
                <div class="col-lg-5">
                    <div class="header__top__right">
                        <div class="header__top__phone">
                            <i class="fa fa-phone"></i>
                            <span>(WA) 08210008062</span>
                        </div>
                        <div class="header__top__social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-google"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
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
                    <a href="/"><img src="{{ asset('img/logo.svg') }}" alt=""></a>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="header__nav d-flex align-items-center justify-content-between w-100">
                    <nav class="header__menu flex-grow-1 text-center">
                        <ul class="d-flex align-items-center justify-content-center list-unstyled mb-0">
                            <li class="mx-3"><a href="/" class="text-white text-uppercase text-decoration-none fw-normal {{ request()->routeIs('home') || request()->routeIs('index') ? 'active' : '' }}" style="{{ request()->routeIs('home') || request()->routeIs('index') ? 'border-bottom: 2px solid #dc3545; padding-bottom: 2px;' : '' }}">Home</a></li>
                            <li class="mx-3"><a href="/car" class="text-white text-uppercase text-decoration-none fw-normal {{ request()->routeIs('cars') ? 'active' : '' }}" style="{{ request()->routeIs('cars') ? 'border-bottom: 2px solid #dc3545; padding-bottom: 2px;' : '' }}">Cars</a></li>
                            <li class="mx-3"><a href="/blog" class="text-white text-uppercase text-decoration-none fw-normal {{ request()->routeIs('blog') ? 'active' : '' }}" style="{{ request()->routeIs('blog') ? 'border-bottom: 2px solid #dc3545; padding-bottom: 2px;' : '' }}">Blog</a></li>
                            <li class="mx-3"><a href="#" class="text-white text-uppercase text-decoration-none fw-normal">Pages</a>
                                <ul class="dropdown">
                                    <li><a href="/about">About Us</a></li>
                                    <li><a href="/car-details">Car Details</a></li>
                                    <li><a href="/blog-details">Blog Details</a></li>
                                </ul>
                            </li>
                            <li class="mx-3"><a href="/about" class="text-white text-uppercase text-decoration-none fw-normal {{ request()->routeIs('about') ? 'active' : '' }}" style="{{ request()->routeIs('about') ? 'border-bottom: 2px solid #dc3545; padding-bottom: 2px;' : '' }}">About</a></li>
                            <li class="mx-3"><a href="/contact" class="text-white text-uppercase text-decoration-none fw-normal {{ request()->routeIs('contact') ? 'active' : '' }}" style="{{ request()->routeIs('contact') ? 'border-bottom: 2px solid #dc3545; padding-bottom: 2px;' : '' }}">Contact</a></li>
                        </ul>
                    </nav>
                    <div class="header__nav__widget d-flex align-items-center" style="gap: 12px;">
                        <a href="#" class="search-switch text-white text-decoration-none d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fa fa-search"></i></a>
                        @auth
                            @if(Auth::user()->role === 'buyer' || Auth::user()->role === 'seller')
                            <a href="{{ Auth::user()->role === 'buyer' ? route('chat.index') : route('chat.seller.index') }}" class="text-white text-decoration-none d-flex align-items-center justify-content-center position-relative" style="width: 32px; height: 32px; font-size: 18px;" title="Obrolan">
                                <i class="fa fa-comments"></i>
                                <span id="headerChatBadge" style="position: absolute; top: -5px; right: -5px; background: #dc2626; color: #fff; border-radius: 50%; width: 18px; height: 18px; display: none; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;">0</span>
                            </a>
                            @endif
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