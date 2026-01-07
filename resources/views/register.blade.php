<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAFTAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #121212;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            margin-top: 120px;
        }
        .card {
            background: rgba(40, 40, 40, 0.9);
            border-radius: 15px;
            padding: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            margin-bottom: 20px;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.6);
        }
        .card-header {
            text-align: center;
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
            padding-bottom: 1px;
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
            margin-bottom: 15px;
        }
        .form-control::placeholder {
            color: #aaa;
        }
        .form-control:focus, .form-select:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(220, 53, 69, 0.6);
            background-color: rgba(255, 255, 255, 0.2);
        }
        .form-select option {
            background-color: #282828;
            color: white;
        }
        .password-wrapper {
            position: relative;
        }
        .password-wrapper .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
        }
        .btn-primary {
            background: linear-gradient(135deg, #dc3545, #ff6b81);
            border: none;
            font-weight: bold;
            transition: all 0.3s ease;
            border-radius: 10px;
            padding: 12px;
        }
        .btn-primary:hover {
            background: white;
            color: #dc3545;
            transform: scale(1.05);
        }
        .form-control::placeholder {
            color: white;
        }
        .form-footer {
            text-align: center;
            margin-top: 10px;
        }
        .form-footer p {
            color: white;
        }
        .form-footer a {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
        }
        .form-footer a:hover {
            text-decoration: underline;
            color: #ff6b81;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
        <div class="card-header">Daftar</div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.2); color: #fff; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif
                <form action="{{ route('register.store') }}" method="POST">
                    @csrf
                    <input type="text" name="name" class="form-control" placeholder="Nama" value="{{ old('name') }}" required>
                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                    <input type="text" name="phone" class="form-control" placeholder="Nomor Telepon" value="{{ old('phone') }}" required>
                    <select name="gender" class="form-select" required>
                        <option value="" disabled selected>Jenis Kelamin</option>
                        <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    </select>
                    <input type="text" name="city" class="form-control" placeholder="Kota" value="{{ old('city') }}" required>
                    <select name="institution" class="form-select" required>
                        <option value="" disabled selected>Institusi</option>
                        <option value="Perorangan" {{ old('institution') == 'Perorangan' ? 'selected' : '' }}>Perorangan</option>
                        <option value="Dealer" {{ old('institution') == 'Dealer' ? 'selected' : '' }}>Dealer</option>
                    </select>
                    <div class="password-wrapper">
                        <input type="password" name="password" class="form-control" placeholder="Kata Sandi" required id="password">
                        <span class="toggle-password" onclick="togglePassword('password')">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                    </div>
                    <div class="password-wrapper">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Kata Sandi" required id="password_confirmation">
                        <span class="toggle-password" onclick="togglePassword('password_confirmation')">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Daftar</button>
                </form>
                <div class="form-footer">
                    <p>Sudah punya akun? <a href="login">Masuk</a></p>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = event.target.closest('.toggle-password').querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>