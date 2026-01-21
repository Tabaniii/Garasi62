<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi - Garasi62</title>
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
            overflow:hidden
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
            max-width:450px;
            width:100%;
            position:relative;
            z-index:1
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
            border-radius: 5px;
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
        .form-control{
            width:100%;
            padding:14px 15px 14px 45px;
            border:2px solid #333;
            border-radius: 5px;
            font-size:.95rem;
            color:#fff !important;
            background:#1a1a1a !important;
            transition:border-color .2s,box-shadow .2s;
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
        .form-control:focus{
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
        .form-control:read-only{
            background:#151515 !important;
            cursor:not-allowed;
            opacity:0.7;
            border-color:#444 !important;
        }
        .form-control:read-only:focus{
            border-color:#444 !important;
            box-shadow:none !important;
            background:#151515 !important;
        }
        input[type="password"]{
            color:#fff !important;
            -webkit-text-fill-color:#fff !important;
            font-family:'Poppins',sans-serif
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
        .btn-submit{
            width:100%;
            padding:14px;
            background:linear-gradient(135deg,#dc3545 0%,#c82333 100%);
            border:none;
            border-radius: 5px;
            color:#fff;
            font-weight:600;
            font-size:1rem;
            cursor:pointer;
            transition:transform .2s,box-shadow .2s;
            position:relative;
            overflow:hidden;
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
        .back-link{
            color:#dc3545;
            text-decoration:none;
            font-size:.95rem;
            display:inline-flex;
            align-items:center;
            transition:color .2s,transform .2s;
            margin-bottom:20px;
            padding:10px 15px;
            border-radius: 5px;
            background:rgba(220,53,69,.1);
            border:1px solid rgba(220,53,69,.2);
            width:fit-content
        }
        .back-link:hover{
            color:#ff4757;
            transform:translateX(-3px);
            background:rgba(220,53,69,.15);
            text-decoration:none
        }
        .back-link i{
            margin-right:8px;
            transition:transform .2s
        }
        .back-link:hover i{
            transform:translateX(-2px)
        }
        .form-footer{
            text-align:center;
            margin-top:25px;
            padding-top:25px;
            border-top:1px solid #333
        }
        .form-footer p{
            color:#999;
            font-size:.9rem;
            margin-bottom:8px
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
            border-radius: 5px;
            margin-bottom:20px;
            font-size:.9rem
        }
        .alert-success{
            background:rgba(40,167,69,.15);
            color:#4ade80;
            border:1px solid rgba(40,167,69,.3)
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
        <a href="{{ route('login') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Login
        </a>
        <div class="card">
            <div class="card-header">
                <h1><i class="fas fa-lock me-2"></i>Reset Kata Sandi</h1>
                <p>Masukkan password baru Anda</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               placeholder="Alamat Email" 
                               value="{{ $email ?? old('email') }}" 
                               required 
                               readonly
                               autocomplete="email"
                               title="Email tidak dapat diubah">
                    </div>
                    @error('email')
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                        </div>
                    @enderror

                    <div class="input-group password-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" 
                               name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Password Baru" 
                               required 
                               id="password"
                               autocomplete="new-password">
                        <span class="toggle-password" onclick="togglePassword('password')">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                        </div>
                    @enderror

                    <div class="input-group password-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" 
                               name="password_confirmation" 
                               class="form-control" 
                               placeholder="Konfirmasi Password Baru" 
                               required 
                               id="password_confirmation"
                               autocomplete="new-password">
                        <span class="toggle-password" onclick="togglePassword('password_confirmation')">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                    </div>
                    
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span class="loading-spinner"></span>
                        <span class="btn-text">Reset Password</span>
                    </button>
                </form>
                
                <div class="form-footer">
                    <p>Ingat password Anda? <a href="{{ route('login') }}">Masuk</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword(fieldId){
            const p=document.getElementById(fieldId);
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
        
        document.getElementById('resetPasswordForm').addEventListener('submit',function(e){
            const b=document.getElementById('submitBtn');
            b.classList.add('loading');
            b.disabled=true;
            b.querySelector('.btn-text').textContent='Memproses...'
        });
        
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        
        passwordConfirmInput.addEventListener('blur', function() {
            if (passwordInput.value !== this.value) {
                this.style.borderColor = '#f56565';
            } else {
                this.style.borderColor = '#333';
            }
        });
        
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal Reset Password',
                html: '<ul style="text-align: left; padding-left: 20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
</body>
</html>
