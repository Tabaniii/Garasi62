@extends('template.temp')

@section('content')
<!-- Breadcrumb End -->
<div class="breadcrumb-option set-bg" data-setbg="img/breadcrumb-bg.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Car Listing</h2>
                    <div class="breadcrumb__links">
                        <a href="./index.html"><i class="fa fa-home"></i> Home</a>
                        <span>About</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Begin -->

<!-- Car Section Begin -->
<section class="car spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="car__sidebar">
                    <div class="car__search">
                        <h5>Car Search</h5>
                        <form action="{{ route('cars') }}" method="GET" id="searchForm">
                            <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                    <div class="car__filter">
                        <h5>Car Filter</h5>
                        <form action="{{ route('cars') }}" method="GET" id="filterForm">
                            <input type="hidden" name="search" value="{{ request('search') }}">

                            <select name="brand" onchange="document.getElementById('filterForm').submit();">
                                <option value="" data-display="Brand">Select Brand</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                                @endforeach
                            </select>

                            <select name="tahun" onchange="document.getElementById('filterForm').submit();">
                                <option value="" data-display="Tahun">Select Tahun</option>
                                @foreach($tahunList as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                @endforeach
                            </select>

                            <select name="tipe" onchange="document.getElementById('filterForm').submit();">
                                <option value="" data-display="Tipe">Select Tipe</option>
                                <option value="rent" {{ request('tipe') == 'rent' ? 'selected' : '' }}>For Rent</option>
                                <option value="buy" {{ request('tipe') == 'buy' ? 'selected' : '' }}>For Sale</option>
                            </select>

                            <select name="transmisi" onchange="document.getElementById('filterForm').submit();">
                                <option value="" data-display="Transmisi">Select Transmisi</option>
                                @foreach($transmisiList as $transmisi)
                                <option value="{{ $transmisi }}" {{ request('transmisi') == $transmisi ? 'selected' : '' }}>{{ $transmisi }}</option>
                                @endforeach
                            </select>

                            <select name="metode" onchange="document.getElementById('filterForm').submit();">
                                <option value="" data-display="Metode">Select Metode</option>
                                @foreach($metodeList as $metode)
                                <option value="{{ $metode }}" {{ request('metode') == $metode ? 'selected' : '' }}>{{ $metode }}</option>
                                @endforeach
                            </select>

                            <select name="kapasitasmesin" onchange="document.getElementById('filterForm').submit();">
                                <option value="" data-display="Kapasitas Mesin">Select Kapasitas Mesin</option>
                                @foreach($kapasitasmesinList as $kapasitasmesin)
                                <option value="{{ $kapasitasmesin }}" {{ request('kapasitasmesin') == $kapasitasmesin ? 'selected' : '' }}>{{ $kapasitasmesin }}</option>
                                @endforeach
                            </select>

                            <div class="filter-price">
                                <p>Price:</p>
                                <div class="price-range-wrap">
                                    <div class="filter-price-range" id="priceRange"></div>
                                    <div class="range-slider">
                                        <div class="price-input">
                                            <input type="text" id="filterAmount" readonly>
                                            <input type="hidden" name="min_price" id="minPrice" value="{{ request('min_price', $minPrice ?? 0) }}">
                                            <input type="hidden" name="max_price" id="maxPrice" value="{{ request('max_price', $maxPrice ?? 0) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="car__filter__btn">
                                <a href="{{ route('cars') }}" class="site-btn">Reset Filter</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="car__filter__option">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="car__filter__option__item">
                                <h6>Show On Page</h6>
                                <form action="{{ route('cars') }}" method="GET" id="perPageForm" style="display: inline;">
                                    @foreach(request()->except('per_page', 'page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <select name="per_page" onchange="document.getElementById('perPageForm').submit();">
                                        <option value="9" {{ request('per_page', 9) == 9 ? 'selected' : '' }}>9 Car</option>
                                        <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15 Car</option>
                                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 Car</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="car__filter__option__item car__filter__option__item--right">
                                <h6>Sort By</h6>
                                <form action="{{ route('cars') }}" method="GET" id="sortForm" style="display: inline;">
                                    @foreach(request()->except('sort_by', 'sort_order', 'page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <select name="sort_option" onchange="handleSortChange(this.value);">
                                        <option value="created_at_desc" {{ request('sort_by', 'created_at') == 'created_at' && request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="harga_desc" {{ request('sort_by') == 'harga' && request('sort_order') == 'desc' ? 'selected' : '' }}>Price: Highest First</option>
                                        <option value="harga_asc" {{ request('sort_by') == 'harga' && request('sort_order') == 'asc' ? 'selected' : '' }}>Price: Lowest First</option>
                                    </select>
                                    <input type="hidden" name="sort_by" id="sortBy" value="{{ request('sort_by', 'created_at') }}">
                                    <input type="hidden" name="sort_order" id="sortOrder" value="{{ request('sort_order', 'desc') }}">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @forelse($cars as $index => $car)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="car-card-modern animate-fade-in-up" data-animation-delay="{{ $index * 0.1 }}">
                            <!-- Image Section with Overlay -->
                            <div class="car-card-image-wrapper">
                                <div class="car-card-image-slider">
                                    @if($car->image && is_array($car->image) && count($car->image) > 0)
                                        <img src="{{ asset('storage/' . $car->image[0]) }}" alt="{{ $car->brand }} {{ $car->nama ?? '' }}" class="car-card-main-image">
                                        @if(count($car->image) > 1)
                                            <div class="image-count-badge">
                                                <i class="fa fa-images"></i> {{ count($car->image) }}
                                            </div>
                                        @endif
                                    @else
                                        <img src="{{ asset('garasi62/img/cars/car-8.jpg') }}" alt="{{ $car->brand }} {{ $car->nama ?? '' }}" class="car-card-main-image">
                                    @endif
                                </div>
                                <!-- Overlay Badges -->
                                <div class="car-card-overlay">
                                    <span class="car-type-badge {{ $car->tipe == 'buy' ? 'badge-sale' : 'badge-rent' }}">
                                        {{ $car->tipe == 'rent' ? 'For Rent' : 'For Sale' }}
                                    </span>
                                    @if($car->stock)
                                    <span class="stock-badge {{ strtolower($car->stock) === 'tersedia' ? 'stock-available' : 'stock-limited' }}">
                                        {{ $car->stock }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="car-card-content">
                                <!-- Header -->
                                <div class="car-card-header">
                                    <h5 class="car-card-title">
                                        <a href="{{ route('car.details', $car->id) }}">
                                            {{ $car->brand }}@if($car->nama) {{ $car->nama }}@endif
                                        </a>
                                    </h5>
                                    <div class="car-year-badge">{{ $car->tahun }}</div>
                                </div>

                                <!-- Location -->
                                @if($car->location)
                                <div class="car-card-location">
                                    <i class="fa fa-map-marker"></i>
                                    <span>{{ $car->location }}</span>
                                </div>
                                @endif

                                <!-- Specifications -->
                                <div class="car-card-specs">
                                    <div class="spec-item-modern">
                                        <div class="spec-icon">
                                            <i class="fa fa-tachometer"></i>
                                        </div>
                                        <div class="spec-content">
                                            <span class="spec-label">Kilometer</span>
                                            <span class="spec-value">{{ number_format($car->kilometer ?? 0, 0, ',', '.') }} km</span>
                                        </div>
                                    </div>
                                    <div class="spec-item-modern">
                                        <div class="spec-icon">
                                            <i class="fa fa-cog"></i>
                                        </div>
                                        <div class="spec-content">
                                            <span class="spec-label">Transmisi</span>
                                            <span class="spec-value">{{ $car->transmisi ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="spec-item-modern">
                                        <div class="spec-icon">
                                            <i class="fa fa-car"></i>
                                        </div>
                                        <div class="spec-content">
                                            <span class="spec-label">Mesin</span>
                                            <span class="spec-value">{{ $car->kapasitasmesin ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="car-card-price">
                                    <div class="price-label">Harga</div>
                                    <div class="price-value">
                                        Rp {{ number_format($car->harga ?? 0, 0, ',', '.') }}
                                        @if($car->tipe == 'rent' && $car->metode)
                                            <span class="price-period">/{{ $car->metode }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="car-card-actions">
                                    <a href="{{ route('car.details', $car->id) }}" class="btn-detail">
                                        <i class="fa fa-eye"></i>
                                        <span>Lihat Detail</span>
                                    </a>
                                    @auth
                                        @if(auth()->user()->role === 'buyer')
                                            @php
                                                $seller = $car->seller;
                                            @endphp
                                            @if($seller)
                                                <a href="{{ route('chat.seller', $seller->id) }}?car_id={{ $car->id }}" class="btn-chat">
                                                    <i class="fa fa-comments"></i>
                                                    <span>Chat ke Penjual</span>
                                                </a>
                                            @endif
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn-chat">
                                            <i class="fa fa-comments"></i>
                                            <span>Login untuk Chat</span>
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center" style="padding: 40px;">
                            <i class="fa fa-info-circle fa-3x mb-3"></i>
                            <h4>Tidak ada mobil ditemukan</h4>
                            <p>Silakan coba filter atau pencarian yang berbeda.</p>
                            <a href="{{ route('cars') }}" class="btn btn-primary mt-3">Reset Filter</a>
                        </div>
                    </div>
                    @endforelse
                </div>
                @if($cars->hasPages())
                <div class="pagination__option">
                    @if($cars->onFirstPage())
                    <span class="disabled"><span class="arrow_carrot-2left"></span></span>
                    @else
                    <a href="{{ $cars->previousPageUrl() }}"><span class="arrow_carrot-2left"></span></a>
                    @endif

                    @php
                    $currentPage = $cars->currentPage();
                    $lastPage = $cars->lastPage();
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($lastPage, $currentPage + 2);

                    if ($startPage > 1) {
                    $startPage = 1;
                    }
                    if ($endPage < $lastPage && ($endPage - $startPage) < 4) {
                        $endPage=min($lastPage, $startPage + 4);
                        }
                        @endphp

                        @if($startPage> 1)
                        <a href="{{ $cars->url(1) }}">1</a>
                        @if($startPage > 2)
                        <span>...</span>
                        @endif
                        @endif

                        @for($page = $startPage; $page <= $endPage; $page++)
                            @if($page==$currentPage)
                            <a href="#" class="active">{{ $page }}</a>
                            @else
                            <a href="{{ $cars->url($page) }}">{{ $page }}</a>
                            @endif
                            @endfor

                            @if($endPage < $lastPage)
                                @if($endPage < $lastPage - 1)
                                <span>...</span>
                                @endif
                                <a href="{{ $cars->url($lastPage) }}">{{ $lastPage }}</a>
                                @endif

                                @if($cars->hasMorePages())
                                <a href="{{ $cars->nextPageUrl() }}"><span class="arrow_carrot-2right"></span></a>
                                @else
                                <span class="disabled"><span class="arrow_carrot-2right"></span></span>
                                @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
<!-- Car Section End -->

<style>
    /* ============================================
   PRICE RANGE PREVIEW
   ============================================ */
    .price-range-preview {
        margin-bottom: 10px;
        padding: 8px 0;
    }

    .price-range-preview small {
        display: block;
        color: #666;
        font-size: 12px;
        font-style: italic;
    }

    /* ============================================
   MODERN CARD DESIGN
   ============================================ */
    .car-card-modern {
        background: #ffffff;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0, 0, 0, 0.05);
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .car-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 5px;
        padding: 2px;
        background: linear-gradient(135deg, #df2d24, #ff6b6b, #df2d24);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.4s;
        z-index: 1;
        pointer-events: none;
    }

    .car-card-modern:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 60px rgba(223, 45, 36, 0.25);
        border-color: transparent;
    }

    .car-card-modern:hover::before {
        opacity: 1;
    }

    /* Image Wrapper */
    .car-card-image-wrapper {
        position: relative;
        width: 100%;
        height: 220px;
        overflow: hidden;
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    }

    .car-card-image-slider {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .car-card-main-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .car-card-modern:hover .car-card-main-image {
        transform: scale(1.1);
    }

    .image-count-badge {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(10px);
        color: #fff;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        z-index: 2;
    }

    .image-count-badge i {
        font-size: 12px;
    }

    /* Overlay Badges */
    .car-card-overlay {
        position: absolute;
        top: 12px;
        left: 12px;
        right: 12px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        z-index: 2;
    }

    .car-type-badge {
        padding: 6px 14px;
        border-radius: 5px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
    }

    .badge-rent {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
        color: #fff;
    }

    .badge-sale {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: #fff;
    }

    .stock-badge {
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 10px;
        font-weight: 700;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .stock-available {
        background: rgba(46, 125, 50, 0.9);
        color: #fff;
    }

    .stock-limited {
        background: rgba(230, 81, 0, 0.9);
        color: #fff;
    }

    /* Card Content */
    .car-card-content {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        background: linear-gradient(to bottom, #ffffff, #fafbfc);
    }

    /* Header */
    .car-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
        gap: 10px;
    }

    .car-card-title {
        flex: 1;
        margin: 0;
        font-size: 20px;
        font-weight: 800;
        line-height: 1.3;
    }

    .car-card-title a {
        color: #1a1a1a;
        text-decoration: none;
        transition: color 0.3s;
        background: linear-gradient(135deg, #1a1a1a, #4a4a4a);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .car-card-title a:hover {
        background: linear-gradient(135deg, #df2d24, #ff6b6b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .car-year-badge {
        padding: 6px 12px;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        color: #1a1a1a;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #e0e0e0;
        white-space: nowrap;
    }

    /* Location */
    .car-card-location {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 16px;
        font-size: 13px;
        color: #6b7280;
    }

    .car-card-location i {
        color: #df2d24;
        font-size: 14px;
    }

    /* Specifications */
    .car-card-specs {
        margin-bottom: 16px;
        padding: 12px;
        background: linear-gradient(135deg, #fafbfc, #ffffff);
        border-radius: 5px;
        border: 1px solid #f0f0f0;
    }

    .spec-item-modern {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 0;
    }

    .spec-item-modern:not(:last-child) {
        border-bottom: 1px solid #f0f0f0;
    }

    .spec-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #fff, #f8f9fa);
        border-radius: 5px;
        color: #df2d24;
        font-size: 14px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        flex-shrink: 0;
    }

    .spec-content {
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .spec-label {
        font-size: 11px;
        color: #9ca3af;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .spec-value {
        font-size: 13px;
        color: #1a1a1a;
        font-weight: 700;
    }

    /* Price */
    .car-card-price {
        margin-top: auto;
        padding: 16px;
        background: linear-gradient(135deg, #fff, #fafbfc);
        border-radius: 5px;
        border: 2px solid #f0f0f0;
        margin-bottom: 16px;
        text-align: center;
    }

    .price-label {
        font-size: 11px;
        color: #9ca3af;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 6px;
    }

    .price-value {
        font-size: 24px;
        font-weight: 900;
        background: linear-gradient(135deg, #df2d24, #ff6b6b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.2;
    }

    .price-period {
        font-size: 14px;
        font-weight: 500;
        color: #9ca3af;
        -webkit-text-fill-color: #9ca3af;
    }

    /* Action Buttons */
    .car-card-actions {
        display: flex;
        gap: 10px;
    }

    .btn-detail,
    .btn-chat {
        flex: 1;
    }

    .btn-detail,
    .btn-chat {
        padding: 12px 16px;
        border-radius: 5px;
        font-size: 13px;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .btn-detail {
        background: linear-gradient(135deg, #1a1a1a, #4a4a4a);
        color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        background: linear-gradient(135deg, #2a2a2a, #5a5a5a);
    }

    .btn-chat {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: #fff;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-chat:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
    }

    .btn-detail i,
    .btn-chat i {
        font-size: 14px;
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

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .animate-fade-in-up[data-animation-delay] {
        animation-delay: calc(var(--delay, 0) * 1s);
    }

    /* Responsive */
    @media (max-width: 991px) {
        .car-card-modern {
            margin-bottom: 30px;
        }
    }

    @media (max-width: 767px) {
        .car-card-image-wrapper {
            height: 200px;
        }

        .car-card-title {
            font-size: 18px;
        }

        .car-card-actions {
            flex-direction: column;
        }

        .btn-detail,
        .btn-chat {
            width: 100%;
        }
    }

    /* ============================================
   CARD IMAGE SECTION
   ============================================ */
    .car__item__pic--fixed {
        width: 100%;
        height: 180px;
        overflow: hidden;
        position: relative;
        border-radius: 5px 5px 0 0;
        background: #f5f5f5;
        flex-shrink: 0;
    }

    .car__item__pic--fixed .owl-carousel,
    .car__item__pic--fixed .owl-stage-outer,
    .car__item__pic--fixed .owl-stage,
    .car__item__pic--fixed .owl-item {
        height: 100%;
    }

    .car__item__pic--fixed img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }



    /* ============================================
   CARD CONTENT SECTION
   ============================================ */
    .car__item__text--fixed {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 20px;
        box-sizing: border-box;
    }

    .car__item__text__inner {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* ============================================
   CARD TITLE
   ============================================ */
    .car-title,
    .car__item__text--fixed h5 {
        margin: 0;
        font-size: 18px;
        line-height: 1.4;
        color: #19191a;
        min-height: 50px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .car__item__text car__item__text--fixed {
        margin-top: -20px;
    }

    .car-title a,
    .car__item__text--fixed h5 a {
        margin-top: -5px;
        color: #19191a;
        text-decoration: none;
        transition: color 0.3s ease;
        display: block;
    }

    .car-title a:hover,
    .car__item__text--fixed h5 a:hover {
        color: #df2d24;
    }

    /* ============================================
   CARD HEADER - Tahun & Stock Badges
   ============================================ */
    .car__header {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin: 0;
    }

    .label-date {
        display: inline-block;
        padding: 5px 11px;
        background: #f8f8f8;
        color: #19191a;
        font-size: 11px;
        font-weight: 700;
        border-radius: 5px;
        letter-spacing: 0.5px;
        border: 1px solid #e8e8e8;
        line-height: 1.2;
        margin: 0;
    }

    .label-stock {
        display: inline-block;
        padding: 5px 11px;
        font-size: 11px;
        font-weight: 700;
        border-radius: 5px;
        letter-spacing: 0.5px;
        border: 1px solid;
        line-height: 1.2;
        margin: 0;
    }

    .label-stock.available {
        background: #e8f5e9;
        color: #2e7d32;
        border-color: #c8e6c9;
    }

    .label-stock.limited {
        background: #fff3e0;
        color: #e65100;
        border-color: #ffe0b2;
    }

    /* ============================================
   CARD LOCATION
   ============================================ */
    .car__location {
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 0;
        padding: 0;
        font-size: 13px;
        color: #707079;
    }

    .car__location i {
        color: #df2d24;
        font-size: 14px;
        flex-shrink: 0;
    }

    .car__location span {
        color: #707079;
        font-weight: 500;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    /* ============================================
   CARD SPECIFICATIONS
   ============================================ */
    .car__specs {
        margin: 0;
        padding: 12px 0;
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 8px;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
    }

    .car__specs .spec-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        padding: 0;
        margin: 0;
    }

    .car__specs .spec-item i {
        color: #df2d24;
        font-size: 14px;
        width: 18px;
        text-align: center;
        flex-shrink: 0;
    }

    .car__specs .spec-item span {
        font-weight: 500;
        color: #19191a;
        font-size: 13px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        flex: 1;
    }

    /* ============================================
   CARD DESCRIPTION
   ============================================ */
    .car__description {
        margin: 0;
        padding: 0;
        flex-shrink: 0;
    }

    .car__description p {
        font-size: 12px;
        line-height: 1.6;
        color: #666;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        letter-spacing: 0.2px;
    }

    /* ============================================
   CARD PRICE SECTION
   ============================================ */
    .car__item__price {
        margin-top: auto;
        padding-top: 16px;
        border-top: 2px solid #f0f0f0;
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex-shrink: 0;
        box-sizing: border-box;
    }

    .car__price__header {
        margin: 0;
        padding: 0;
    }

    .car-option {
        display: inline-block;
        padding: 6px 12px;
        background: #df2d24;
        color: #fff;
        width: 100%;
        font-size: 10px;
        font-weight: 700;
        border-radius: 5px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        box-shadow: 0 2px 4px rgba(223, 45, 36, 0.2);
        line-height: 1.2;
        margin: 0;
    }

    .car-option.sale {
        background: #19191a;
        box-shadow: 0 2px 4px rgba(25, 25, 26, 0.2);
    }

    .car__price__value,
    .car__item__price h6 {
        margin: 0;
        margin-top: 10px;
        font-size: 22px;
        margin-left: -120px;
        font-weight: 800;
        color: #df2d24;
        line-height: 1.3;
        word-wrap: break-word;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .car__price__value span,
    .car__item__price h6 span {
        font-size: 14px;
        font-weight: 500;
        color: #999;
        display: inline-block;
        margin-left: 4px;
    }

    /* ============================================
   RESPONSIVE DESIGN
   ============================================ */
    @media (max-width: 991px) {
        .car__item--fixed {
            max-width: 280px;
        }
    }

    @media (max-width: 767px) {
        .car__item--fixed {
            max-width: 100%;
            min-height: auto;
        }

        .car__item__text--fixed {
            padding: 18px 16px;
        }

        .car__item__pic--fixed {
            height: 180px;
        }

        .car-title,
        .car__item__text--fixed h5 {
            font-size: 17px;
            font-weight: 1200;
            font-transform: bold;
            min-height: 48px;
        }

        .car__price__value,
        .car__item__price h6 {
            font-size: 20px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Price Range Slider
        var minPrice = {{ $minPrice ?? 0 }};
        var maxPrice = {{ $maxPrice ?? 1000000000 }};
        var currentMin = {{ request('min_price', $minPrice ?? 0) }};
        var currentMax = {{ request('max_price', $maxPrice ?? 1000000000) }};

        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Tunggu hingga jQuery UI ter-load (setelah main.js)
        function initPriceSlider() {
            if (typeof $ === 'undefined' || !$.fn.slider) {
                setTimeout(initPriceSlider, 100);
                return;
            }

            // Pastikan slider belum diinisialisasi oleh main.js, atau destroy dulu
            if ($("#priceRange").hasClass('ui-slider')) {
                $("#priceRange").slider('destroy');
            }
            
            $("#priceRange").slider({
                range: true,
                min: minPrice,
                max: maxPrice,
                values: [currentMin, currentMax],
                slide: function(event, ui) {
                    $("#filterAmount").val("Rp " + formatNumber(ui.values[0]) + " - Rp " + formatNumber(ui.values[1]));
                    $("#minPrice").val(ui.values[0]);
                    $("#maxPrice").val(ui.values[1]);
                },
                change: function(event, ui) {
                    // Auto submit on change
                    setTimeout(function() {
                        document.getElementById('filterForm').submit();
                    }, 500);
                }
            });

            // Pastikan format Rupiah digunakan, override format dari main.js
            $("#filterAmount").val("Rp " + formatNumber(currentMin) + " - Rp " + formatNumber(currentMax));
        }

        // Tunggu window load untuk memastikan main.js sudah ter-load
        if (window.addEventListener) {
            window.addEventListener('load', function() {
                setTimeout(initPriceSlider, 500);
            });
        } else {
            setTimeout(initPriceSlider, 1000);
        }
    });

    function handleSortChange(value) {
        var parts = value.split('_');
        var sortBy = parts[0];
        var sortOrder = parts[1];

        document.getElementById('sortBy').value = sortBy;
        document.getElementById('sortOrder').value = sortOrder;
        document.getElementById('sortForm').submit();
    }

    // Animation on scroll
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.animate-fade-in-up');
        cards.forEach((card, index) => {
            const delay = card.getAttribute('data-animation-delay') || 0;
            card.style.setProperty('--delay', delay);
        });
    });

</script>

@endsection