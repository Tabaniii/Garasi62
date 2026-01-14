@extends('template.temp')

<!-- @section('title', 'Home - GARASI62') Set the title for the page -->

@section('content')
    <!-- Breadcrumb End -->
    <div class="breadcrumb-option set-bg" data-setbg="img/breadcrumb-bg.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Contact Us</h2>
                        <div class="breadcrumb__links">
                            <a href="./index.html"><i class="fa fa-home"></i> Home</a>
                            <span>Contact Us</span>
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
                            <h2>Let’s Work Together</h2>
                            <p>To make requests for further information, contact us via our social channels.</p>
                        </div>
                        <ul>
                            <li><span>Weekday</span> 08:00 am to 18:00 pm</li>
                            <li><span>Saturday:</span> 10:00 am to 16:00 pm</li>
                            <li><span>Sunday:</span> Closed</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="contact__form">
                        @guest
                            <div class="alert alert-warning" style="margin-bottom: 20px; padding: 15px; background-color: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px; color: #856404;">
                                <i class="fa fa-exclamation-triangle"></i> 
                                <strong>Perhatian!</strong> Anda harus <a href="{{ route('login') }}" style="color: #dc3545; font-weight: bold; text-decoration: underline;">login terlebih dahulu</a> untuk mengirim pesan.
                            </div>
                        @endguest
                        
                        @auth
                            <div class="alert alert-info" style="margin-bottom: 20px; padding: 12px; background-color: #e7f3ff; border-left: 4px solid #2196F3; border-radius: 4px;">
                                <i class="fa fa-info-circle"></i> 
                                <strong>Email Anda:</strong> {{ auth()->user()->email }}
                            </div>
                        @endauth
                        
                        <form action="{{ route('contact.send') }}" method="POST" id="contactForm" @guest style="opacity: 0.6; pointer-events: none;" @endguest>
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="text" name="name" placeholder="Name" value="{{ old('name', auth()->user()->name ?? '') }}" required minlength="2" maxlength="255">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <input type="email" name="email" placeholder="Email" value="{{ old('email', auth()->user()->email ?? '') }}" disabled style="background-color: #f5f5f5; cursor: not-allowed;" title="Email diambil dari akun Anda yang sedang login">
                                    <small class="text-muted" style="display: block; margin-top: 5px; font-size: 12px;">
                                        <i class="fa fa-lock"></i> Email diambil dari akun Anda
                                    </small>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <input type="text" name="subject" placeholder="Subject" value="{{ old('subject') }}" required minlength="3" maxlength="255">
                            @error('subject')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <textarea name="message" placeholder="Your Question" required minlength="10" maxlength="5000">{{ old('message') }}</textarea>
                            @error('message')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            
                            <!-- Google reCAPTCHA -->
                            <div class="g-recaptcha-wrapper" style="margin: 15px 0;">
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                @error('g-recaptcha-response')
                                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <button type="submit" class="site-btn">Submit Now</button>
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
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="contact__address__item">
                            <h4>California Showroom</h4>
                            <p>625 Gloria Union, California, United Stated Colorlib.california@gmail.com</p>
                            <span>(+12) 456 678 9100</span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="contact__address__item">
                            <h4>New York Showroom</h4>
                            <p>8235 South Ave. Jamestown, NewYork Colorlib.Newyork@gmail.com</p>
                            <span>(+12) 456 678 9100</span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="contact__address__item">
                            <h4>Florida Showroom</h4>
                            <p>497 Beaver Ridge St. Daytona Beach, Florida Colorlib.california@gmail.com</p>
                            <span>(+12) 456 678 9100</span>
                        </div>
                    </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
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
