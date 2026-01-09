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
                    @forelse($cars as $car)
                        <div class="col-lg-4 col-md-4">
                            <div class="car__item car__item--fixed">
                                <!-- Card Image -->
                                <div class="car__item__pic__slider owl-carousel car__item__pic--fixed">
                                    @if($car->image && is_array($car->image) && count($car->image) > 0)
                                        @foreach($car->image as $imagePath)
                                            <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $car->brand }} {{ $car->nama ?? '' }}">
                                        @endforeach
                                    @else
                                        <img src="{{ asset('garasi62/img/cars/car-8.jpg') }}" alt="{{ $car->brand }} {{ $car->nama ?? '' }}">
                                    @endif
                                </div>
                                
                                <!-- Card Content -->
                                <div class="car__item__text car__item__text--fixed">
                                    <div class="car__item__text__inner">
                                        <!-- Title -->
                                        <h5 class="car-title">
                                            <a href="{{ route('car.details', $car->id) }}">
                                                {{ $car->brand }}@if($car->nama) {{ $car->nama }}@endif
                                            </a>
                                        </h5>
                                        
                                        <!-- Badges: Tahun & Stock -->
                                        <div class="car__header">
                                            <div class="label-date">{{ $car->tahun }}</div>
                                            @if($car->stock)
                                                <div class="label-stock {{ strtolower($car->stock) === 'tersedia' ? 'available' : 'limited' }}">
                                                    {{ $car->stock }}
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Location -->
                                        @if($car->location)
                                            <div class="car__location">
                                                <i class="fa fa-map-marker"></i>
                                                <span>{{ $car->location }}</span>
                                            </div>
                                        @endif
                                        
                                        <!-- Specifications -->
                                        <ul class="car__specs">
                                            <li class="spec-item">
                                                <i class="fa fa-tachometer"></i>
                                                <span>{{ number_format($car->kilometer ?? 0, 0, ',', '.') }} km</span>
                                            </li>
                                            <li class="spec-item">
                                                <i class="fa fa-cog"></i>
                                                <span>{{ $car->transmisi ?? '-' }}</span>
                                            </li>
                                            <li class="spec-item">
                                                <i class="fa fa-car"></i>
                                                <span>{{ $car->kapasitasmesin ?? '-' }}</span>
                                            </li>
                                        </ul>
                                        
                                        <!-- Description -->
                                        @if($car->description)
                                            <div class="car__description">
                                                <p>
                                                    {{ mb_substr(strip_tags($car->description), 0, 100) }}
                                                    @if(mb_strlen(strip_tags($car->description)) > 100)
                                                        ...
                                                    @endif
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Price Section -->
                                    <div class="car__item__price">
                                        <div class="car__price__header">
                                            <span class="car-option {{ $car->tipe == 'buy' ? 'sale' : '' }}">
                                                {{ $car->tipe == 'rent' ? 'For Rent' : 'For Sale' }}
                                            </span>
                                        </div>
                                        <h6 class="car__price__value">
                                            Rp {{ number_format($car->harga ?? 0, 0, ',', '.') }}
                                            @if($car->tipe == 'rent' && $car->metode)
                                                <span>/{{ $car->metode }}</span>
                                            @endif
                                        </h6>
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
                                $endPage = min($lastPage, $startPage + 4);
                            }
                        @endphp
                        
                        @if($startPage > 1)
                            <a href="{{ $cars->url(1) }}">1</a>
                            @if($startPage > 2)
                                <span>...</span>
                            @endif
                        @endif
                        
                        @for($page = $startPage; $page <= $endPage; $page++)
                            @if($page == $currentPage)
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
   CARD CONTAINER - Fixed Size Styling
   ============================================ */
.car__item--fixed {
    width: 100%;
    max-width: 280px;
    min-height: 615px;
    height: auto;
    margin: 0 auto 30px;
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: 12px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.car__item--fixed:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    border-color: #e0e0e0;
}

/* ============================================
   CARD IMAGE SECTION
   ============================================ */
.car__item__pic--fixed {
    width: 100%;
    height: 180px;
    overflow: hidden;
    position: relative;
    border-radius: 12px 12px 0 0;
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

.car__item__text car__item__text--fixed{
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
    border-radius: 6px;
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
    border-radius: 6px;
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
    
    if (typeof $ !== 'undefined' && $.fn.slider) {
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
        
        $("#filterAmount").val("Rp " + formatNumber(currentMin) + " - Rp " + formatNumber(currentMax));
    }
    
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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
</script>

@endsection