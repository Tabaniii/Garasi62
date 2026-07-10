@extends('template.temp')

@section('meta')
    <meta name="description" content="Tentang Garasi62 - Kami menyediakan segala kebutuhan mobil Anda. Jual beli dan sewa mobil terpercaya.">
    <meta name="keywords" content="Tentang Garasi62, Profil Garasi62, Dealer Mobil">

    <!-- Open Graph -->
    <meta property="og:title" content="Tentang Kami - Garasi62">
    <meta property="og:description" content="Tentang Garasi62 - Kami menyediakan segala kebutuhan mobil Anda. Jual beli dan sewa mobil terpercaya.">
    <meta property="og:image" content="{{ asset('garasi62/img/about/about-pic.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="GARASI62">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Tentang Kami - Garasi62">
    <meta name="twitter:description" content="Tentang Garasi62 - Kami menyediakan segala kebutuhan mobil Anda.">
    <meta name="twitter:image" content="{{ asset('garasi62/img/about/about-pic.jpg') }}">
@endsection


@include('components.messages-widget')
@section('content')
    @php
        $aboutTitle = \App\Models\SiteSetting::get('about_title', 'Selamat Datang di Garasi62');
        $aboutSubtitle = \App\Models\SiteSetting::get('about_subtitle', 'Kami Menyediakan Segala yang Anda Butuhkan untuk Mobil');
        $aboutDescription = \App\Models\SiteSetting::get('about_description', 'Garasi62 adalah platform terpercaya untuk jual beli dan sewa mobil. Kami berkomitmen memberikan layanan terbaik bagi pelanggan kami.');
        $aboutFeature1Title = \App\Models\SiteSetting::get('about_feature_1_title', 'Sistem Jaminan Kualitas');
        $aboutFeature1Text = \App\Models\SiteSetting::get('about_feature_1_text', 'Kami memastikan setiap kendaraan yang kami tawarkan telah melalui inspeksi ketat.');
        $aboutFeature1Icon = \App\Models\SiteSetting::get('about_feature_1_icon', 'img/about/af-1.png');
        $aboutFeature2Title = \App\Models\SiteSetting::get('about_feature_2_title', 'Proses Pengujian Akurat');
        $aboutFeature2Text = \App\Models\SiteSetting::get('about_feature_2_text', 'Setiap detail kendaraan diperiksa untuk memastikan performa maksimal.');
        $aboutFeature2Icon = \App\Models\SiteSetting::get('about_feature_2_icon', 'img/about/af-2.png');
        $aboutFeature3Title = \App\Models\SiteSetting::get('about_feature_3_title', 'Teknologi Integrasi Infrastruktur');
        $aboutFeature3Text = \App\Models\SiteSetting::get('about_feature_3_text', 'Kami menggunakan sistem modern untuk mengelola inventaris dan layanan kami.');
        $aboutFeature3Icon = \App\Models\SiteSetting::get('about_feature_3_icon', 'img/about/af-3.png');
        $aboutImage = \App\Models\SiteSetting::get('about_image', 'img/about/about-pic.jpg');
        $aboutMissionTitle = \App\Models\SiteSetting::get('about_mission_title', 'Misi Kami');
        $aboutMissionText = \App\Models\SiteSetting::get('about_mission_text', 'Memberikan pengalaman jual beli dan sewa mobil yang transparan, aman, dan mudah bagi seluruh lapisan masyarakat.');
        $aboutVisionTitle = \App\Models\SiteSetting::get('about_vision_title', 'Visi Kami');
        $aboutVisionText = \App\Models\SiteSetting::get('about_vision_text', 'Menjadi platform otomotif nomor satu di Indonesia yang dikenal karena kepercayaan dan kualitas layanannya.');
    @endphp

    <!-- Breadcrumb End -->
    <div class="breadcrumb-option set-bg" data-setbg="img/breadcrumb-bg.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Tentang Kami</h2>
                        <div class="breadcrumb__links">
                            <a href="/"><i class="fa fa-home"></i> Beranda</a>
                            <span>Tentang Kami</span>
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
                        <h2>{{ $aboutTitle }} <br />{{ $aboutSubtitle }}</h2>
                        <p>{{ $aboutDescription }}</p>
                    </div>
                </div>
            </div>
            <div class="about__feature">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="about__feature__item">
                            <img src="{{ asset($aboutFeature1Icon) }}" alt="">
                            <h5>{{ $aboutFeature1Title }}</h5>
                            <p>{{ $aboutFeature1Text }}</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="about__feature__item">
                            <img src="{{ asset($aboutFeature2Icon) }}" alt="">
                            <h5>{{ $aboutFeature2Title }}</h5>
                            <p>{{ $aboutFeature2Text }}</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="about__feature__item">
                            <img src="{{ asset($aboutFeature3Icon) }}" alt="">
                            <h5>{{ $aboutFeature3Title }}</h5>
                            <p>{{ $aboutFeature3Text }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="about__pic">
                        <img src="{{ asset($aboutImage) }}" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="about__item">
                        <h5>{{ $aboutMissionTitle }}</h5>
                        <p>{{ $aboutMissionText }}</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="about__item">
                        <h5>{{ $aboutVisionTitle }}</h5>
                        <p>{{ $aboutVisionText }}</p>
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
                            <h2>Minta Panggilan Balik</h2>
                            <p>Tinggalkan kontak Anda, tim kami akan segera menghubungi Anda untuk informasi lebih lanjut.</p>
                        </div>
                        <a href="{{ route('contact') }}">Hubungi Kami</a>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-1 col-md-6">
                    <form action="#" class="call__form">
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="text" placeholder="Nama">
                            </div>
                            <div class="col-lg-6">
                                <input type="text" placeholder="Email">
                            </div>
                            <div class="col-lg-6">
                                <input type="text" placeholder="Telepon">
                            </div>
                            <div class="col-lg-6">
                                <select>
                                    <option value="">Pilih Layanan Kami</option>
                                    <option value="">Beli Mobil</option>
                                    <option value="">Jual Mobil</option>
                                    <option value="">Cuci Mobil</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="site-btn">Kirim Sekarang</button>
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
                        <span>Tim Kami</span>
                        <h2>Temui Ahli Kami</h2>
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
                        <span style="color: #dc2626; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; font-size: 14px; display: inline-block; margin-bottom: 10px;">Testimoni</span>
                        <h2 style="font-size: 42px; font-weight: 800; margin: 15px 0 20px; color: #1a1a1a; position: relative; word-wrap: break-word; overflow-wrap: break-word;">
                            Apa Kata Mereka Tentang Kami
                            <span style="position: absolute; bottom: -10px; left: 50%; width: 80px; height: 4px; background: linear-gradient(90deg, #dc2626, #991b1b); border-radius: 5px;"></span>
                        </h2>
                        <p style="font-size: 16px; color: #6b7280; max-width: 600px; margin: 0 auto; word-wrap: break-word; overflow-wrap: break-word;">Pelanggan kami adalah pendukung terbesar kami. Apa pendapat mereka tentang kami?</p>
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
                        <p>Stok Kendaraan</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter__item">
                        <h2 class="counter-num">1500</h2>
                        <strong>+</strong>
                        <p>Kendaraan Terjual</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter__item">
                        <h2 class="counter-num">1922</h2>
                        <p>Ulasan Dealer</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter__item">
                        <h2 class="counter-num">5100</h2>
                        <p>Pelanggan Puas</p>
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
                        <span>Mitra Kami</span>
                        <h2>Klien Kami</h2>
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
