<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Reset Access</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto:wght@300;400&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-glow: #00ddeb;
            --secondary-glow: #ff00aa;
            --bg-gradient: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            --glass: rgba(255, 255, 255, 0.1);
            --border-glass: rgba(255, 255, 255, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            color: white;
        }

        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            background: var(--primary-glow);
            border-radius: 50%;
            opacity: 0.6;
            animation: float 15s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.6; }
            90% { opacity: 0.6; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        .login-card {
            background: var(--glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            padding: 40px 30px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 221, 235, 0.2);
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: conic-gradient(from 0deg, #00ddeb, #ff00aa, #00ddeb);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Orbitron', sans-serif;
            font-size: 32px;
            color: white;
            animation: pulse 2s infinite;
            box-shadow: 0 0 20px rgba(0, 221, 235, 0.6);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        h2 {
            font-family: 'Orbitron', sans-serif;
            text-align: center;
            margin-bottom: 10px;
            background: linear-gradient(90deg, #00ddeb, #ff00aa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }

        p.subtitle {
            text-align: center;
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 30px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary-glow);
            box-shadow: 0 0 15px rgba(0, 221, 235, 0.5);
            color: white;
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: var(--primary-glow);
        }

        .btn-login {
            background: linear-gradient(45deg, #00ddeb, #ff00aa);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 0, 170, 0.4);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .extra-links {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            margin-top: 20px;
        }

        .extra-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s;
        }

        .extra-links a:hover {
            color: var(--primary-glow);
        }

        .tilt-container {
            perspective: 1000px;
        }

        .tilt-card {
            transition: transform 0.1s;
        }
    </style>
</head>
<body>

    <!-- Floating Particles -->
    <div class="particles" id="particles"></div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="tilt-container">
                    <div class="login-card tilt-card" id="tiltCard">
                        <div class="logo">∞</div>
                        <h2>Forgot Password</h2>
                        <p class="subtitle">Enter your email to reset your password</p>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                           placeholder="Email Address" value="{{ old('email') }}" required autofocus>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-login w-100 text-white">
                                Send Reset Link
                            </button>

                            <div class="extra-links">
                                <a href="{{ route('login') }}">Back to Login</a>
                                <a href="#">Help?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Floating Particles
        const particlesContainer = document.getElementById('particles');
        const particleCount = 50;

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            const size = Math.random() * 6 + 2;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.animationDelay = `${Math.random() * 15}s`;
            particle.style.animationDuration = `${Math.random() * 10 + 10}s`;
            particlesContainer.appendChild(particle);
        }

        // 3D Tilt Effect
        const tiltCard = document.getElementById('tiltCard');
        const tiltContainer = document.querySelector('.tilt-container');

        tiltContainer.addEventListener('mousemove', (e) => {
            const rect = tiltContainer.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateY = (x - centerX) / centerX * 15;
            const rotateX = (centerY - y) / centerY * 15;

            tiltCard.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(20px)`;
        });

        tiltContainer.addEventListener('mouseleave', () => {
            tiltCard.style.transform = 'rotateX(0) rotateY(0) translateZ(0)';
        });
    </script>
</body>
</html>
