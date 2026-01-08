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
                    <div class="car__details__pic">
                        <div class="car__details__pic__large">
                            @if($car->image && is_array($car->image) && count($car->image) > 0)
                                <img class="car-big-img" src="{{ asset('storage/' . $car->image[0]) }}" alt="{{ $car->brand }}">
                            @else
                                <img class="car-big-img" src="{{ asset('garasi62/img/cars/details/cd-1.jpg') }}" alt="{{ $car->brand }}">
                            @endif
                        </div>
                        <div class="car-thumbs">
                            <div class="car-thumbs-track" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px;">
                                @if($car->image && is_array($car->image) && count($car->image) > 0)
                                    @foreach(array_slice($car->image, 1, 5) as $index => $imagePath)
                                        <div class="ct" data-imgbigurl="{{ asset('storage/' . $imagePath) }}" style="cursor: pointer; border: 2px solid transparent; transition: border 0.3s;">
                                            <img src="{{ asset('storage/' . $imagePath) }}" alt="Thumbnail {{ $index + 2 }}" style="width: 100%; height: 80px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                    @if(count($car->image) < 6)
                                        @for($i = count($car->image); $i < 6; $i++)
                                            <div style="width: 100%; height: 80px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">
                                                <i class="fa fa-image"></i>
                                            </div>
                                        @endfor
                                    @endif
                                @else
                                    @for($i = 0; $i < 5; $i++)
                                        <div style="width: 100%; height: 80px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">
                                            <i class="fa fa-image"></i>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="car__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Vehicle
                                    Overview</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Technical</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Features & Options</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-4" role="tab">Vehicle Location</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="car__details__tab__info">
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
                    <div class="car__details__sidebar">
                        <div class="car__details__sidebar__model">
                            <ul>
                                @if($car->stock)
                                <li>Stock <span>{{ $car->stock }}</span></li>
                                @endif
                                @if($car->vin)
                                <li>Vin <span>{{ $car->vin }}</span></li>
                                @endif
                            </ul>
                            <a href="#" class="primary-btn">Get Today Is Price</a>
                            <p>Pricing in {{ date('m/d/Y') }}</p>
                        </div>
                        <div class="car__details__sidebar__payment">
                            <ul>
                                @if($car->msrp)
                                <li>MSRP <span>Rp {{ number_format($car->msrp, 0, ',', '.') }}</span></li>
                                @endif
                                @if($car->dealer_discounts)
                                <li>Dealer Discounts <span>Rp {{ number_format($car->dealer_discounts, 0, ',', '.') }}</span></li>
                                @endif
                                <li>Price <span>Rp {{ number_format($car->harga, 0, ',', '.') }}</span></li>
                            </ul>
                            <a href="#" class="primary-btn"><i class="fa fa-credit-card"></i> Express Purchase</a>
                            <a href="#" class="primary-btn sidebar-btn"><i class="fa fa-sliders"></i> Build Payment</a>
                            <a href="#" class="primary-btn sidebar-btn"><i class="fa fa-money"></i> Value Trade</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Car Details Section End -->

    <script>
        // Handle thumbnail click untuk mengubah gambar besar
        document.addEventListener('DOMContentLoaded', function() {
            const thumbnails = document.querySelectorAll('.car-thumbs .ct');
            const bigImg = document.querySelector('.car-big-img');
            
            thumbnails.forEach(function(thumb) {
                thumb.addEventListener('click', function() {
                    const imgUrl = this.getAttribute('data-imgbigurl');
                    if (imgUrl && bigImg) {
                        bigImg.src = imgUrl;
                        // Update active state
                        thumbnails.forEach(t => t.style.border = '2px solid transparent');
                        this.style.border = '2px solid #dc3545';
                    }
                });
                
                // Hover effect
                thumb.addEventListener('mouseenter', function() {
                    if (this.style.border !== '2px solid #dc3545') {
                        this.style.border = '2px solid #ccc';
                    }
                });
                thumb.addEventListener('mouseleave', function() {
                    if (this.style.border !== '2px solid #dc3545') {
                        this.style.border = '2px solid transparent';
                    }
                });
            });
            
            // Set first thumbnail as active
            if (thumbnails.length > 0) {
                thumbnails[0].style.border = '2px solid #dc3545';
            }
        });
    </script>
    @endsection