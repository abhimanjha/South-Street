<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SouthStreet</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body>

    <div class="auth-wrapper register-active">
        <!-- LEFT PANEL (FORM) -->
        <div class="auth-left">
            <img src="{{ asset('imgs/logo.png') }}" alt="SouthStreet Logo" class="auth-logo">
            <!-- REGISTER FORM -->
            <div class="form-panel register-panel">
                <h2 class="text-center mb-4">Sign Up</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               required>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        Create Account
                    </button>

                    <p class="switch-text text-center mb-0">
                        Already have an account? <a href="{{ route('login') }}" onclick="switchToLogin()">Sign in</a>
                    </p>
                </form>
            </div>
        </div>

        <!-- RIGHT PANEL (TEXT/IMAGE) -->
        <div class="auth-right">
            <div class="auth-content">
                <h1 class="auth-title">Join Us</h1>
                <p class="auth-text">Create your account to start shopping and managing your orders.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
