@extends('template.temp')

<!-- @section('title', 'Home - GARASI62') Set the title for the page -->

@section('content')
@include('components.messages-widget')
<!-- Hero Section Begin -->
<section class="hero spad set-bg" data-setbg="img/hero-bg.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="hero__text">
                    <div class="hero__text__title">
                        <span>TEMUKAN MOBIL IMPIANMU DISINI</span>
                        <h2>Porsche Cayenne S</h2>
                    </div>
                    <div class="hero__text__price">
                        <div class="car-model">Model 2019</div>
                        <h2>IDR 2.2M</h2>
                    </div>
                    <a href="#" class="primary-btn"><img src="img/wheel.png" alt=""> Test Drive</a>
                    <a href="{{ route('about') }}" class="primary-btn more-btn">Learn More</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero__tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Rental Mobil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Beli Mobil</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <div class="hero__tab__form">
                                <form action="{{ route('cars') }}" method="GET" id="rentForm">
                                    <input type="hidden" name="tipe" value="rent">
                                    <div class="select-list">
                                        <div class="select-list-item">
                                            <p>Pilih Tahun</p>
                                            <select name="tahun">
                                                <option value="" data-display="Pilih Tahun">Pilih Tahun</option>
                                                @foreach($tahunList as $tahun)
                                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="select-list-item">
                                            <p>Merk Mobil</p>
                                            <select name="brand">
                                                <option value="" data-display="Pilih Brand">Pilih Brand</option>
                                                @foreach($brands as $brand)
                                                <option value="{{ $brand }}">{{ $brand }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="select-list-item">
                                            <p>Transmisi</p>
                                            <select name="transmisi">
                                                <option value="" data-display="Pilih Transmisi">Pilih Transmisi</option>
                                                @foreach($transmisiList as $transmisi)
                                                <option value="{{ $transmisi }}">{{ $transmisi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="select-list-item">
                                            <p>Kapasitas Mesin</p>
                                            <select name="kapasitasmesin">
                                                <option value="" data-display="Pilih Kapasitas Mesin">Pilih Kapasitas Mesin</option>
                                                @foreach($kapasitasmesinList as $kapasitasmesin)
                                                <option value="{{ $kapasitasmesin }}">{{ $kapasitasmesin }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="filter-price">
                                        <p>Harga:</p>
                                        <div class="price-input-row" style="margin-bottom: 25px;">
                                            <div class="price-input-item">
                                                <label>Harga Min:</label>
                                                <input type="number" name="min_price" id="rentMinPrice" placeholder="Min" value="{{ $minPrice ?? 0 }}" min="0">
                                            </div>
                                            <div class="price-input-item">
                                                <label>Harga Max:</label>
                                                <input type="number" name="max_price" id="rentMaxPrice" placeholder="Max" value="{{ $maxPrice ?? 1000000000 }}" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="site-btn">Searching</button>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-2" role="tabpanel">
                            <div class="hero__tab__form">
                                <form action="{{ route('cars') }}" method="GET" id="buyForm">
                                    <input type="hidden" name="tipe" value="buy">
                                    <div class="select-list">
                                        <div class="select-list-item">
                                            <p>Pilih Tahun</p>
                                            <select name="tahun">
                                                <option value="" data-display="Pilih Tahun">Pilih Tahun</option>
                                                @foreach($tahunList as $tahun)
                                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="select-list-item">
                                            <p>Merk Mobil</p>
                                            <select name="brand">
                                                <option value="" data-display="Pilih Brand">Pilih Brand</option>
                                                @foreach($brands as $brand)
                                                <option value="{{ $brand }}">{{ $brand }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="select-list-item">
                                            <p>Transmisi</p>
                                            <select name="transmisi">
                                                <option value="" data-display="Pilih Transmisi">Pilih Transmisi</option>
                                                @foreach($transmisiList as $transmisi)
                                                <option value="{{ $transmisi }}">{{ $transmisi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="select-list-item">
                                            <p>Kapasitas Mesin</p>
                                            <select name="kapasitasmesin">
                                                <option value="" data-display="Pilih Kapasitas Mesin">Pilih Kapasitas Mesin</option>
                                                @foreach($kapasitasmesinList as $kapasitasmesin)
                                                <option value="{{ $kapasitasmesin }}">{{ $kapasitasmesin }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="filter-price" style="margin-bottom: 25px;">
                                        <p>Harga:</p>
                                        <div class="price-input-row">
                                            <div class="price-input-item">
                                                <label>Harga Min:</label>
                                                <input type="number" name="min_price" id="buyMinPrice" placeholder="Min" value="{{ $minPrice ?? 0 }}" min="0">
                                            </div>
                                            <div class="price-input-item">
                                                <label>Harga Max:</label>
                                                <input type="number" name="max_price" id="buyMaxPrice" placeholder="Max" value="{{ $maxPrice ?? 1000000000 }}" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="site-btn">Searching</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Hero Section End -->

<!-- Services Section Begin -->
<section class="services spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <span>Our Services</span>
                    <h2>What We Offers</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="services__item">
                    <img src="img/services/services-1.png" alt="">
                    <h5>Rental A Cars</h5>
                    <p>Consectetur adipiscing elit incididunt ut labore et dolore magna aliqua. Risus commodo viverra maecenas.</p>
                    <a href="{{ route('about') }}"><i class="fa fa-long-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="services__item">
                    <img src="img/services/services-2.png" alt="">
                    <h5>Buying A Cars</h5>
                    <p>Consectetur adipiscing elit incididunt ut labore et dolore magna aliqua. Risus commodo viverra maecenas.</p>
                    <a href="{{ route('about') }}"><i class="fa fa-long-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="services__item">
                    <img src="img/services/services-3.png" alt="">
                    <h5>Car Maintenance</h5>
                    <p>Consectetur adipiscing elit incididunt ut labore et dolore magna aliqua. Risus commodo viverra maecenas.</p>
                    <a href="{{ route('about') }}"><i class="fa fa-long-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="services__item">
                    <img src="img/services/services-4.png" alt="">
                    <h5>Support 24/7</h5>
                    <p>Consectetur adipiscing elit incididunt ut labore et dolore magna aliqua. Risus commodo viverra maecenas.</p>
                    <a href="{{ route('about') }}"><i class="fa fa-long-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Services Section End -->

<!-- Feature Section Begin -->
<section class="feature spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="feature__text">
                    <div class="section-title">
                        <span>Our Feature</span>
                        <h2>We Are a Trusted Name In Auto</h2>
                    </div>
                    <div class="feature__text__desc">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis.</p>
                    </div>
                    <div class="feature__text__btn">
                        <a href="{{ route('about') }}" class="primary-btn">About Us</a>
                        <a href="#" class="primary-btn partner-btn">Our Partners</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 offset-lg-4">
                <div class="row">
                    <div class="col-lg-6 col-md-4 col-6">
                        <div class="feature__item">
                            <div class="feature__item__icon">
                                <img src="img/feature/feature-1.png" alt="">
                            </div>
                            <h6>Engine</h6>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4 col-6">
                        <div class="feature__item">
                            <div class="feature__item__icon">
                                <img src="img/feature/feature-2.png" alt="">
                            </div>
                            <h6>Turbo</h6>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4 col-6">
                        <div class="feature__item">
                            <div class="feature__item__icon">
                                <img src="img/feature/feature-3.png" alt="">
                            </div>
                            <h6>Colling</h6>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4 col-6">
                        <div class="feature__item">
                            <div class="feature__item__icon">
                                <img src="img/feature/feature-4.png" alt="">
                            </div>
                            <h6>Suspension</h6>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4 col-6">
                        <div class="feature__item">
                            <div class="feature__item__icon">
                                <img src="img/feature/feature-5.png" alt="">
                            </div>
                            <h6>Electrical</h6>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4 col-6">
                        <div class="feature__item">
                            <div class="feature__item__icon">
                                <img src="img/feature/feature-6.png" alt="">
                            </div>
                            <h6>Brakes</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Feature Section End -->

<!-- Car Section Begin -->
<section class="car spad" style="background: #fafbfc; padding: 80px 0;">
    <div class="container">
        <!-- Header Section -->
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="text-center mb-4">
                    <span style="display: inline-block; font-size: 14px; font-weight: 600; color: #df2d24; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px;">Koleksi Mobil terbaru kami</span>
                    <h2 style="font-size: 42px; font-weight: 800; color: #1a1a1a; margin: 0; letter-spacing: -0.5px;">Penawaran Terbaik</h2>
                    <p style="font-size: 16px; color: #6b7280; margin-top: 12px; max-width: 600px; margin-left: auto; margin-right: auto;">Temukan mobil impian Anda dari koleksi terpilih kami</p>
                </div>
            </div>
        </div>

        <!-- Car Grid -->
        <div class="row car-filter">
            @forelse($cars as $car)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4 mix{{ $car->tipe == 'buy' ? ' sale' : '' }}" style="transition: opacity 0.3s ease, transform 0.3s ease, display 0.3s ease;">
                <div class="car-card-modern" style="background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); transition: all 0.3s; height: 100%; display: flex; flex-direction: column;">
                    <!-- Car Image -->
                    <div class="car-image-wrapper" style="position: relative; width: 100%; height: 220px; min-height: 220px; max-height: 220px; overflow: hidden; background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);">
                        <div class="car__item__pic__slider owl-carousel" style="height: 220px; min-height: 220px; max-height: 220px; width: 100%;">
                            @if($car->image && is_array($car->image) && count($car->image) > 0)
                                @foreach($car->image as $imagePath)
                                    <div style="width: 100%; height: 220px; min-height: 220px; max-height: 220px; overflow: hidden;">
                                        <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $car->brand }} {{ $car->nama ?? '' }}" style="width: 100%; height: 220px; min-height: 220px; max-height: 220px; object-fit: cover; display: block;">
                                    </div>
                                @endforeach
                            @else
                                <div style="width: 100%; height: 220px; min-height: 220px; max-height: 220px; overflow: hidden;">
                                    <img src="{{ asset('garasi62/img/cars/car-8.jpg') }}" alt="{{ $car->brand }} {{ $car->nama ?? '' }}" style="width: 100%; height: 220px; min-height: 220px; max-height: 220px; object-fit: cover; display: block;">
                                </div>
                            @endif
                        </div>
                        <!-- Badge -->
                        <div style="position: absolute; top: 12px; left: 12px; background: {{ $car->tipe == 'buy' ? 'linear-gradient(135deg, #10b981, #34d399)' : 'linear-gradient(135deg, #3b82f6, #2563eb)' }}; color: #fff; padding: 6px 14px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                            {{ $car->tipe == 'rent' ? 'Sewa' : 'Jual' }}
                        </div>
                        <!-- Year Badge -->
                        @if($car->tahun)
                        <div style="position: absolute; top: 12px; right: 12px; background: rgba(0,0,0,0.7); color: #fff; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; backdrop-filter: blur(10px);">
                            {{ $car->tahun }}
                        </div>
                        @endif
                    </div>

                    <!-- Car Info -->
                    <div class="car-info-content" style="padding: 20px; flex: 1; display: flex; flex-direction: column;">
                        <!-- Title -->
                        <h5 style="margin: 0 0 12px 0; font-size: 18px; font-weight: 700; color: #1a1a1a; line-height: 1.4;">
                            @auth
                                <a href="{{ route('car.details', $car->id) }}" style="color: #1a1a1a; text-decoration: none; transition: color 0.3s;">{{ $car->brand ?? '' }}@if($car->nama) {{ $car->nama }}@endif</a>
                            @else
                                <a href="{{ route('login') }}" onclick="event.preventDefault(); if (typeof Swal !== 'undefined') { Swal.fire({icon: 'info', title: 'Login Diperlukan', text: 'Silakan login terlebih dahulu untuk melihat detail mobil', confirmButtonText: 'Login', confirmButtonColor: '#df2d24'}).then((result) => { if (result.isConfirmed) window.location.href='{{ route('login') }}'; }); } else { window.location.href='{{ route('login') }}'; } return false;" style="color: #1a1a1a; text-decoration: none; transition: color 0.3s;">{{ $car->brand ?? '' }}@if($car->nama) {{ $car->nama }}@endif</a>
                            @endauth
                        </h5>

                        <!-- Specs -->
                        <div class="car-specs" style="display: flex; gap: 16px; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #f3f4f6; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280;">
                                <i class="fa fa-tachometer" style="color: #df2d24; font-size: 14px;"></i>
                                <span><strong style="color: #1a1a1a;">{{ number_format($car->kilometer ?? 0, 0, ',', '.') }}</strong> km</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280;">
                                <i class="fa fa-cog" style="color: #df2d24; font-size: 14px;"></i>
                                <span style="color: #1a1a1a;">{{ $car->transmisi ?? '-' }}</span>
                            </div>
                            @if($car->kapasitasmesin)
                            <div style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280;">
                                <i class="fa fa-car" style="color: #df2d24; font-size: 14px;"></i>
                                <span style="color: #1a1a1a;">{{ $car->kapasitasmesin }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Price -->
                        <div class="car-price" style="margin-top: auto;">
                            <div style="font-size: 24px; font-weight: 800; color: #df2d24; line-height: 1.2;">
                                Rp {{ number_format($car->harga ?? 0, 0, ',', '.') }}
                                @if($car->tipe == 'rent' && $car->metode)
                                <span style="font-size: 14px; font-weight: 600; color: #6b7280;">/{{ $car->metode }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center" style="padding: 80px 20px; background: #fff; border-radius: 12px;">
                    <div style="font-size: 64px; color: #e5e7eb; margin-bottom: 20px;">
                        <i class="fa fa-car"></i>
                    </div>
                    <h4 style="font-size: 24px; font-weight: 700; color: #1a1a1a; margin-bottom: 12px;">Tidak ada mobil tersedia</h4>
                    <p style="font-size: 16px; color: #6b7280; margin: 0;">Silakan tambahkan mobil melalui admin panel.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<style>
/* Fixed Image Size */
.car-image-wrapper {
    height: 220px !important;
    min-height: 220px !important;
    max-height: 220px !important;
}

.car__item__pic__slider {
    height: 220px !important;
    min-height: 220px !important;
    max-height: 220px !important;
}

.car__item__pic__slider .owl-item,
.car__item__pic__slider .owl-item > div {
    height: 220px !important;
    min-height: 220px !important;
    max-height: 220px !important;
}

.car__item__pic__slider img {
    width: 100% !important;
    height: 220px !important;
    min-height: 220px !important;
    max-height: 220px !important;
    object-fit: cover !important;
    object-position: center !important;
}

/* Car Card Hover Effect */
.car-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12) !important;
}

.car-card-modern:hover h5 a {
    color: #df2d24;
}

/* Filter Button Active State */
.filter-btn.active {
    background: #df2d24 !important;
    color: #fff !important;
}

.filter-btn:not(.active):hover {
    background: #f3f4f6 !important;
    color: #1a1a1a !important;
}

/* Filter Items Transition */
.car-filter .mix {
    transition: opacity 0.3s ease, transform 0.3s ease;
    will-change: opacity, transform;
}

/* Responsive */
@media (max-width: 768px) {
    .car-card-modern {
        margin-bottom: 24px;
    }
    
    .filter-controls-modern {
        flex-direction: column;
        width: 100%;
        max-width: 300px;
    }
    
    .filter-btn {
        width: 100%;
    }
    
    .car-image-wrapper,
    .car__item__pic__slider,
    .car__item__pic__slider .owl-item,
    .car__item__pic__slider .owl-item > div,
    .car__item__pic__slider img {
        height: 200px !important;
        min-height: 200px !important;
        max-height: 200px !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    const filterItems = document.querySelectorAll('.mix');
    const filterContainer = document.querySelector('.car-filter');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
                if (!btn.classList.contains('active')) {
                    btn.style.background = 'transparent';
                    btn.style.color = '#6b7280';
                }
            });
            
            // Add active class to clicked button
            this.classList.add('active');
            this.style.background = '#df2d24';
            this.style.color = '#fff';
            
            const filterValue = this.getAttribute('data-filter');
            
            // Filter items
            filterItems.forEach(item => {
                const shouldShow = filterValue === '*' || item.classList.contains(filterValue.replace('.', ''));
                
                if (shouldShow) {
                    item.style.display = 'block';
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.95)';
                    
                    // Trigger reflow
                    item.offsetHeight;
                    
                    // Animate in
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    // Animate out then hide
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.95)';
                    
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
});
</script>
<!-- Car Section End -->

<!-- Chooseus Section Begin -->
<section class="chooseus spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="chooseus__text">
                    <div class="section-title">
                        <h2>Why People Choose Us</h2>
                        <p>Duis aute irure dolorin reprehenderits volupta velit dolore fugiat nulla pariatur excepteur sint occaecat cupidatat.</p>
                    </div>
                    <ul>
                        <li><i class="fa fa-check-circle"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                        <li><i class="fa fa-check-circle"></i> Integer et nisl et massa tempor ornare vel id orci.</li>
                        <li><i class="fa fa-check-circle"></i> Nunc consectetur ligula vitae nisl placerat tempus.</li>
                        <li><i class="fa fa-check-circle"></i> Curabitur quis ante vitae lacus varius pretium.</li>
                    </ul>
                    <a href="{{ route('about') }}" class="primary-btn">About Us</a>
                </div>
            </div>
        </div>
    </div>
    <div class="chooseus__video set-bg">
        <img src="img/chooseus-video.png" alt="">
        <a href="https://www.youtube.com/watch?v=Xd0Ok-MkqoE" class="play-btn video-popup"><i class="fa fa-play"></i></a>
    </div>
</section>
<!-- Chooseus Section End -->

<!-- Latest Blog Section Begin -->
<section class="latest spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <span>Our Blog</span>
                    <h2>Latest News Updates</h2>
                    <p>Sign up for the latest Automobile Industry information and more. Duis aute<br /> irure dolorin reprehenderits volupta velit dolore fugiat.</p>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse($blogs as $blog)
            <div class="col-lg-4 col-md-6">
                <div class="latest__blog__item">
                    <div class="latest__blog__item__pic set-bg" 
                         @if($blog->image)
                         data-setbg="{{ asset('storage/' . $blog->image) }}"
                         style="background-image: url('{{ asset('storage/' . $blog->image) }}');"
                         @else
                         data-setbg="img/latest-blog/lb-1.jpg"
                         style="background-image: url('{{ asset('img/latest-blog/lb-1.jpg') }}');"
                         @endif>
                        <ul>
                            <li>By {{ $blog->author }}</li>
                            <li>{{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</li>
                            <li>{{ $blog->comment_count ?? 0 }} Comment{{ ($blog->comment_count ?? 0) != 1 ? 's' : '' }}</li>
                        </ul>
                    </div>
                    <div class="latest__blog__item__text">
                        <h5>{{ $blog->title }}</h5>
                        <p>{{ $blog->excerpt ? Str::limit($blog->excerpt, 150) : Str::limit(strip_tags($blog->content), 150) }}</p>
                        <a href="{{ route('blog.show', $blog->slug) }}">View More <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-lg-12">
                <div class="text-center" style="padding: 60px 20px;">
                    <p style="color: #6b7280; font-size: 16px;">Belum ada blog yang dipublikasikan.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>
<!-- Latest Blog Section End -->

<!-- Cta Begin -->
<div class="cta">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="cta__item set-bg" data-setbg="img/cta/cta-1.jpg">
                    <h4>Do You Want To Buy A Car</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="cta__item set-bg" data-setbg="img/cta/cta-2.jpg">
                    <h4>Do You Want To Rent A Car</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cta End -->

<style>
    .price-range-preview {
        margin-bottom: 15px;
        padding: 8px 0;
    }

    .price-range-preview small {
        display: block;
        color: #666;
        font-size: 12px;
        font-style: italic;
    }

    .price-input-row {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }

    .price-input-item {
        flex: 1;
    }

    .price-input-item label {
        display: block;
        margin-bottom: 5px;
        font-size: 13px;
        font-weight: 500;
        color: #19191a;
    }

    .price-input-item input[type="number"] {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e8e8e8;
        border-radius: 5px;
        font-size: 14px;
        color: #19191a;
        transition: border-color 0.3s ease;
    }

    .price-input-item input[type="number"]:focus {
        outline: none;
        border-color: #df2d24;
    }

    .price-input-item input[type="number"]::placeholder {
        color: #999;
    }

    @media (max-width: 575px) {
        .price-input-row {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>

<script>
    (function() {
        function initNiceSelect() {
            // Inisialisasi ulang nice-select untuk memastikan placeholder muncul
            if (typeof $ !== 'undefined' && typeof $.fn.niceSelect !== 'undefined') {
                $('#rentForm select, #buyForm select').niceSelect('update');
            } else {
                // Jika belum ter-load, coba lagi setelah 100ms (max 10 kali)
                if (typeof initNiceSelect.attemptCount === 'undefined') {
                    initNiceSelect.attemptCount = 0;
                }
                initNiceSelect.attemptCount++;
                if (initNiceSelect.attemptCount < 10) {
                    setTimeout(initNiceSelect, 100);
                }
            }
        }

        // Tunggu hingga semua script di template ter-load
        if (window.addEventListener) {
            window.addEventListener('load', function() {
                setTimeout(initNiceSelect, 300);
            });
        } else if (window.attachEvent) {
            window.attachEvent('onload', function() {
                setTimeout(initNiceSelect, 300);
            });
        } else {
            // Fallback jika event listener tidak tersedia
            setTimeout(initNiceSelect, 1000);
        }
    })();
</script>
@endsection