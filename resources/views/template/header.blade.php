<header class="header">
    <!-- Top Bar -->
    <div class="header__top d-none d-md-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-6">
                    <ul class="header__top__widget">
                        <li><i class="fa fa-clock-o"></i> Sales: 08:00 am to 18:00 pm</li>
                        <li><i class="fa fa-envelope-o"></i> info@garasi62.co.id</li>
                    </ul>
                </div>
                <div class="col-lg-5 col-md-6">
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
    
    <!-- Main Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #19191a; padding: 15px 0;">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="/">
                <img src="{{ asset('img/logo.svg') }}" alt="GARASI62" style="height: 40px;">
            </a>
            
            <!-- Search Icon (Desktop) -->
            <a href="#" class="search-switch text-white text-decoration-none d-none d-lg-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                <i class="fa fa-search"></i>
            </a>
            
            <!-- Hamburger Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navbar Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-uppercase {{ request()->routeIs('home') || request()->routeIs('index') ? 'active' : '' }}" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase {{ request()->routeIs('cars') ? 'active' : '' }}" href="/car">Cars</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase {{ request()->routeIs('blog') ? 'active' : '' }}" href="/blog">Blog</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link text-uppercase dropdown-toggle" href="#" id="pagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pages
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="pagesDropdown">
                            <li><a class="dropdown-item" href="/about">About Us</a></li>
                            <li><a class="dropdown-item" href="/car-details">Car Details</a></li>
                            <li><a class="dropdown-item" href="/blog-details">Blog Details</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase {{ request()->routeIs('about') ? 'active' : '' }}" href="/about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase {{ request()->routeIs('contact') ? 'active' : '' }}" href="/contact">Contact</a>
                    </li>
                </ul>
                
                <!-- Right Side: Search, Auth Buttons -->
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <!-- Search Icon (Mobile) -->
                    <a href="#" class="search-switch text-white text-decoration-none d-lg-none d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fa fa-search"></i>
                    </a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-danger text-white text-decoration-none" style="white-space: nowrap; border-radius: 5px; padding: 8px 16px;">
                            Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-light" style="white-space: nowrap; border-radius: 5px; padding: 8px 16px;">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-danger text-white text-decoration-none" style="white-space: nowrap; border-radius: 5px; padding: 8px 16px;">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light text-decoration-none" style="white-space: nowrap; border-radius: 5px; padding: 8px 16px;">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    @if(request()->routeIs('dashboard'))
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option set-bg" data-setbg="img/breadcrumb-bg.jpg">
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

<style>
/* Navbar Responsive Styles */
.header .navbar {
    background-color: #19191a !important;
    padding: 10px 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.header .navbar-brand {
    transition: transform 0.3s ease;
}

.header .navbar-brand:hover {
    transform: scale(1.05);
}

.header .navbar-brand img {
    height: 35px;
    transition: all 0.3s ease;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}

.header .navbar-nav .nav-link {
    color: #ffffff !important;
    font-weight: 500;
    padding: 8px 15px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    border-radius: 4px;
    margin: 0 2px;
}

.header .navbar-nav .nav-link::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #dc2626, #ef4444);
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 2px;
}

.header .navbar-nav .nav-link:hover::before,
.header .navbar-nav .nav-link.active::before {
    width: calc(100% - 30px);
}

.header .navbar-nav .nav-link:hover {
    color: #dc2626 !important;
    background-color: rgba(220, 38, 38, 0.1);
    transform: translateY(-2px);
}

.header .navbar-nav .nav-link.active {
    color: #dc2626 !important;
    background-color: rgba(220, 38, 38, 0.15);
}

/* Dropdown Animation Styles */
.header .navbar-nav .dropdown {
    position: relative;
}

.header .navbar-nav .dropdown-menu {
    background: linear-gradient(135deg, #1f1f1f 0%, #19191a 100%);
    border: 1px solid rgba(220, 38, 38, 0.2);
    border-radius: 8px;
    padding: 8px 0;
    margin-top: 10px;
    min-width: 200px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(220, 38, 38, 0.1);
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1050;
    pointer-events: none;
}

.header .navbar-nav .dropdown-menu.show {
    opacity: 1;
    transform: translateY(0) scale(1);
    pointer-events: auto;
}


.header .navbar-nav .dropdown-item {
    color: #ffffff;
    padding: 10px 20px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    border-left: 3px solid transparent;
    margin: 2px 8px;
    border-radius: 4px;
}

.header .navbar-nav .dropdown-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 0;
    background: linear-gradient(90deg, #dc2626, #ef4444);
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 4px 0 0 4px;
    z-index: -1;
}

.header .navbar-nav .dropdown-item:hover {
    background: linear-gradient(90deg, rgba(220, 38, 38, 0.2), rgba(239, 68, 68, 0.15));
    color: #ffffff;
    transform: translateX(5px);
    border-left-color: #dc2626;
    padding-left: 25px;
}

.header .navbar-nav .dropdown-item:hover::before {
    width: 100%;
}

.header .navbar-nav .dropdown-toggle::after {
    transition: transform 0.3s ease;
    margin-left: 8px;
}

.header .navbar-nav .dropdown.show .dropdown-toggle::after {
    transform: rotate(180deg);
}

.header .navbar-toggler {
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 6px 10px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.header .navbar-toggler:hover {
    background-color: rgba(220, 38, 38, 0.1);
    border-color: rgba(220, 38, 38, 0.5);
    transform: scale(1.05);
}

.header .navbar-toggler:focus {
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.25);
}

.header .navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    transition: transform 0.3s ease;
}

/* Button Styles with Animation */
.header .btn-danger {
    background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
    border: none;
    font-size: 14px;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.header .btn-danger::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.header .btn-danger:hover::before {
    width: 300px;
    height: 300px;
}

.header .btn-danger:hover {
    background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
}

.header .btn-danger:active {
    transform: translateY(0);
    box-shadow: 0 2px 10px rgba(220, 38, 38, 0.3);
}

.header .btn-outline-light {
    border: 2px solid rgba(255, 255, 255, 0.5);
    color: #ffffff;
    font-size: 14px;
    font-weight: 600;
    background: transparent;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.header .btn-outline-light::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.5s;
}

.header .btn-outline-light:hover::before {
    left: 100%;
}

.header .btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.9);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
}

.header .btn-outline-light:active {
    transform: translateY(0);
}

/* Search Icon Animation */
.header .search-switch {
    transition: all 0.3s ease;
    border-radius: 50%;
}

.header .search-switch:hover {
    background-color: rgba(220, 38, 38, 0.2);
    transform: scale(1.1) rotate(90deg);
}

/* Responsive Styles */
@media (max-width: 991.98px) {
    .header .navbar-collapse {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .header .navbar-nav {
        margin-bottom: 15px;
    }
    
    .header .navbar-nav .nav-item {
        margin-bottom: 5px;
    }
    
    .header .navbar-nav .nav-link {
        padding: 10px 15px !important;
    }
    
    .header .navbar-nav .nav-link.active::after {
        display: none;
    }
    
    .header .d-flex.gap-2 {
        flex-direction: column;
        width: 100%;
        gap: 10px !important;
    }
    
    .header .btn {
        width: 100%;
        text-align: center;
    }
    
    .header .search-switch {
        margin-bottom: 10px;
    }
}

@media (max-width: 767.98px) {
    .header .navbar-brand img {
        height: 30px;
    }
    
    .header .navbar {
        padding: 8px 0;
    }
    
    .header__top {
        display: none !important;
    }
}

@media (max-width: 575.98px) {
    .header .navbar-brand img {
        height: 28px;
    }
    
    .header .btn {
        font-size: 13px;
        padding: 6px 12px !important;
    }
}

/* Desktop: Navbar lebih kecil saat scroll */
@media (min-width: 992px) {
    .header .navbar-nav .nav-link {
        font-size: 14px;
        padding: 6px 12px !important;
    }
    
    .header .btn {
        font-size: 13px;
        padding: 6px 14px !important;
    }
}

/* Top Bar Responsive */
@media (max-width: 991.98px) {
    .header__top__widget li {
        font-size: 12px;
        padding: 5px 0;
    }
    
    .header__top__phone span {
        font-size: 12px;
    }
    
    /* Mobile Dropdown Animation */
    .header .navbar-nav .dropdown-menu {
        margin-top: 5px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
}

/* Smooth Scroll for Navbar */
.header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    width: 100%;
    z-index: 1050;
    transition: all 0.3s ease;
}

/* Navbar Scroll Effect */
.header.scrolled .navbar {
    padding: 8px 0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.header.scrolled .navbar-brand img {
    height: 30px;
}
</style>

<script>
// Wait for Bootstrap to be loaded
function initDropdownAnimations() {
    if (typeof bootstrap === 'undefined') {
        setTimeout(initDropdownAnimations, 100);
        return;
    }
    
    // Dropdown Animation Enhancement
    const dropdowns = document.querySelectorAll('.navbar-nav .dropdown');
    
    dropdowns.forEach(dropdown => {
        const dropdownMenu = dropdown.querySelector('.dropdown-menu');
        const dropdownToggle = dropdown.querySelector('.dropdown-toggle');
        
        if (dropdownMenu && dropdownToggle) {
            // Initialize Bootstrap Dropdown if not already initialized
            let bsDropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
            if (!bsDropdown) {
                try {
                    bsDropdown = new bootstrap.Dropdown(dropdownToggle, {
                        offset: 10,
                        boundary: 'viewport'
                    });
                } catch (e) {
                    console.log('Bootstrap dropdown initialization:', e);
                }
            }
            
            // Handle Bootstrap 5 dropdown events
            dropdown.addEventListener('show.bs.dropdown', function(e) {
                dropdownMenu.style.opacity = '0';
                dropdownMenu.style.transform = 'translateY(-10px) scale(0.95)';
            });
            
            dropdown.addEventListener('shown.bs.dropdown', function(e) {
                // Use setTimeout to ensure smooth animation
                setTimeout(() => {
                    dropdownMenu.style.opacity = '1';
                    dropdownMenu.style.transform = 'translateY(0) scale(1)';
                }, 10);
            });
            
            dropdown.addEventListener('hide.bs.dropdown', function(e) {
                dropdownMenu.style.opacity = '0';
                dropdownMenu.style.transform = 'translateY(-10px) scale(0.95)';
            });
            
            dropdown.addEventListener('hidden.bs.dropdown', function(e) {
                // Reset styles after animation
                setTimeout(() => {
                    dropdownMenu.style.opacity = '';
                    dropdownMenu.style.transform = '';
                }, 400);
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initDropdownAnimations();
});
    
    // Navbar Scroll Effect
    const header = document.querySelector('.header');
    
    if (header) {
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset || window.scrollY;
            
            if (currentScroll > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }
    
    function adjustBodyPadding() {
        const headerEl = document.querySelector('.header');
        if (!headerEl) return;
        document.body.style.paddingTop = headerEl.offsetHeight + 'px';
        document.documentElement.style.setProperty('--header-height', headerEl.offsetHeight + 'px');
    }
    
    adjustBodyPadding();
    window.addEventListener('resize', adjustBodyPadding);
    
    // Smooth animation for mobile menu collapse
    const navbarCollapse = document.getElementById('navbarNav');
    if (navbarCollapse) {
        navbarCollapse.addEventListener('show.bs.collapse', function() {
            this.style.maxHeight = '0';
            setTimeout(() => {
                this.style.maxHeight = this.scrollHeight + 'px';
            }, 10);
        });
        
        navbarCollapse.addEventListener('shown.bs.collapse', function() {
            this.style.maxHeight = 'none';
        });
        
        navbarCollapse.addEventListener('hide.bs.collapse', function() {
            this.style.maxHeight = this.scrollHeight + 'px';
            setTimeout(() => {
                this.style.maxHeight = '0';
            }, 10);
        });
    }
});

// Add CSS animations for mobile menu
const style = document.createElement('style');
style.textContent = `
    #navbarNav {
        transition: max-height 0.3s ease, opacity 0.3s ease;
        overflow: hidden;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
`;
document.head.appendChild(style);
</script>
