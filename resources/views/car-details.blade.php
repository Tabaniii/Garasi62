@extends('template.temp')

<!-- @section('title', 'Home - GARASI62') Set the title for the page -->

@section('content')
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option set-bg" data-setbg="{{ asset('garasi62/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>{{ $car->brand }} {{ $car->tahun }}</h2>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                            <a href="{{ route('cars') }}">Car Listing</a>
                            <span>{{ $car->brand }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Car Details Section Begin -->
    <section class="car-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="car-details-gallery animate-fade-in">
                        <div class="car-main-image-wrapper">
                            @if($car->image && is_array($car->image) && count($car->image) > 0)
                                <img class="car-big-img" src="{{ asset('storage/' . $car->image[0]) }}" alt="{{ $car->brand }}" id="mainCarImage">
                            @else
                                <img class="car-big-img" src="{{ asset('garasi62/img/cars/details/cd-1.jpg') }}" alt="{{ $car->brand }}" id="mainCarImage">
                            @endif
                            <div class="image-overlay-badge">
                                <span class="car-type-overlay {{ $car->tipe == 'buy' ? 'badge-sale-overlay' : 'badge-rent-overlay' }}">
                                    {{ $car->tipe == 'rent' ? 'For Rent' : 'For Sale' }}
                                </span>
                            </div>
                        </div>
                        <div class="car-thumbs-modern">
                            <div class="car-thumbs-grid">
                                @if($car->image && is_array($car->image) && count($car->image) > 0)
                                    @foreach(array_slice($car->image, 0, 5) as $index => $imagePath)
                                        <div class="thumb-item {{ $index === 0 ? 'active' : '' }}" data-imgbigurl="{{ asset('storage/' . $imagePath) }}">
                                            <img src="{{ asset('storage/' . $imagePath) }}" alt="Thumbnail {{ $index + 1 }}">
                                            <div class="thumb-overlay"></div>
                                        </div>
                                    @endforeach
                                    @if(count($car->image) < 5)
                                        @for($i = count($car->image); $i < 5; $i++)
                                            <div class="thumb-item placeholder">
                                                <i class="fa fa-image"></i>
                                            </div>
                                        @endfor
                                    @endif
                                @else
                                    @for($i = 0; $i < 5; $i++)
                                        <div class="thumb-item placeholder">
                                            <i class="fa fa-image"></i>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="car-details-tabs animate-slide-in-left">
                        <ul class="nav nav-tabs modern-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">
                                    <i class="fa fa-info-circle"></i> Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">
                                    <i class="fa fa-cog"></i> Technical
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">
                                    <i class="fa fa-star"></i> Features
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-4" role="tab">
                                    <i class="fa fa-map-marker"></i> Location
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="car__details__tab__info animate-fade-in">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="car__details__tab__info__item">
                                                <h5>General Information</h5>
                                                @if($car->description)
                                                    <div class="mb-3">
                                                        {!! nl2br(e($car->description)) !!}
                                                    </div>
                                                @else
                                                    <ul>
                                                        <li><i class="fa fa-check"></i> Brand: <strong>{{ $car->brand }}</strong></li>
                                                        <li><i class="fa fa-check"></i> Tahun: <strong>{{ $car->tahun }}</strong></li>
                                                        <li><i class="fa fa-check"></i> Kilometer: <strong>{{ $car->kilometer }} km</strong></li>
                                                        <li><i class="fa fa-check"></i> Transmisi: <strong>{{ $car->transmisi }}</strong></li>
                                                        <li><i class="fa fa-check"></i> Kapasitas Mesin: <strong>{{ $car->kapasitasmesin }}</strong></li>
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="car__details__tab__info__item">
                                                <h5>Spesifikasi</h5>
                                                <ul>
                                                    <li><i class="fa fa-check"></i> Brand: <strong>{{ $car->brand }}</strong></li>
                                                    <li><i class="fa fa-check"></i> Tahun: <strong>{{ $car->tahun }}</strong></li>
                                                    <li><i class="fa fa-check"></i> Kilometer: <strong>{{ $car->kilometer }} km</strong></li>
                                                    <li><i class="fa fa-check"></i> Transmisi: <strong>{{ $car->transmisi }}</strong></li>
                                                    <li><i class="fa fa-check"></i> Kapasitas Mesin: <strong>{{ $car->kapasitasmesin }}</strong></li>
                                                    @if($car->location)
                                                    <li><i class="fa fa-check"></i> Lokasi: <strong>{{ $car->location }}</strong></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="car__details__tab__feature">
                                    <div class="row">
                                        @if($car->interior_features && is_array($car->interior_features) && count($car->interior_features) > 0)
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="car__details__tab__feature__item">
                                                <h5>Interior Design</h5>
                                                <ul>
                                                    @foreach(array_filter($car->interior_features) as $feature)
                                                    <li><i class="fa fa-check-circle"></i> {{ $feature }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        @endif
                                        @if($car->safety_features && is_array($car->safety_features) && count($car->safety_features) > 0)
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="car__details__tab__feature__item">
                                                <h5>Safety Design</h5>
                                                <ul>
                                                    @foreach(array_filter($car->safety_features) as $feature)
                                                    <li><i class="fa fa-check-circle"></i> {{ $feature }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        @endif
                                        @if($car->extra_features && is_array($car->extra_features) && count($car->extra_features) > 0)
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="car__details__tab__feature__item">
                                                <h5>Extra Design</h5>
                                                <ul>
                                                    @foreach(array_filter($car->extra_features) as $feature)
                                                    <li><i class="fa fa-check-circle"></i> {{ $feature }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                <div class="car__details__tab__info">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="car__details__tab__info__item">
                                                <h5>Technical Specifications</h5>
                                                @if($car->technical_specs)
                                                    <div class="mb-3">
                                                        {!! nl2br(e($car->technical_specs)) !!}
                                                    </div>
                                                @else
                                                    <ul>
                                                        <li><i class="fa fa-check"></i> Brand: <strong>{{ $car->brand }}</strong></li>
                                                        <li><i class="fa fa-check"></i> Tahun: <strong>{{ $car->tahun }}</strong></li>
                                                        <li><i class="fa fa-check"></i> Kilometer: <strong>{{ $car->kilometer }} km</strong></li>
                                                        <li><i class="fa fa-check"></i> Transmisi: <strong>{{ $car->transmisi }}</strong></li>
                                                        <li><i class="fa fa-check"></i> Kapasitas Mesin: <strong>{{ $car->kapasitasmesin }}</strong></li>
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-3" role="tabpanel">
                                <div class="car__details__tab__feature">
                                    <div class="row">
                                        @if($car->interior_features && is_array($car->interior_features) && count(array_filter($car->interior_features)) > 0)
                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="car__details__tab__feature__item">
                                                <h5>Interior Design</h5>
                                                <ul>
                                                    @foreach(array_filter($car->interior_features) as $feature)
                                                    <li><i class="fa fa-check-circle"></i> {{ $feature }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        @endif
                                        @if($car->safety_features && is_array($car->safety_features) && count(array_filter($car->safety_features)) > 0)
                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="car__details__tab__feature__item">
                                                <h5>Safety Design</h5>
                                                <ul>
                                                    @foreach(array_filter($car->safety_features) as $feature)
                                                    <li><i class="fa fa-check-circle"></i> {{ $feature }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        @endif
                                        @if($car->extra_features && is_array($car->extra_features) && count(array_filter($car->extra_features)) > 0)
                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="car__details__tab__feature__item">
                                                <h5>Extra Design</h5>
                                                <ul>
                                                    @foreach(array_filter($car->extra_features) as $feature)
                                                    <li><i class="fa fa-check-circle"></i> {{ $feature }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        @endif
                                        @if((!$car->interior_features || count(array_filter($car->interior_features ?? [])) == 0) && 
                                            (!$car->safety_features || count(array_filter($car->safety_features ?? [])) == 0) && 
                                            (!$car->extra_features || count(array_filter($car->extra_features ?? [])) == 0))
                                        <div class="col-lg-12">
                                            <p class="text-muted">Belum ada features yang ditambahkan.</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-4" role="tabpanel">
                                <div class="car__details__tab__info">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="car__details__tab__info__item">
                                                <h5>Vehicle Location</h5>
                                                @if($car->location)
                                                    <p><i class="fa fa-map-marker"></i> <strong>{{ $car->location }}</strong></p>
                                                @else
                                                    <p><i class="fa fa-map-marker"></i> Lokasi belum ditentukan</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="car-details-sidebar animate-slide-in-right">
                        <div class="sidebar-card">
                            <div class="sidebar-header">
                                <h5><i class="fa fa-info-circle"></i> Informasi</h5>
                            </div>
                            <div class="sidebar-info-list">
                                @if($car->stock)
                                <div class="info-item">
                                    <span class="info-label">Stock</span>
                                    <span class="info-value stock-value">{{ $car->stock }}</span>
                                </div>
                                @endif
                                @if($car->vin)
                                <div class="info-item">
                                    <span class="info-label">VIN</span>
                                    <span class="info-value">{{ $car->vin }}</span>
                                </div>
                                @endif
                                <div class="info-item">
                                    <span class="info-label">Tahun</span>
                                    <span class="info-value">{{ $car->tahun }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Kilometer</span>
                                    <span class="info-value">{{ number_format($car->kilometer ?? 0, 0, ',', '.') }} km</span>
                                </div>
                            </div>
                            <div class="wishlist-button-wrapper">
                            @auth
                                @if(Auth::user()->role === 'buyer')
                                    @php
                                        $isInWishlist = \App\Models\Wishlist::where('user_id', Auth::id())
                                            ->where('car_id', $car->id)
                                            ->exists();
                                    @endphp
                                    @if($isInWishlist)
                                        <form action="{{ route('wishlist.destroy', $car->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sidebar-action btn-wishlist-remove">
                                                <i class="fa fa-heart-broken"></i> Hapus dari Wishlist
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('wishlist.store', $car->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-sidebar-action btn-wishlist-add">
                                                <i class="fa fa-heart"></i> Tambah ke Wishlist
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            @endauth
                            </div>
                        </div>
                        <div class="sidebar-card price-card">
                            <div class="sidebar-header">
                                <h5><i class="fa fa-tag"></i> Harga</h5>
                            </div>
                            <div class="price-details">
                                @if($car->msrp)
                                <div class="price-item">
                                    <span class="price-label">MSRP</span>
                                    <span class="price-amount">Rp {{ number_format($car->msrp, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                @if($car->dealer_discounts)
                                <div class="price-item discount">
                                    <span class="price-label">Discount</span>
                                    <span class="price-amount">- Rp {{ number_format($car->dealer_discounts, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                <div class="price-item final-price">
                                    <span class="price-amount main-price">Rp {{ number_format($car->harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        @if($car->seller)
                        <div class="sidebar-card seller-card">
                            <div class="sidebar-header">
                                <h5><i class="fa fa-user"></i> Penjual</h5>
                            </div>
                            <div class="seller-info">
                                <div class="seller-avatar">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <div class="seller-details">
                                    <div class="seller-name">{{ $car->seller->name }}</div>
                                    @if($car->seller->email)
                                    <div class="seller-email">
                                        <i class="fa fa-envelope"></i> {{ $car->seller->email }}
                                    </div>
                                    @endif
                                    @if($car->seller->phone)
                                    <div class="seller-phone">
                                        <i class="fa fa-phone"></i> {{ $car->seller->phone }}
                                    </div>
                                    @endif
                                    @if($car->seller->city)
                                    <div class="seller-location">
                                        <i class="fa fa-map-marker"></i> {{ $car->seller->city }}
                                    </div>
                                    @endif
                                </div>
                                <div class="seller-chat-button">
                                    @auth
                                        @if(Auth::user()->role === 'buyer')
                                            <a href="{{ route('chat.seller', $car->seller->id) }}?car_id={{ $car->id }}" class="btn-sidebar-action btn-chat-seller">
                                                <i class="fa fa-comments"></i> Chat ke Penjual
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn-sidebar-action btn-chat-login">
                                            <i class="fa fa-comments"></i> Login untuk Chat
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                        @endif
                        @auth
                            @if(Auth::user()->role !== 'seller' || Auth::id() != $car->seller_id)
                            <div class="sidebar-card report-card">
                                <div class="sidebar-header">
                                    <h5><i class="fa fa-flag"></i> Laporkan Mobil</h5>
                                </div>
                                <button type="button" class="btn-sidebar-action btn-report" data-toggle="modal" data-target="#reportModal">
                                    <i class="fa fa-flag"></i> Laporkan Mobil Ini
                                </button>
                            </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Car Details Section End -->
    
    <!-- Report Modal -->
    @auth
    <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">
                        <i class="fa fa-flag"></i> Laporkan Mobil
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('reports.store', $car->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="mb-3">Anda akan melaporkan mobil <strong>{{ $car->brand }} {{ $car->nama }}</strong>. Laporan ini akan dikirim ke admin dan seller pemilik mobil.</p>
                        
                        <div class="mb-3">
                            <label for="reason" class="form-label">Alasan Pelaporan <span class="text-danger">*</span></label>
                            <select class="form-control" id="reason" name="reason" required>
                                <option value="">Pilih Alasan</option>
                                <option value="false_information">Informasi Palsu</option>
                                <option value="inappropriate_content">Konten Tidak Pantas</option>
                                <option value="spam">Spam</option>
                                <option value="duplicate">Duplikat</option>
                                <option value="scam">Penipuan</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Detail Laporan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" name="message" rows="4" placeholder="Jelaskan secara detail mengapa Anda melaporkan mobil ini..." required minlength="10" maxlength="1000"></textarea>
                            <small class="form-text text-muted">Minimal 10 karakter, maksimal 1000 karakter</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-flag"></i> Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endauth

    <style>
        /* Car Details Modern Design */
        .car-details-gallery {
            margin-bottom: 30px;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            background: #fff;
        }

        .car-main-image-wrapper {
            position: relative;
            width: 100%;
            height: 450px;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        }

        .car-main-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .car-main-image-wrapper:hover img {
            transform: scale(1.05);
        }

        .image-overlay-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 2;
        }

        .car-type-overlay {
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        .badge-rent-overlay {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.9), rgba(96, 165, 250, 0.9));
            color: #fff;
        }

        .badge-sale-overlay {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.9), rgba(52, 211, 153, 0.9));
            color: #fff;
        }

        .car-thumbs-modern {
            padding: 16px;
            background: #fafbfc;
        }

        .car-thumbs-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }

        .thumb-item {
            position: relative;
            height: 80px;
            border-radius: 5px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #f0f0f0;
        }

        .thumb-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .thumb-item:hover {
            transform: translateY(-4px);
            border-color: #df2d24;
            box-shadow: 0 8px 20px rgba(223, 45, 36, 0.3);
        }

        .thumb-item:hover img {
            transform: scale(1.1);
        }

        .thumb-item.active {
            border-color: #df2d24;
            box-shadow: 0 4px 15px rgba(223, 45, 36, 0.4);
        }

        .thumb-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.1));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .thumb-item:hover .thumb-overlay {
            opacity: 1;
        }

        .thumb-item.placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            color: #9ca3af;
        }

        .thumb-item.placeholder i {
            font-size: 24px;
        }

        /* Modern Tabs */
        .modern-tabs {
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 30px;
        }

        .modern-tabs .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            padding: 12px 20px;
            color: #6b7280;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            background: transparent;
        }

        .modern-tabs .nav-link i {
            margin-right: 6px;
            font-size: 13px;
        }

        .modern-tabs .nav-link:hover {
            color: #df2d24;
            background: rgba(223, 45, 36, 0.05);
            border-bottom-color: rgba(223, 45, 36, 0.3);
        }

        .modern-tabs .nav-link.active {
            color: #df2d24;
            border-bottom-color: #df2d24;
            background: rgba(223, 45, 36, 0.05);
        }

        /* Sidebar Modern Design */
        .car-details-sidebar {
            position: sticky;
            top: 20px;
            z-index: 10;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
        }
        
        .car-details-sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .car-details-sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 5px;
        }
        
        .car-details-sidebar::-webkit-scrollbar-thumb {
            background: #df2d24;
            border-radius: 5px;
        }
        
        .car-details-sidebar::-webkit-scrollbar-thumb:hover {
            background: #b91c1c;
        }
        

        .sidebar-card {
            background: #fff;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
            transition: all 0.3s;
            position: relative;
        }
        

        .sidebar-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .sidebar-header {
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }

        .sidebar-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-header h5 i {
            color: #df2d24;
        }

        .sidebar-info-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            background: linear-gradient(135deg, #fafbfc, #ffffff);
            border-radius: 5px;
            border: 1px solid #f0f0f0;
            transition: all 0.3s;
        }

        .info-item:hover {
            background: linear-gradient(135deg, #ffffff, #fafbfc);
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .info-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
        }

        .info-value {
            font-size: 13px;
            color: #1a1a1a;
            font-weight: 700;
        }

        .stock-value {
            padding: 3px 10px;
            border-radius: 5px;
            background: linear-gradient(135deg, #10b981, #34d399);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
        }

        /* Price Card */
        .price-details {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 16px;
        }

        .price-item {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            background: linear-gradient(135deg, #fafbfc, #ffffff);
            border-radius: 5px;
        }

        .price-item.discount {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
        }

        .price-item.final-price {
            background: linear-gradient(135deg, #df2d24, #ff6b6b);
            color: #fff;
            padding: 14px 16px;
        }

        .price-item.final-price .price-label,
        .price-item.final-price .price-amount {
            color: #fff;
        }

        .price-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
        }

        .price-item.final-price .price-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
        }

        .price-amount {
            font-size: 14px;
            color: #1a1a1a;
            font-weight: 700;
        }

        .main-price {
            font-size: 20px;
            font-weight: 900;
        }

        /* Sidebar Buttons */
        .sidebar-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .btn-sidebar-primary,
        .btn-sidebar-secondary,
        .btn-sidebar-action {
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
            width: 100%;
        }
        
        .wishlist-button-wrapper {
            margin-top: 16px;
            margin-bottom: 0;
        }
        
        .wishlist-button-wrapper form {
            margin: 0;
        }
        
        .chat-button-wrapper {
            margin-top: 12px;
        }
        
        .chat-button-wrapper a {
            margin: 0;
        }

        .btn-sidebar-primary {
            background: linear-gradient(135deg, #1a1a1a, #4a4a4a);
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-sidebar-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }

        .btn-sidebar-secondary {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            color: #1a1a1a;
            border: 1px solid #e0e0e0;
        }

        .btn-sidebar-secondary:hover {
            background: linear-gradient(135deg, #e9ecef, #dee2e6);
            transform: translateY(-2px);
        }

        .btn-wishlist-add {
            background: linear-gradient(135deg, #a855f7, #9333ea);
            color: #fff;
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.3);
        }

        .btn-wishlist-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(168, 85, 247, 0.4);
        }

        .btn-wishlist-remove {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: #fff;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .btn-wishlist-remove:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        }

        .btn-chat-seller,
        .btn-chat-login {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-chat-seller:hover,
        .btn-chat-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            color: #fff;
        }

        .btn-report {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-report:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }

        /* Seller Card */
        .seller-card {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
        }

        .seller-info {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .seller-avatar {
            display: flex;
            justify-content: center;
            margin-bottom: 8px;
        }

        .seller-avatar i {
            font-size: 64px;
            color: #df2d24;
            opacity: 0.8;
        }

        .seller-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .seller-name {
            font-size: 18px;
            font-weight: 800;
            color: #1a1a1a;
            text-align: center;
            margin-bottom: 4px;
        }

        .seller-email,
        .seller-phone,
        .seller-location {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #fff;
            border-radius: 5px;
            font-size: 13px;
            color: #4b5563;
            border: 1px solid #e5e7eb;
            transition: all 0.3s;
        }

        .seller-email:hover,
        .seller-phone:hover,
        .seller-location:hover {
            background: #f9fafb;
            border-color: #df2d24;
            transform: translateX(4px);
        }

        .seller-email i,
        .seller-phone i,
        .seller-location i {
            color: #df2d24;
            font-size: 14px;
            width: 18px;
            text-align: center;
        }

        .seller-chat-button {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 2px solid #f0f0f0;
        }

        .seller-chat-button .btn-sidebar-action {
            margin: 0;
        }

        /* Animations */
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

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        .animate-slide-in-left {
            animation: slideInLeft 0.8s ease-out;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.8s ease-out;
        }

        .tab-pane {
            animation: fadeIn 0.5s ease-out;
        }

        .car__details__tab__info,
        .car__details__tab__feature {
            padding: 24px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .car__details__tab__info__item h5,
        .car__details__tab__feature__item h5 {
            font-size: 18px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }

        .car__details__tab__info__item ul,
        .car__details__tab__feature__item ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .car__details__tab__info__item ul li,
        .car__details__tab__feature__item ul li {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            font-size: 14px;
        }

        .car__details__tab__info__item ul li:hover,
        .car__details__tab__feature__item ul li:hover {
            padding-left: 8px;
            background: rgba(223, 45, 36, 0.05);
        }

        .car__details__tab__info__item ul li i,
        .car__details__tab__feature__item ul li i {
            color: #df2d24;
            font-size: 14px;
        }

        .car__details__tab__feature__item {
            padding: 16px;
            background: linear-gradient(135deg, #fafbfc, #ffffff);
            border-radius: 5px;
            border: 1px solid #f0f0f0;
            transition: all 0.3s;
        }
        
        .car__details__tab__feature__item h5 {
            font-size: 16px;
            margin-bottom: 12px;
            padding-bottom: 10px;
        }

        .car__details__tab__feature__item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .car-main-image-wrapper {
                height: 380px;
            }

            .car-details-sidebar {
                position: relative;
                top: 0;
                margin-top: 24px;
                max-height: none;
                overflow-y: visible;
            }
            
            .sidebar-card {
                padding: 18px;
            }
        }

        @media (max-width: 767px) {
            .car-main-image-wrapper {
                height: 280px;
            }

            .car-thumbs-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
            }
            
            .thumb-item {
                height: 70px;
            }
            
            .sidebar-card {
                padding: 16px;
            }
            
            .main-price {
                font-size: 18px;
            }
        }
    </style>

    <script>
        // Handle thumbnail click dengan animasi
        document.addEventListener('DOMContentLoaded', function() {
            const thumbnails = document.querySelectorAll('.thumb-item');
            const mainImg = document.getElementById('mainCarImage');
            
            thumbnails.forEach(function(thumb) {
                thumb.addEventListener('click', function() {
                    const imgUrl = this.getAttribute('data-imgbigurl');
                    if (imgUrl && mainImg) {
                        // Fade out effect
                        mainImg.style.opacity = '0';
                        mainImg.style.transform = 'scale(0.95)';
                        
                        setTimeout(function() {
                            mainImg.src = imgUrl;
                            // Fade in effect
                            mainImg.style.opacity = '1';
                            mainImg.style.transform = 'scale(1)';
                        }, 200);
                        
                        // Update active state
                        thumbnails.forEach(t => t.classList.remove('active'));
                        this.classList.add('active');
                    }
                });
            });
        });

    </script>
    @endsection