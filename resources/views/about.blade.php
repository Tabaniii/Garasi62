@extends('template.temp')

<!-- @section('title', 'Home - GARASI62') Set the title for the page -->
@include('components.messages-widget')
@section('content')
    <!-- Breadcrumb End -->
    <div class="breadcrumb-option set-bg" data-setbg="img/breadcrumb-bg.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>About Us</h2>
                        <div class="breadcrumb__links">
                            <a href="./index.html"><i class="fa fa-home"></i> Home</a>
                            <span>About Us</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Begin -->

    <!-- About Us Section Begin -->
    <section class="about spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title about-title">
                        <h2>Wellcome To HVAC Auto Online <br />We Provide Everything You Need To A Car</h2>
                        <p>First I will explain what contextual advertising is. Contextual advertising means the
                            advertising of products on a website according to<br /> the content the page is displaying.
                            For example if the content of a website was information on a Ford truck then the
                            advertisements</p>
                    </div>
                </div>
            </div>
            <div class="about__feature">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="about__feature__item">
                            <img src="img/about/af-1.png" alt="">
                            <h5>Quality Assurance System</h5>
                            <p>It seems though that some of the biggest problems with the internet advertising trends
                                are the lack of</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="about__feature__item">
                            <img src="img/about/af-2.png" alt="">
                            <h5>Accurate Testing Processes</h5>
                            <p>Where do you register your complaints? How can you protest in any form against companies
                                whose</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="about__feature__item">
                            <img src="img/about/af-3.png" alt="">
                            <h5>Infrastructure Integration Technology</h5>
                            <p>So in final analysis: it’s true, I hate peeping Toms, but if I had to choose, I’d take
                                one any day over an</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="about__pic">
                        <img src="img/about/about-pic.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="about__item">
                        <h5>Our Mission</h5>
                        <p>Now, I’m not like Robin, that weirdo from my cultural anthropology class; I think that
                            advertising is something that has its place in our society; which for better or worse is
                            structured along a marketplace economy. But, simply because I feel advertising has a right
                            to exist, doesn’t mean that I like or agree with it, in its</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="about__item">
                        <h5>Our Vision</h5>
                        <p>Where do you register your complaints? How can you protest in any form against companies
                            whose advertising techniques you don’t agree with? You don’t. And on another point of
                            difference between traditional products and their advertising and those of the internet
                            nature, simply ignoring internet advertising is </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Us Section End -->

    <!-- Call Section Begin -->
    <section class="call spad set-bg" data-setbg="img/about/call-bg.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="call__text">
                        <div class="section-title">
                            <h2>Request A Call Back</h2>
                            <p>Posters had been a very beneficial marketing tool because it had paved to deliver an
                                effective message that conveyed customer’s</p>
                        </div>
                        <a href="#">Contact Us</a>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-1 col-md-6">
                    <form action="#" class="call__form">
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="text" placeholder="Name">
                            </div>
                            <div class="col-lg-6">
                                <input type="text" placeholder="Email">
                            </div>
                            <div class="col-lg-6">
                                <input type="text" placeholder="Phone">
                            </div>
                            <div class="col-lg-6">
                                <select>
                                    <option value="">Choose Our Services</option>
                                    <option value="">Buy Cars</option>
                                    <option value="">Sell Cars</option>
                                    <option value="">Wash Cars</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="site-btn">Submit Now</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Call Section End -->

    <!-- Team Section Begin -->
    <section class="team spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title team-title">
                        <span>Our Team</span>
                        <h2>Meet Our Expert</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="team__item">
                        <div class="team__item__pic">
                            <img src="img/about/team-1.jpg" alt="">
                        </div>
                        <div class="team__item__text">
                            <h5>John Smith</h5>
                            <span>Marketing</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="team__item">
                        <div class="team__item__pic">
                            <img src="img/about/team-2.jpg" alt="">
                        </div>
                        <div class="team__item__text">
                            <h5>Christine Wise</h5>
                            <span>C.E.O</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="team__item">
                        <div class="team__item__pic">
                            <img src="img/about/team-3.jpg" alt="">
                        </div>
                        <div class="team__item__text">
                            <h5>Sean Robbins</h5>
                            <span>Manager</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="team__item">
                        <div class="team__item__pic">
                            <img src="img/about/team-4.jpg" alt="">
                        </div>
                        <div class="team__item__text">
                            <h5>Lucy Myers</h5>
                            <span>Delivary</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Team Section End -->

    <section class="testimonial spad" style="background: #f8f9fa; position: relative; overflow: hidden; padding: 80px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title testimonial-title" style="text-align: center; margin-bottom: 50px;">
                        <span style="color: #dc2626; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; font-size: 14px; display: inline-block; margin-bottom: 10px;">Testimonials</span>
                        <h2 style="font-size: 42px; font-weight: 800; margin: 15px 0 20px; color: #1a1a1a; position: relative; word-wrap: break-word; overflow-wrap: break-word;">
                            What People Say About Us
                            <span style="position: absolute; bottom: -10px; left: 50%; width: 80px; height: 4px; background: linear-gradient(90deg, #dc2626, #991b1b); border-radius: 5px;"></span>
                        </h2>
                        <p style="font-size: 16px; color: #6b7280; max-width: 600px; margin: 0 auto; word-wrap: break-word; overflow-wrap: break-word;">Our customers are our biggest supporters. What do they think of us?</p>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 50px;">
                <div class="col-lg-12">
                    <div class="testimonial__slider owl-carousel">
                        @forelse($testimonials as $testimonial)
                            <div class="testimonial__item__wrapper">
                                <div class="testimonial__item" style="background: #ffffff; border-radius: 5px; padding: 45px 40px; border: 1px solid #f0f0f0; transition: all 0.3s ease; position: relative; overflow: visible; min-height: 300px; display: flex; flex-direction: column; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); height: 100%; width: 500px; max-width: 100%;">
                                    <!-- Decorative Quote Icon -->
                                    <div style="position: absolute; top: 25px; right: 25px; opacity: 0.08; font-size: 120px; color: #dc2626; line-height: 1; z-index: 0; pointer-events: none;">
                                        <i class="fa fa-quote-right"></i>
                                    </div>
                                    
                                    <div class="testimonial__item__author" style="display: flex; align-items: flex-start; margin-bottom: 25px; position: relative; z-index: 1; flex-shrink: 0;">
                                        <div class="testimonial__item__author__pic" style="margin-right: 20px; position: relative; flex-shrink: 0;">
                                            @if($testimonial->image)
                                                <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}" style="width: 80px; height: 80px; border-radius: 5px; object-fit: cover; border: 4px solid #dc2626; display: block;">
                                            @else
                                                <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #dc2626, #991b1b); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 32px; color: #ffffff; border: 4px solid #ffffff; flex-shrink: 0; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);">
                                                    {{ strtoupper(mb_substr($testimonial->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="testimonial__item__author__text" style="flex: 1; min-width: 0; word-wrap: break-word; overflow-wrap: break-word; overflow: visible;">
                                            <div class="rating" style="margin-bottom: 12px; display: flex; flex-wrap: wrap; gap: 3px;">
                                                @for($i = 0; $i < $testimonial->rating; $i++)
                                                    <i class="fa fa-star" style="color: #fbbf24; font-size: 16px; flex-shrink: 0;"></i>
                                                @endfor
                                                @for($i = $testimonial->rating; $i < 5; $i++)
                                                    <i class="fa fa-star" style="color: #e5e7eb; font-size: 16px; flex-shrink: 0;"></i>
                                                @endfor
                                            </div>
                                            <h5 style="font-size: 18px; font-weight: 700; color: #1a1a1a; margin: 0 0 6px 0; word-wrap: break-word; overflow-wrap: break-word; line-height: 1.5; display: block; width: 100%; white-space: normal; overflow: visible;">
                                                <span style="display: block; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; overflow: visible;">{{ $testimonial->name }}</span>
                                                @if($testimonial->position || $testimonial->company)
                                                    <span style="color: #6b7280; font-weight: 500; font-size: 14px; display: block; margin-top: 5px; word-wrap: break-word; overflow-wrap: break-word; line-height: 1.6; white-space: normal; overflow: visible;">
                                                        @if($testimonial->position)
                                                            {{ $testimonial->position }}
                                                        @endif
                                                        @if($testimonial->position && $testimonial->company)
                                                            <span style="color: #9ca3af;">, </span>
                                                        @endif
                                                        @if($testimonial->company)
                                                            {{ $testimonial->company }}
                                                        @endif
                                                    </span>
                                                @endif
                                            </h5>
                                        </div>
                                    </div>
                                    <div style="flex: 1; position: relative; z-index: 1; min-height: 70px; display: flex; align-items: flex-start; margin-top: 10px;">
                                        <p style="font-size: 15px; line-height: 1.95; color: #4b5563; margin: 0; font-style: italic; word-wrap: break-word; overflow-wrap: break-word; text-align: left; white-space: normal; width: 100%; hyphens: auto; overflow: visible;">"{{ $testimonial->message }}"</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-lg-12">
                                <div class="text-center" style="padding: 60px 20px;">
                                    <i class="fa fa-quote-right" style="font-size: 64px; color: #e5e7eb; margin-bottom: 20px;"></i>
                                    <p style="font-size: 18px; color: #6b7280; word-wrap: break-word; overflow-wrap: break-word;">Belum ada testimoni yang ditampilkan.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            .testimonial__slider.owl-carousel {
                position: relative;
            }
            
            .testimonial__slider .owl-stage {
                display: flex;
                align-items: stretch;
            }
            
            .testimonial__slider .owl-item {
                display: flex;
                height: auto;
            }
            
            .testimonial__item__wrapper {
                height: 100%;
                display: flex;
                padding: 0 15px;
            }
            
            .testimonial__item {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
                width: 500px !important;
                max-width: 100% !important;
                transition: all 0.3s ease !important;
            }
            
            .testimonial__item:hover {
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12) !important;
            }
            
            /* Styling untuk navigasi yang lebih jelas */
            .testimonial__slider .owl-nav {
                margin-top: 30px;
                text-align: center;
            }
            
            .testimonial__slider .owl-nav button {
                width: 45px;
                height: 45px;
                background: #dc2626 !important;
                color: #fff !important;
                border-radius: 5px;
                margin: 0 10px;
                font-size: 18px;
                border: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            
            .testimonial__slider .owl-nav button:hover {
                background: #991b1b !important;
            }
            
            .testimonial__slider .owl-nav button.disabled {
                opacity: 0.4;
                cursor: not-allowed;
            }
            
            /* Styling untuk dots indicator */
            .testimonial__slider .owl-dots {
                text-align: center;
                margin-top: 25px;
            }
            
            .testimonial__slider .owl-dots button {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: #d1d5db !important;
                margin: 0 5px;
                border: none;
            }
            
            .testimonial__slider .owl-dots button.active {
                background: #dc2626 !important;
                width: 30px;
                border-radius: 5px;
            }
            
            .testimonial__item__author__text,
            .testimonial__item__author__text * {
                word-break: break-word !important;
                overflow-wrap: break-word !important;
                hyphens: auto;
                overflow: visible !important;
            }
            
            .testimonial__item p {
                word-break: break-word !important;
                overflow-wrap: break-word !important;
                hyphens: auto;
                white-space: normal !important;
                overflow: visible !important;
            }
            
            .testimonial__item h5,
            .testimonial__item h5 * {
                word-break: break-word !important;
                overflow-wrap: break-word !important;
                white-space: normal !important;
                overflow: visible !important;
            }
            
            .testimonial__item {
                overflow: visible !important;
            }
            
            @media (min-width: 992px) {
                .testimonial__slider .owl-item {
                    width: auto !important;
                }
                
                .testimonial__item {
                    padding: 45px 40px !important;
                    min-height: 300px !important;
                    width: 500px !important;
                    max-width: 100% !important;
                }
            }
            
            @media (max-width: 991px) {
                .testimonial__slider .owl-item {
                    width: 100% !important;
                }
                
                .testimonial__item {
                    padding: 35px 30px !important;
                    min-height: 260px !important;
                    width: 100% !important;
                    max-width: 100% !important;
                }
            }
            
            @media (max-width: 768px) {
                .testimonial__item {
                    padding: 30px 25px !important;
                    min-height: 240px !important;
                }
                
                .testimonial__item__author__pic {
                    margin-right: 15px !important;
                }
                
                .testimonial__item__author__pic img,
                .testimonial__item__author__pic div {
                    width: 70px !important;
                    height: 70px !important;
                }
                
                .section-title h2 {
                    font-size: 32px !important;
                    word-wrap: break-word !important;
                    overflow-wrap: break-word !important;
                }
                
                .testimonial__item p {
                    font-size: 14px !important;
                    line-height: 1.8 !important;
                }
                
                .testimonial__item__author__text h5 {
                    font-size: 16px !important;
                }
            }
            
            @media (max-width: 576px) {
                .testimonial__item {
                    padding: 25px 20px !important;
                    min-height: 220px !important;
                }
                
                .testimonial__item__author {
                    flex-direction: column;
                    align-items: center !important;
                    text-align: center;
                }
                
                .testimonial__item__author__pic {
                    margin-right: 0 !important;
                    margin-bottom: 15px !important;
                }
                
                .testimonial__item__author__text {
                    text-align: center;
                    width: 100%;
                }
            }
        </style>
    </section>

    <!-- Counter Begin -->
    <div class="counter spad set-bg" data-setbg="img/counter-bg.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter__item">
                        <h2 class="counter-num">1922</h2>
                        <p>Vehicles Stock</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter__item">
                        <h2 class="counter-num">1500</h2>
                        <strong>+</strong>
                        <p>Vehicles Sale</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter__item">
                        <h2 class="counter-num">1922</h2>
                        <p>Dealer Reviews</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter__item">
                        <h2 class="counter-num">5100</h2>
                        <p>Happy Clients</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Counter End -->

    <!-- Clients Begin -->
    <div class="clients spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title client-title">
                        <span>Partner</span>
                        <h2>Our Clients</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="#" class="client__item">
                        <img src="img/clients/client-1.png" alt="">
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="#" class="client__item">
                        <img src="img/clients/client-2.png" alt="">
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="#" class="client__item">
                        <img src="img/clients/client-3.png" alt="">
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="#" class="client__item">
                        <img src="img/clients/client-2.png" alt="">
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="#" class="client__item">
                        <img src="img/clients/client-4.png" alt="">
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="#" class="client__item">
                        <img src="img/clients/client-5.png" alt="">
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="#" class="client__item">
                        <img src="img/clients/client-6.png" alt="">
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="#" class="client__item">
                        <img src="img/clients/client-7.png" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Clients End -->

    <!-- Script untuk Testimonial Carousel - Pola Gerakan Jelas -->
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                var $testimonialSlider = $(".testimonial__slider");
                
                // Destroy existing carousel jika ada
                if ($testimonialSlider.length && $testimonialSlider.data('owl.carousel')) {
                    $testimonialSlider.trigger('destroy.owl.carousel');
                    $testimonialSlider.removeClass('owl-carousel');
                    $testimonialSlider.find('.owl-stage-outer').remove();
                    $testimonialSlider.find('.owl-nav').remove();
                    $testimonialSlider.find('.owl-dots').remove();
                }
                
                // Hitung jumlah item
                var itemCount = $testimonialSlider.find('.testimonial__item__wrapper').length;
                
                // Inisialisasi slider dengan pola yang jelas dan sederhana
                if (itemCount > 0) {
                    // Tentukan apakah perlu loop (hanya jika item lebih banyak dari yang ditampilkan)
                    var needsLoop = itemCount > 2;
                    
                    $testimonialSlider.addClass('owl-carousel').owlCarousel({
                        loop: needsLoop,                // Loop hanya jika benar-benar perlu
                        margin: 30,
                        items: 2,
                        slideBy: 1,
                        dots: true,                     // Dots untuk indikator posisi yang jelas
                        nav: true,                      // Tombol navigasi kiri/kanan
                        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
                        smartSpeed: 600,
                        autoHeight: false,
                        autoplay: false,                // Kontrol manual oleh user
                        rtl: false,
                        rewind: true,                   // Kembali ke awal dengan jelas saat sampai akhir
                        mouseDrag: true,
                        touchDrag: true,
                        pullDrag: true,
                        center: false,
                        stagePadding: 0,
                        responsive: {
                            1200: {
                                items: 2,
                                loop: needsLoopFull
                            },
                            992: {
                                items: 2,
                                loop: needsLoopFull
                            },
                            768: {
                                items: 1,
                                loop: itemCount > 1
                            },
                            576: {
                                items: 1,
                                loop: itemCount > 1
                            },
                            0: {
                                items: 1,
                                loop: itemCount > 1
                            }
                        }
                    });
                    
                    // Nonaktifkan nav button di ujung jika tidak loop
                    if (!needsLoop) {
                        $testimonialSlider.on('changed.owl.carousel', function(event) {
                            var current = event.item.index;
                            var total = event.item.count;
                            
                            // Disable nav button di ujung
                            var $nav = $testimonialSlider.find('.owl-nav');
                            $nav.find('.owl-prev').toggleClass('disabled', current === 0);
                            $nav.find('.owl-next').toggleClass('disabled', current >= total - 2);
                        });
                        
                        // Trigger sekali untuk set initial state
                        $testimonialSlider.trigger('changed.owl.carousel');
                    }
                }
            }, 200);
        });
    </script>

    @endsection
