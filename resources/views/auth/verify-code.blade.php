<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Garasi62</title>
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
        .info-box{
            background:rgba(220,53,69,.1);
            border:1px solid rgba(220,53,69,.3);
            border-radius: 5px;
            padding:15px;
            margin-bottom:25px;
            text-align:center
        }
        .info-box i{
            color:#dc3545;
            font-size:2rem;
            margin-bottom:10px
        }
        .info-box p{
            color:#fff;
            font-size:.9rem;
            margin:0
        }
        .info-box .email{
            color:#dc3545;
            font-weight:600;
            margin-top:5px
        }
        .input-group{
            position:relative;
            margin-bottom:18px;
            min-height:48px;
            display:flex;
            align-items:center
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
        .form-control{
            width:100%;
            padding:14px 15px 14px 45px;
            border:2px solid #333;
            border-radius: 5px;
            font-size:.95rem;
            color:#fff !important;
            background:#1a1a1a !important;
            transition:border-color .2s,box-shadow .2s;
            appearance:none;
            will-change:border-color;
            position:relative;
            z-index:1;
            -webkit-text-fill-color:#fff !important;
            text-align:center;
            letter-spacing:8px;
            font-size:1.5rem;
            font-weight:700;
            font-family:'Courier New',monospace
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
            color:#666;
            letter-spacing:2px
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
        .btn-resend{
            width:100%;
            padding:12px;
            background:transparent;
            border:2px solid #666;
            border-radius: 5px;
            color:#999;
            font-weight:600;
            font-size:.9rem;
            cursor:pointer;
            transition:all .2s;
            margin-top:10px
        }
        .btn-resend:hover{
            border-color:#dc3545;
            color:#dc3545
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
        .hint-text{
            color:#999;
            font-size:.85rem;
            text-align:center;
            margin-top:10px
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
                <h1><i class="fas fa-shield-alt me-2"></i>Verifikasi Email</h1>
                <p>Masukkan kode verifikasi yang dikirim ke email Anda</p>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <div class="info-box">
                    <i class="fas fa-envelope"></i>
                    <p>Kode verifikasi telah dikirim ke:</p>
                    <p class="email">{{ Session::get('pending_user.email') ?? 'Email Anda' }}</p>
                </div>

                <form action="{{ route('register.verify') }}" method="POST" id="verifyForm">
                    @csrf
                    
                    <div class="input-group">
                        <i class="fas fa-key input-icon"></i>
                        <input type="text" 
                               name="verification_code" 
                               class="form-control" 
                               placeholder="000000" 
                               required 
                               maxlength="6"
                               minlength="6"
                               pattern="[0-9]{6}"
                               id="verification_code"
                               autocomplete="off"
                               autofocus>
                    </div>
                    <p class="hint-text">
                        <i class="fas fa-info-circle me-2"></i>Kode berlaku selama 60 menit
                    </p>
                    
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span class="btn-text">Verifikasi</span>
                    </button>
                </form>

                <form action="{{ route('register.resend') }}" method="POST" style="margin-top: 10px;">
                    @csrf
                    <button type="submit" class="btn-resend">
                        <i class="fas fa-redo me-2"></i>Kirim Ulang Kode
                    </button>
                </form>
                
                <div class="form-footer">
                    <p>Belum menerima email? <a href="{{ route('register.resend') }}" onclick="event.preventDefault(); document.querySelector('form[action=\'{{ route('register.resend') }}\']').submit();">Kirim ulang</a></p>
                    <p><a href="{{ route('register') }}">Kembali ke halaman registrasi</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Auto focus dan format input
        const codeInput = document.getElementById('verification_code');
        
        codeInput.addEventListener('input', function(e) {
            // Hanya angka
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Auto submit jika sudah 6 digit
            if (this.value.length === 6) {
                // Optional: auto submit setelah 6 digit
                // document.getElementById('verifyForm').submit();
            }
        });

        // Handle form submit
        document.getElementById('verifyForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memverifikasi...';
        });

        // SweetAlert untuk success/error
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
</body>
</html>

