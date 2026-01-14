<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Garasi62</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{
            font-family:'Poppins',sans-serif;
            background:#000;
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:20px;
            position:relative;
            overflow-y:auto
        }
        body::before{
            content:'';
            position:fixed;
            width:100%;
            height:100%;
            background:radial-gradient(circle at 20% 50%,rgba(220,53,69,.15) 0,transparent 50%),
                        radial-gradient(circle at 80% 80%,rgba(220,53,69,.1) 0,transparent 50%);
            z-index:0;
            top:0;
            left:0
        }
        .container{
            max-width:500px;
            width:100%;
            position:relative;
            z-index:1;
            margin:40px auto
        }
        .logo-container{
            text-align:center;
            margin-bottom:30px
        }
        .logo-container img{
            max-width:180px;
            height:auto;
            filter:brightness(0) invert(1)
        }
        .card{
            background:rgba(20,20,20,.95);
            border-radius:16px;
            padding:40px 35px;
            box-shadow:0 10px 40px rgba(220,53,69,.2),0 0 0 1px rgba(220,53,69,.1);
            border:1px solid rgba(220,53,69,.2);
            will-change:transform;
            transform:translateZ(0)
        }
        .card-header{
            text-align:center;
            margin-bottom:30px
        }
        .card-header h1{
            font-size:1.75rem;
            font-weight:700;
            color:#fff;
            margin-bottom:8px
        }
        .card-header p{
            color:#999;
            font-size:.9rem
        }
        .input-group{
            position:relative;
            margin-bottom:18px
        }
        .input-icon{
            position:absolute;
            left:15px;
            top:50%;
            transform:translateY(-50%);
            color:#dc3545;
            font-size:1rem;
            z-index:100 !important;
            pointer-events:none;
            display:block !important;
            visibility:visible !important;
            opacity:1 !important
        }
        .input-group:focus-within .input-icon{
            color:#dc3545 !important;
            z-index:100 !important
        }
        .form-control,.form-select{
            width:100%;
            padding:14px 15px 14px 45px;
            border:2px solid #333;
            border-radius:10px;
            font-size:.95rem;
            color:#fff !important;
            background:#1a1a1a !important;
            transition:border-color .2s,box-shadow .2s;
            appearance:none;
            will-change:border-color;
            position:relative;
            z-index:1;
            -webkit-text-fill-color:#fff !important;
            -webkit-autofill,
            -webkit-autofill:hover,
            -webkit-autofill:focus{
                -webkit-text-fill-color:#fff !important;
                -webkit-box-shadow:0 0 0 1000px #1a1a1a inset !important;
                box-shadow:0 0 0 1000px #1a1a1a inset !important;
                transition:background-color 5000s ease-in-out 0s
            }
        }
        .form-select{
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23dc3545' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat:no-repeat;
            background-position:right 15px center;
            padding-right:40px
        }
        .form-control:focus,.form-select:focus{
            outline:none;
            border-color:#dc3545;
            background:#1f1f1f !important;
            box-shadow:0 0 0 3px rgba(220,53,69,.1);
            -webkit-text-fill-color:#fff !important;
            -webkit-box-shadow:0 0 0 1000px #1f1f1f inset,0 0 0 3px rgba(220,53,69,.1) !important;
            box-shadow:0 0 0 1000px #1f1f1f inset,0 0 0 3px rgba(220,53,69,.1) !important
        }
        .form-control::placeholder{
            color:#666
        }
        input[type="password"]{
            color:#fff !important;
            -webkit-text-fill-color:#fff !important;
            font-family:'Poppins',sans-serif
        }
        .form-select option{
            background:#1a1a1a;
            color:#fff;
            padding:10px
        }
        .password-wrapper{
            position:relative
        }
        .password-wrapper .form-control{
            padding-right:50px
        }
        .toggle-password{
            position:absolute;
            right:15px;
            top:50%;
            transform:translateY(-50%);
            cursor:pointer;
            color:#999;
            font-size:1.1rem;
            transition:color .2s;
            z-index:100 !important;
            pointer-events:auto;
            display:block !important;
            visibility:visible !important;
            opacity:1 !important
        }
        .toggle-password:hover{
            color:#dc3545
        }
        .password-strength{
            margin-top:8px;
            height:4px;
            background:#333;
            border-radius:2px;
            overflow:hidden;
            display:none
        }
        .password-strength.active{
            display:block
        }
        .password-strength-bar{
            height:100%;
            width:0;
            transition:width .2s;
            border-radius:2px
        }
        .password-strength-bar.weak{
            width:33%;
            background:#dc3545
        }
        .password-strength-bar.medium{
            width:66%;
            background:#ff6b81
        }
        .password-strength-bar.strong{
            width:100%;
            background:#4ade80
        }
        .password-hint{
            font-size:.8rem;
            color:#999;
            margin-top:5px;
            display:none
        }
        .password-hint.active{
            display:block
        }
        .match-indicator{
            font-size:.85rem;
            margin-top:5px;
            display:none
        }
        .match-indicator.active{
            display:block
        }
        .match-indicator.match{
            color:#4ade80
        }
        .match-indicator.no-match{
            color:#dc3545
        }
        .btn-submit{
            width:100%;
            padding:14px;
            background:linear-gradient(135deg,#dc3545 0%,#c82333 100%);
            border:none;
            border-radius:10px;
            color:#fff;
            font-weight:600;
            font-size:1rem;
            cursor:pointer;
            transition:transform .2s,box-shadow .2s;
            position:relative;
            overflow:hidden;
            margin-top:10px;
            will-change:transform
        }
        .btn-submit:hover{
            transform:translateY(-2px);
            box-shadow:0 8px 20px rgba(220,53,69,.4)
        }
        .btn-submit:active{
            transform:translateY(0)
        }
        .btn-submit:disabled{
            opacity:.7;
            cursor:not-allowed
        }
        .form-footer{
            text-align:center;
            margin-top:25px;
            padding-top:25px;
            border-top:1px solid #333
        }
        .form-footer p{
            color:#999;
            font-size:.9rem
        }
        .form-footer a{
            color:#dc3545;
            text-decoration:none;
            font-weight:600;
            transition:color .2s
        }
        .form-footer a:hover{
            color:#ff4757;
            text-decoration:underline
        }
        .alert{
            padding:12px 16px;
            border-radius:10px;
            margin-bottom:20px;
            font-size:.9rem
        }
        .alert-danger{
            background:rgba(220,53,69,.15);
            color:#ff6b81;
            border:1px solid rgba(220,53,69,.3)
        }
        .loading-spinner{
            display:none;
            width:18px;
            height:18px;
            border:2px solid rgba(255,255,255,.3);
            border-top-color:#fff;
            border-radius:50%;
            animation:spin .6s linear infinite;
            margin-right:10px;
            vertical-align:middle
        }
        @keyframes spin{
            to{transform:rotate(360deg)}
        }
        .btn-submit.loading .loading-spinner{
            display:inline-block
        }
        @media (max-width:576px){
            .card{padding:30px 25px}
            .card-header h1{font-size:1.5rem}
            .logo-container img{max-width:150px}
        }
        /* Fade In Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .container {
            animation: fadeIn 0.6s ease-out;
        }
        .card {
            animation: fadeIn 0.8s ease-out;
        }
    </style>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="{{ asset('img/logo.svg') }}" alt="Garasi62 Logo" loading="eager">
        </div>
        <div class="card">
            <div class="card-header">
                <h1><i class="fas fa-user-plus me-2"></i>Daftar</h1>
                <p>Buat akun baru untuk memulai</p>
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif
                <form action="{{ route('register.store') }}" method="POST" id="registerForm">
                    @csrf
                    
                    <div class="input-group">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" 
                               name="name" 
                               class="form-control" 
                               placeholder="Nama Lengkap" 
                               value="{{ old('name') }}" 
                               required 
                               autocomplete="name"
                               autofocus>
                    </div>
                    
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" 
                               name="email" 
                               class="form-control" 
                               placeholder="Alamat Email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email">
                    </div>
                    
                    <div class="input-group">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="tel" 
                               name="phone" 
                               class="form-control" 
                               placeholder="Nomor Telepon" 
                               value="{{ old('phone') }}" 
                               required 
                               autocomplete="tel">
                    </div>
                    
                    <div class="input-group">
                        <i class="fas fa-venus-mars input-icon"></i>
                        <select name="gender" class="form-select" required>
                            <option value="" disabled selected>Jenis Kelamin</option>
                            <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <i class="fas fa-map-marker-alt input-icon"></i>
                        <input type="text" 
                               name="city" 
                               class="form-control" 
                               placeholder="Kota" 
                               value="{{ old('city') }}" 
                               required>
                    </div>
                    
                    <div class="input-group">
                        <i class="fas fa-building input-icon"></i>
                        <select name="institution" class="form-select" required>
                            <option value="" disabled selected>Institusi</option>
                            <option value="Perorangan" {{ old('institution') == 'Perorangan' ? 'selected' : '' }}>Perorangan</option>
                            <option value="Dealer" {{ old('institution') == 'Dealer' ? 'selected' : '' }}>Dealer</option>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <i class="fas fa-user-tag input-icon"></i>
                        <select name="role" class="form-select" required>
                            <option value="" disabled selected>Tipe Akun</option>
                            <option value="buyer" {{ old('role') == 'buyer' ? 'selected' : '' }}>Buyer (Pembeli)</option>
                            <option value="seller" {{ old('role') == 'seller' ? 'selected' : '' }}>Seller (Penjual)</option>
                        </select>
                        <small class="text-muted" style="display: block; margin-top: 5px; font-size: 12px; color: #999;">
                            <i class="fas fa-info-circle"></i> Pilih tipe akun sesuai kebutuhan Anda
                        </small>
                    </div>
                    
                    <div class="input-group password-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" 
                               name="password" 
                               class="form-control" 
                               placeholder="Kata Sandi" 
                               required 
                               id="password"
                               autocomplete="new-password">
                        <span class="toggle-password" onclick="togglePassword('password')">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                        <div class="password-strength" id="passwordStrength">
                            <div class="password-strength-bar" id="passwordStrengthBar"></div>
                        </div>
                        <div class="password-hint" id="passwordHint">
                            Minimal 8 karakter, kombinasi huruf dan angka
                        </div>
                    </div>
                    
                    <div class="input-group password-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" 
                               name="password_confirmation" 
                               class="form-control" 
                               placeholder="Konfirmasi Kata Sandi" 
                               required 
                               id="password_confirmation"
                               autocomplete="new-password">
                        <span class="toggle-password" onclick="togglePassword('password_confirmation')">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                        <div class="match-indicator" id="matchIndicator">
                            <i class="fas fa-check-circle"></i> Kata sandi cocok
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span class="loading-spinner"></span>
                        <span class="btn-text">Daftar</span>
                    </button>
                </form>
                
                <div class="form-footer">
                    <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword(inputId){
            const p=document.getElementById(inputId);
            const i=event.target.closest('.toggle-password').querySelector('i');
            if(p.type==='password'){
                p.type='text';
                i.classList.remove('fa-eye');
                i.classList.add('fa-eye-slash')
            }else{
                p.type='password';
                i.classList.remove('fa-eye-slash');
                i.classList.add('fa-eye')
            }
        }
        const passwordInput=document.getElementById('password');
        const passwordStrength=document.getElementById('passwordStrength');
        const passwordStrengthBar=document.getElementById('passwordStrengthBar');
        const passwordHint=document.getElementById('passwordHint');
        passwordInput.addEventListener('input',function(){
            const p=this.value;
            if(p.length>0){
                passwordStrength.classList.add('active');
                passwordHint.classList.add('active');
                let s=0;
                if(p.length>=8)s++;
                if(p.match(/[a-z]/)&&p.match(/[A-Z]/))s++;
                if(p.match(/\d/))s++;
                if(p.match(/[^a-zA-Z\d]/))s++;
                passwordStrengthBar.className='password-strength-bar';
                if(s<=1)passwordStrengthBar.classList.add('weak');
                else if(s<=2)passwordStrengthBar.classList.add('medium');
                else passwordStrengthBar.classList.add('strong')
            }else{
                passwordStrength.classList.remove('active');
                passwordHint.classList.remove('active')
            }
        });
        const passwordConfirm=document.getElementById('password_confirmation');
        const matchIndicator=document.getElementById('matchIndicator');
        passwordConfirm.addEventListener('input',function(){
            const p=passwordInput.value;
            const c=this.value;
            if(c.length>0){
                matchIndicator.classList.add('active');
                if(p===c){
                    matchIndicator.className='match-indicator active match';
                    matchIndicator.innerHTML='<i class="fas fa-check-circle"></i> Kata sandi cocok'
                }else{
                    matchIndicator.className='match-indicator active no-match';
                    matchIndicator.innerHTML='<i class="fas fa-times-circle"></i> Kata sandi tidak cocok'
                }
            }else{
                matchIndicator.classList.remove('active')
            }
        });
        document.getElementById('registerForm').addEventListener('submit',function(e){
            const b=document.getElementById('submitBtn');
            b.classList.add('loading');
            b.disabled=true;
            b.querySelector('.btn-text').textContent='Mendaftar...'
        });
        const emailInput=document.querySelector('input[name="email"]');
        const phoneInput=document.querySelector('input[name="phone"]');
        emailInput.addEventListener('blur',function(){
            const e=this.value.trim();
            this.style.borderColor=e&&!e.includes('@')?'#dc3545':'#333'
        });
        phoneInput.addEventListener('input',function(){
            this.value=this.value.replace(/[^0-9+]/g,'')
        });
        
        // SweetAlert untuk success/error
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Registrasi Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            }).then(() => {
                window.location.href = '{{ route('login') }}';
            });
        @endif
        
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Registrasi Gagal',
                html: '<ul style="text-align: left; padding-left: 20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
</body>
</html>

