<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="HVAC Template">
    <meta name="keywords" content="HVAC, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GARASI62</title>

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
        <div class="offcanvas__widget">
            <a href="#"><i class="fa fa-cart-plus"></i></a>
            <a href="#" class="search-switch"><i class="fa fa-search"></i></a>
            <a href="#" class="primary-btn">Add Car</a>
        </div>
        <div class="offcanvas__logo">
            <a href="./index.html"><img src="img/logo.svg" alt=""></a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <ul class="offcanvas__widget__add">
            <li><i class="fa fa-clock-o"></i> Sales: 08:00 am to 18:00 pm</li>
            <li><i class="fa fa-envelope-o"></i> Info.colorlib@gmail.com</li>
        </ul>
        <div class="offcanvas__phone__num">
            <i class="fa fa-phone"></i>
            <span>(+12) 345 678 910</span>
        </div>
        <div class="offcanvas__social">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-google"></i></a>
            <a href="#"><i class="fa fa-instagram"></i></a>
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