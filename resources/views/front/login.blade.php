<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SouthStreet</title>

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
        <div class="auth-left login-content">
            <img src="{{ asset('imgs/logo.png') }}" alt="SouthStreet Logo" class="auth-logo">
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-text">Sign in to your account to continue shopping and managing your orders.</p>
        </div>
        <div class="auth-left register-content" style="display: none;">
            <img src="{{ asset('imgs/logo.png') }}" alt="SouthStreet Logo" class="auth-logo">
            <h1 class="auth-title">Join SouthStreet</h1>
            <p class="auth-text">Create your account to start shopping and enjoy personalized experiences.</p>
        </div>

        <!-- RIGHT PANEL -->
        <div class="auth-right">
            <!-- LOGIN FORM -->
            <div class="form-panel login-panel">
                <h2 class="text-center mb-4">Sign In</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus>
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

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        Sign In
                    </button>

                    <p class="switch-text text-center mb-0">
                        Don't have an account? <a href="#" onclick="switchToRegister(event)">Sign up</a>
                    </p>
                </form>
            </div>

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
                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
                               required>
                        @error('password_confirmation')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        Sign Up
                    </button>

                    <p class="switch-text text-center mb-0">
                        Already have an account? <a href="#" onclick="switchToLogin(event)">Sign in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function switchToRegister(event) {
            event.preventDefault();
            const wrapper = document.querySelector('.auth-wrapper');
            const loginContent = document.querySelector('.login-content');
            const registerContent = document.querySelector('.register-content');
            const loginPanel = document.querySelector('.login-panel');
            const registerPanel = document.querySelector('.register-panel');

            // Fade out login form to the right
            loginPanel.style.left = '120%';
            loginPanel.style.opacity = '0';
            setTimeout(() => {
                loginPanel.style.display = 'none';
            }, 300);

            // Fade in register form from the right
            setTimeout(() => {
                registerPanel.style.display = 'block';
                registerPanel.style.left = '50%';
                registerPanel.style.opacity = '1';
            }, 300);

            // Change left panel content instantly (no fade)
            loginContent.style.display = 'none';
            registerContent.style.display = 'block';

            // Update wrapper class
            wrapper.classList.remove('login-active');
            wrapper.classList.add('register-active');
        }

        function switchToLogin(event) {
            event.preventDefault();
            const wrapper = document.querySelector('.auth-wrapper');
            const loginContent = document.querySelector('.login-content');
            const registerContent = document.querySelector('.register-content');
            const loginPanel = document.querySelector('.login-panel');
            const registerPanel = document.querySelector('.register-panel');

            // Fade out register form to the right
            registerPanel.style.left = '120%';
            registerPanel.style.opacity = '0';
            setTimeout(() => {
                registerPanel.style.display = 'none';
            }, 300);

            // Fade in login form from the right
            setTimeout(() => {
                loginPanel.style.display = 'block';
                loginPanel.style.left = '50%';
                loginPanel.style.opacity = '1';
            }, 300);

            // Change left panel content instantly (no fade)
            registerContent.style.display = 'none';
            loginContent.style.display = 'block';

            // Update wrapper class
            wrapper.classList.remove('register-active');
            wrapper.classList.add('login-active');
        }
    </script>
</body>
</html>
