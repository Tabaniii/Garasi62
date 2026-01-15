@extends('template.temp')

@section('content')
<!-- Breadcrumb Begin -->
<div class="breadcrumb-option set-bg" data-setbg="{{ asset('garasi62/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Keranjang Saya</h2>
                    <div class="breadcrumb__links">
                        <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Keranjang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Cart Section Begin -->
<section class="car spad">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($carts->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="cart-items">
                    <div class="cart-header mb-4">
                        <h4><i class="fa fa-shopping-cart"></i> Item di Keranjang ({{ $carts->count() }})</h4>
                    </div>

                    @foreach($carts as $cart)
                    <div class="cart-item mb-4" style="border: 1px solid #e8e8e8; border-radius: 8px; padding: 20px; background: #fff;">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                @if($cart->car && $cart->car->image && is_array($cart->car->image) && count($cart->car->image) > 0)
                                    <a href="{{ route('car.details', $cart->car->id) }}">
                                        <img src="{{ asset('storage/' . $cart->car->image[0]) }}" alt="{{ $cart->car->brand }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                                    </a>
                                @else
                                    <img src="{{ asset('garasi62/img/cars/car-8.jpg') }}" alt="No Image" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-2">
                                    <a href="{{ route('car.details', $cart->car->id) }}" style="color: #333; text-decoration: none;">
                                        {{ $cart->car->brand }} @if($cart->car->nama){{ $cart->car->nama }}@endif
                                    </a>
                                </h5>
                                <p class="text-muted mb-2">
                                    <i class="fa fa-calendar"></i> Tahun: {{ $cart->car->tahun }} | 
                                    <i class="fa fa-tachometer"></i> {{ number_format($cart->car->kilometer ?? 0, 0, ',', '.') }} km
                                </p>
                                <p class="mb-2">
                                    <span class="badge {{ $cart->car->tipe == 'rent' ? 'badge-info' : 'badge-success' }}">
                                        {{ $cart->car->tipe == 'rent' ? 'For Rent' : 'For Sale' }}
                                    </span>
                                </p>
                                <p class="mb-0" style="font-size: 18px; font-weight: bold; color: #ff6b6b;">
                                    Rp {{ number_format($cart->car->harga ?? 0, 0, ',', '.') }}
                                    @if($cart->car->tipe == 'rent' && $cart->car->metode)
                                        <small>/{{ $cart->car->metode }}</small>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3 text-right">
                                <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="mb-3">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="quantity_{{ $cart->id }}" class="small">Jumlah:</label>
                                        <div class="input-group" style="width: 100px; margin: 0 auto;">
                                            <input type="number" name="quantity" id="quantity_{{ $cart->id }}" 
                                                   value="{{ $cart->quantity }}" min="1" max="10" 
                                                   class="form-control text-center" required>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary mt-2" style="width: 100%;">
                                            <i class="fa fa-sync"></i> Update
                                        </button>
                                    </div>
                                </form>
                                
                                <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus item ini dari keranjang?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="width: 100%;">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4">
                <div class="cart-summary" style="border: 1px solid #e8e8e8; border-radius: 8px; padding: 20px; background: #fff; position: sticky; top: 20px;">
                    <h4 class="mb-4"><i class="fa fa-calculator"></i> Ringkasan</h4>
                    
                    <div class="summary-item mb-3" style="border-bottom: 1px solid #e8e8e8; padding-bottom: 15px;">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Item:</span>
                            <strong>{{ $carts->sum('quantity') }} item</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Total Harga:</span>
                            <strong style="font-size: 20px; color: #ff6b6b;">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div class="cart-actions">
                        <a href="{{ route('cars') }}" class="btn btn-outline-primary btn-block mb-2">
                            <i class="fa fa-arrow-left"></i> Lanjutkan Belanja
                        </a>
                        <button type="button" class="btn btn-primary btn-block mb-2" disabled>
                            <i class="fa fa-credit-card"></i> Checkout (Coming Soon)
                        </button>
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Yakin ingin mengosongkan keranjang?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-block">
                                <i class="fa fa-trash"></i> Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-12">
                <div class="text-center" style="padding: 60px 20px;">
                    <i class="fa fa-shopping-cart fa-4x text-muted mb-4"></i>
                    <h3>Keranjang Anda Kosong</h3>
                    <p class="text-muted mb-4">Belum ada mobil yang ditambahkan ke keranjang.</p>
                    <a href="{{ route('cars') }}" class="primary-btn">
                        <i class="fa fa-car"></i> Lihat Mobil Tersedia
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
<!-- Cart Section End -->
@endsection

