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
    
    @auth
    <!-- Chat Notification Popup -->
    <div id="chatNotificationPopup" class="chat-notification-popup" style="display: none;">
        <div class="chat-notification-content">
            <div class="chat-notification-icon">
                <i class="fa fa-comments"></i>
            </div>
            <div class="chat-notification-body">
                <div class="chat-notification-title" id="chatNotificationTitle">Pesan Baru</div>
                <div class="chat-notification-message" id="chatNotificationMessage"></div>
            </div>
            <button class="chat-notification-close" onclick="closeChatNotification()">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Load Pusher JS -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <!-- Load Laravel Echo -->
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.min.js"></script>
    
    <style>
        /* Chat Notification Popup */
        .chat-notification-popup {
            position: fixed;
            top: 90px;
            right: 30px;
            z-index: 10000;
            animation: slideInRight 0.3s ease-out;
        }

        .chat-notification-content {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 320px;
            max-width: 400px;
            border-left: 4px solid #df2d24;
            position: relative;
        }

        .chat-notification-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #df2d24, #b91c1c);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .chat-notification-body {
            flex: 1;
            min-width: 0;
        }

        .chat-notification-title {
            font-weight: 700;
            font-size: 14px;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .chat-notification-message {
            font-size: 13px;
            color: #6c757d;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .chat-notification-close {
            background: transparent;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .chat-notification-close:hover {
            background: rgba(0, 0, 0, 0.05);
            color: #df2d24;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }

        .chat-notification-popup.hiding {
            animation: slideOutRight 0.3s ease-out forwards;
        }

        @media (max-width: 768px) {
            .chat-notification-popup {
                top: 70px;
                right: 15px;
                left: 15px;
            }

            .chat-notification-content {
                min-width: auto;
                max-width: 100%;
            }
        }
    </style>
    
    <script>
        // Initialize Laravel Echo for notifications
        window.Pusher = Pusher;
        
        const pusherKey = '{{ config('broadcasting.connections.pusher.key') }}';
        const pusherCluster = '{{ config('broadcasting.connections.pusher.options.cluster', 'ap1') }}';
        const currentUserId = {{ Auth::id() }};
        
        if (pusherKey) {
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: pusherKey,
                cluster: pusherCluster,
                forceTLS: true,
                encrypted: true,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                },
                enabledTransports: ['ws', 'wss'],
                disableStats: true,
            });

            // Listen for new chat messages
            window.Echo.private(`user.${currentUserId}`)
                .listen('.NewChatMessage', (e) => {
                    console.log('📨 New chat message notification:', e);
                    
                    // Update badge if exists
                    if (typeof updateChatBadge === 'function') {
                        updateChatBadge();
                    }
                    
                    // Update chat list if exists
                    if (typeof refreshChatList === 'function' && window.location.pathname.includes('/chat')) {
                        refreshChatList();
                    }
                    
                    // Show popup notification
                    showChatNotification(e.notification);
                    
                    // Show browser notification if available
                    if ('Notification' in window && Notification.permission === 'granted') {
                        new Notification(e.notification.title || 'Pesan Baru', {
                            body: e.notification.body || 'Anda mendapat pesan baru',
                            icon: '/img/logo.png',
                            tag: `chat-${e.chat_id}`,
                        });
                    }
                });
        }

        // Function to update chat badge
        function updateChatBadge() {
            fetch('{{ route('chat.unread-count') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update badge in sidebar (admin layout)
                    const badge = document.getElementById('chatBadge');
                    if (badge) {
                        if (data.unread_count > 0) {
                            badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                            badge.style.display = 'inline-block';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                    
                    // Update badge in header (public layout)
                    const headerBadge = document.getElementById('headerChatBadge');
                    if (headerBadge) {
                        if (data.unread_count > 0) {
                            headerBadge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                            headerBadge.style.display = 'flex';
                        } else {
                            headerBadge.style.display = 'none';
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching unread count:', error);
            });
        }

        // Function to show chat notification popup
        function showChatNotification(notification) {
            const popup = document.getElementById('chatNotificationPopup');
            const title = document.getElementById('chatNotificationTitle');
            const message = document.getElementById('chatNotificationMessage');
            
            if (popup && title && message) {
                title.textContent = notification.title || 'Pesan Baru';
                message.textContent = notification.body || 'Anda mendapat pesan baru';
                
                popup.style.display = 'block';
                
                // Auto hide after 5 seconds
                setTimeout(() => {
                    closeChatNotification();
                }, 5000);
                
                // Make popup clickable to go to chat
                popup.style.cursor = 'pointer';
                popup.onclick = function() {
                    if (notification.chat_id) {
                        // Close popup immediately
                        closeChatNotification();
                        // Navigate to chat
                        window.location.href = '{{ route('chat.show', '') }}/' + notification.chat_id;
                    }
                };
            }
        }

        // Function to close chat notification
        function closeChatNotification() {
            const popup = document.getElementById('chatNotificationPopup');
            if (popup) {
                popup.classList.add('hiding');
                setTimeout(() => {
                    popup.style.display = 'none';
                    popup.classList.remove('hiding');
                }, 300);
            }
        }

        // Request notification permission on page load
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission().then(permission => {
                console.log('Notification permission:', permission);
            });
        }

        // Load initial badge count
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof updateChatBadge === 'function') {
                updateChatBadge();
            }
            
            // Update badge every 30 seconds as fallback
            setInterval(function() {
                if (typeof updateChatBadge === 'function') {
                    updateChatBadge();
                }
            }, 30000);
        });
    </script>
    @endauth
    
    @stack('scripts')
</body>

</html>