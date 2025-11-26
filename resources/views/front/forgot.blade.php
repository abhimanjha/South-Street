<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | SouthStreet</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body>

    <div class="auth-wrapper login-active">
        <!-- LEFT PANEL -->
        <div class="auth-left">
            <img src="{{ asset('imgs/logo.png') }}" alt="SouthStreet Logo" class="auth-logo">
            <h1 class="auth-title">Reset Password</h1>
            <p class="auth-text">Enter your email address and we'll send you a link to reset your password.</p>
        </div>

        <!-- RIGHT PANEL -->
        <div class="auth-right">
            <!-- FORGOT PASSWORD FORM -->
            <div class="form-panel login-panel">
                <h2 class="text-center mb-4">Forgot Password</h2>

                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        Send Reset Link
                    </button>

                    <p class="switch-text text-center mb-0">
                        Remember your password? <a href="{{ route('login') }}">Sign in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
