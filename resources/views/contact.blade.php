@extends('template.temp')

@section('meta')
    <meta name="description"
        content="Hubungi Garasi62 - Kami siap membantu Anda. Silakan hubungi kami untuk informasi lebih lanjut.">
    <meta name="keywords" content="Kontak Garasi62, Alamat Garasi62, Telepon Garasi62">

    <!-- Open Graph -->
    <meta property="og:title" content="Hubungi Kami - Garasi62">
    <meta property="og:description"
        content="Hubungi Garasi62 - Kami siap membantu Anda. Silakan hubungi kami untuk informasi lebih lanjut.">
    <meta property="og:image" content="{{ asset('garasi62/img/logo.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="GARASI62">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Hubungi Kami - Garasi62">
    <meta name="twitter:description" content="Hubungi Garasi62 - Kami siap membantu Anda.">
    <meta name="twitter:image" content="{{ asset('garasi62/img/logo.png') }}">
@endsection

@include('components.messages-widget')
@section('content')
    @php
        $siteEmail = \App\Models\SiteSetting::get('site_email', \App\Models\SiteSetting::get('footer_email', 'Colorlib@gmail.com'));

        $operationalHoursData = \App\Models\SiteSetting::get('operational_hours', '');
        $operationalHours = empty($operationalHoursData) ? [
            ['day' => 'Hari Kerja', 'hours' => '08:00 - 18:00'],
            ['day' => 'Sabtu', 'hours' => '10:00 - 16:00'],
            ['day' => 'Minggu', 'hours' => 'Tutup'],
        ] : (json_decode($operationalHoursData, true) ?? []);

        $showroomsData = \App\Models\SiteSetting::get('showrooms', '');
        $showrooms = empty($showroomsData) ? [
            [
                'title' => 'California Showroom',
                'address' => '625 Gloria Union, California, United Stated',
                'phone' => '(+12) 456 678 9100'
            ],
            [
                'title' => 'New York Showroom',
                'address' => '8235 South Ave. Jamestown, NewYork',
                'phone' => '(+12) 456 678 9100'
            ],
            [
                'title' => 'Florida Showroom',
                'address' => '497 Beaver Ridge St. Daytona Beach, Florida',
                'phone' => '(+12) 456 678 9100'
            ]
        ] : (json_decode($showroomsData, true) ?? []);
    @endphp
    <!-- Breadcrumb End -->
    <div class="breadcrumb-option set-bg" data-setbg="img/breadcrumb-bg.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Hubungi Kami</h2>
                        <div class="breadcrumb__links">
                            <a href="/"><i class="fa fa-home"></i> Beranda</a>
                            <span>Hubungi Kami</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Begin -->

    <!-- Contact Section Begin -->
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="contact__text">
                        <div class="section-title">
                            <h2>Mari Bekerja Sama</h2>
                            <p>Untuk informasi lebih lanjut, silakan hubungi kami melalui saluran sosial kami atau formulir
                                ini.</p>
                        </div>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 40px; height: 40px; background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">Jam Operasional</div>
                                        <div class="text-muted" style="font-size: 13px;">Info waktu layanan showroom</div>
                                    </div>
                                </div>
                                <ul class="list-unstyled mb-0">
                                    @foreach($operationalHours as $hour)
                                        <li
                                            class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                            <span class="text-muted fw-semibold"
                                                style="flex: 0 0 auto; min-width: 80px;">{{ $hour['day'] }}</span>
                                            <span class="fw-bold text-end"
                                                style="flex: 1; white-space: nowrap; margin-left: 15px;">{{ $hour['hours'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="contact__form">
                        @guest
                            <div class="alert alert-warning"
                                style="margin-bottom: 20px; padding: 15px; background-color: #fff3cd; border-left: 4px solid #ffc107; border-radius: 5px; color: #856404;">
                                <i class="fa fa-exclamation-triangle"></i>
                                <strong>Perhatian!</strong> Anda harus <a href="{{ route('login') }}"
                                    style="color: #dc3545; font-weight: bold; text-decoration: underline;">login terlebih
                                    dahulu</a> untuk mengirim pesan.
                            </div>
                        @endguest

                        @auth
                            <div class="alert alert-info"
                                style="margin-bottom: 20px; padding: 12px; background-color: #e7f3ff; border-left: 4px solid #2196F3; border-radius: 5px;">
                                <i class="fa fa-info-circle"></i>
                                <strong>Email Anda:</strong> {{ auth()->user()->email }}
                            </div>
                        @endauth

                        <form action="{{ route('contact.send') }}" method="POST" id="contactForm" @guest
                        style="opacity: 0.6; pointer-events: none;" @endguest>
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="text" name="name" placeholder="Nama"
                                        value="{{ old('name', auth()->user()->name ?? '') }}" required minlength="2"
                                        maxlength="255">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <input type="email" name="email" placeholder="Email"
                                        value="{{ old('email', auth()->user()->email ?? '') }}" disabled
                                        style="background-color: #f5f5f5; cursor: not-allowed;"
                                        title="Email diambil dari akun Anda yang sedang login">
                                    <small class="text-muted" style="display: block; margin-top: 5px; font-size: 12px;">
                                        <i class="fa fa-lock"></i> Email diambil dari akun Anda
                                    </small>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <input type="text" name="subject" placeholder="Subjek" value="{{ old('subject') }}" required
                                minlength="3" maxlength="255">
                            @error('subject')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <textarea name="message" placeholder="Pertanyaan Anda" required minlength="10"
                                maxlength="5000">{{ old('message') }}</textarea>
                            @error('message')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                            <!-- Google reCAPTCHA -->
                            <div class="g-recaptcha-wrapper" style="margin: 15px 0;">
                                <div class="g-recaptcha" data-sitekey="{{ recaptcha_site_key() }}"></div>
                                @error('g-recaptcha-response')
                                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="site-btn">Kirim Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->

    <!-- Contact Address Begin -->
    <div class="contact-address">
        <div class="container">
            <div class="contact__address__text">
                <div class="row g-4">
                    @foreach($showrooms as $showroom)
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="contact__address__item h-100 p-4 border rounded shadow-sm bg-white">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 38px; height: 38px; background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <h4 class="mb-0">{{ $showroom['title'] }}</h4>
                                </div>
                                <div class="text-muted mb-2">{{ $showroom['address'] }}</div>
                                <div class="d-flex align-items-center mb-2" style="color: #dc3545;">
                                    <i class="fa fa-envelope-o me-2"></i>
                                    <span>{{ $siteEmail }}</span>
                                </div>
                                <div class="d-flex align-items-center fw-bold" style="color: #111111;">
                                    <i class="fa fa-phone me-2" style="color: #dc3545;"></i>
                                    <span>{{ $showroom['phone'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Address End -->

    @push('scripts')
        <!-- Google reCAPTCHA Script -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <script>
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK'
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: '<ul style="text-align: left; padding-left: 20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK'
                });
            @endif

            // Handle form submission dengan loading
            document.addEventListener('DOMContentLoaded', function () {
                const contactForm = document.getElementById('contactForm');
                if (contactForm) {
                    contactForm.addEventListener('submit', function (e) {
                        const form = this;
                        const submitBtn = form.querySelector('button[type="submit"]');

                        // Validasi client-side
                        const name = form.querySelector('input[name="name"]').value.trim();
                        const email = form.querySelector('input[name="email"]').value.trim();
                        const subject = form.querySelector('input[name="subject"]').value.trim();
                        const message = form.querySelector('textarea[name="message"]').value.trim();
                        const recaptchaResponse = form.querySelector('[name="g-recaptcha-response"]')?.value;

                        // Validasi required fields
                        if (!name || !email || !subject || !message) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'warning',
                                title: 'Perhatian!',
                                text: 'Mohon lengkapi semua field yang wajib diisi.',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'OK'
                            });
                            return false;
                        }

                        // Validasi panjang karakter
                        if (name.length < 2) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'warning',
                                title: 'Nama Terlalu Pendek!',
                                text: 'Nama minimal 2 karakter.',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'OK'
                            });
                            return false;
                        }

                        if (subject.length < 3) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'warning',
                                title: 'Subject Terlalu Pendek!',
                                text: 'Subject minimal 3 karakter.',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'OK'
                            });
                            return false;
                        }

                        if (message.length < 10) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'warning',
                                title: 'Pesan Terlalu Pendek!',
                                text: 'Pesan minimal 10 karakter.',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'OK'
                            });
                            return false;
                        }

                        // Validasi email format
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(email)) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'warning',
                                title: 'Email Tidak Valid!',
                                text: 'Mohon masukkan alamat email yang valid.',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'OK'
                            });
                            return false;
                        }

                        // Validasi reCAPTCHA
                        if (!recaptchaResponse) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'warning',
                                title: 'Verifikasi Diperlukan!',
                                text: 'Mohon verifikasi bahwa Anda bukan robot.',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'OK'
                            });
                            return false;
                        }

                        // Show loading
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mengirim...';
                    });
                }
            });
        </script>
    @endpush

@endsection