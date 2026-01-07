<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MASUK</title>
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
            margin-top: 40px;
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
        .btn-primary, .btn-success {
            background: linear-gradient(135deg, #dc3545, #ff6b81);
            border: none;
            font-weight: bold;
            transition: all 0.3s ease;
            border-radius: 10px;
            padding: 12px;
        }
        .btn-primary:hover, .btn-success:hover {
            background: white;
            color: #dc3545;
            transform: scale(1.05);
        }
        .form-footer {
            text-align: center;
            margin-top: 15px;
        }
        .form-footer p {
            margin: 5px 0;
            font-size: 0.9rem;
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
            <div class="card-header">Masuk</div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success" style="background: rgba(40, 167, 69, 0.2); color: #fff; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    {{ session('success') }}
                </div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.2); color: #fff; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif
                <form action="{{ route('login.store') }}" method="POST">
                @csrf
                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                    <div class="password-wrapper">
                        <input type="password" name="password" class="form-control" placeholder="Kata Sandi" required id="password">
                        <span class="toggle-password" onclick="togglePassword()">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Masuk</button>
                </form>
                <div class="form-footer">
                    <p>Belum punya akun? <a href="register">Daftar</a></p>
                    <p>Lupa Kata Sandi? <a href="reset">Reset</a></p>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
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