<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kemenhan Pusdatin</title>
    <meta name="description" content="Login Sistem Inventaris Kementerian Pertahanan Pusat Data dan Informasi">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* ===== RESET & BASE ===== */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #1a1210;
            color: #ffffff;
            overflow: hidden;
        }

        /* ===== MAIN CONTAINER ===== */
        .login-container {
            display: flex;
            align-items: stretch;
            width: 100%;
            height: 100vh;
            padding: 20px;
            gap: 20px;
        }

        /* ===== LEFT HERO CARD ===== */
        .hero-card {
            position: relative;
            flex: 1;
            min-width: 0;
            border-radius: 20px;
            overflow: hidden;
        }

        .hero-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 32px 28px;
            background: linear-gradient(
                to top,
                rgba(0, 0, 0, 0.85) 0%,
                rgba(0, 0, 0, 0.55) 40%,
                rgba(0, 0, 0, 0) 100%
            );
        }

        .hero-title {
            font-size: 36px;
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: 2px;
            color: #ffffff;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .hero-subtitle {
            font-size: 14px;
            font-weight: 400;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.78);
            max-width: 320px;
        }

        /* ===== RIGHT LOGIN FORM ===== */
        .login-form-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            min-width: 0;
        }

        .logo-wrapper {
            margin-bottom: 36px;
        }

        .logo-wrapper img {
            width: 120px;
            height: 120px;
            object-fit: contain;
            filter: drop-shadow(0 4px 16px rgba(180, 120, 30, 0.35));
        }

        /* ===== FORM ===== */
        .login-form {
            width: 100%;
            max-width: 340px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-field {
            position: relative;
        }

        .form-field input,
        .form-field select {
            width: 100%;
            padding: 13px 16px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 400;
            color: #333333;
            background-color: #ffffff;
            border: none;
            border-radius: 6px;
            outline: none;
            transition: box-shadow 0.25s ease;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .form-field input::placeholder {
            color: #999999;
            font-weight: 400;
        }

        .form-field input:focus,
        .form-field select:focus {
            box-shadow: 0 0 0 2px rgba(139, 30, 30, 0.45);
        }

        /* Custom select arrow */
        .select-wrapper {
            position: relative;
        }

        .select-wrapper::after {
            content: '';
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 6px solid #666666;
            pointer-events: none;
        }

        .select-wrapper select {
            padding-right: 40px;
            cursor: pointer;
            color: #999999;
        }

        .select-wrapper select.has-value {
            color: #333333;
        }

        /* ===== SUBMIT BUTTON ===== */
        .submit-btn {
            margin-top: 8px;
            width: 180px;
            padding: 12px 24px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.3px;
            color: #ffffff;
            background-color: #5c1a1a;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            align-self: center;
        }

        .submit-btn:hover {
            background-color: #7a2323;
            box-shadow: 0 4px 16px rgba(122, 35, 35, 0.45);
            transform: translateY(-1px);
        }

        .submit-btn:active {
            transform: translateY(0);
            background-color: #4a1515;
        }

        /* ===== ERROR MESSAGES ===== */
        .error-message {
            background: rgba(220, 53, 53, 0.12);
            border: 1px solid rgba(220, 53, 53, 0.3);
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 13px;
            color: #f87171;
            margin-bottom: 4px;
        }

        /* ===== SUCCESS MESSAGE ===== */
        .success-message {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 6px;
            padding: 12px 14px;
            font-size: 13px;
            color: #4ade80;
            margin-bottom: 4px;
            width: 100%;
            max-width: 340px;
            line-height: 1.5;
        }

        /* ===== FORGOT PASSWORD SECTION ===== */
        .forgot-section {
            width: 100%;
            max-width: 340px;
            margin-top: 16px;
            animation: fadeSlideIn 0.4s ease-out;
        }

        @keyframes fadeSlideIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .forgot-warning {
            background: rgba(234, 179, 8, 0.10);
            border: 1px solid rgba(234, 179, 8, 0.25);
            border-radius: 6px;
            padding: 12px 14px;
            font-size: 12.5px;
            color: #facc15;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        .forgot-warning svg {
            width: 14px;
            height: 14px;
            vertical-align: -2px;
            margin-right: 4px;
            fill: #facc15;
        }

        .forgot-btn {
            width: 100%;
            padding: 11px 20px;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.2px;
            color: #facc15;
            background: rgba(234, 179, 8, 0.08);
            border: 1px solid rgba(234, 179, 8, 0.25);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .forgot-btn:hover {
            background: rgba(234, 179, 8, 0.16);
            border-color: rgba(234, 179, 8, 0.4);
            box-shadow: 0 4px 16px rgba(234, 179, 8, 0.15);
            transform: translateY(-1px);
        }

        .forgot-btn:active {
            transform: translateY(0);
            background: rgba(234, 179, 8, 0.12);
        }

        .forgot-btn svg {
            width: 16px;
            height: 16px;
            fill: #facc15;
        }

        .forgot-email-hint {
            text-align: center;
            font-size: 11.5px;
            color: #777777;
            margin-top: 8px;
        }

        .forgot-email-hint strong {
            color: #999999;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .hero-title {
                font-size: 30px;
            }
        }

        @media (max-width: 840px) {
            .login-container {
                flex-direction: column;
                gap: 16px;
                overflow-y: auto;
                padding: 16px;
            }

            html, body {
                overflow: auto;
            }

            .hero-card {
                flex: none;
                width: 100%;
                height: 320px;
            }

            .hero-title {
                font-size: 26px;
            }

            .hero-subtitle {
                font-size: 13px;
            }

            .login-form-section {
                flex: none;
                width: 100%;
            }

            .logo-wrapper img {
                width: 90px;
                height: 90px;
            }

            .logo-wrapper {
                margin-bottom: 24px;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 12px;
            }

            .hero-card {
                height: 240px;
                border-radius: 16px;
            }

            .hero-overlay {
                padding: 20px 20px;
            }

            .hero-title {
                font-size: 22px;
            }

            .login-form {
                max-width: 100%;
            }

            .forgot-section {
                max-width: 100%;
            }

            .success-message {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Hero Card -->
        <div class="hero-card">
            <img src="{{ asset('images/login-hero.jpeg') }}" alt="Bendera Indonesia">
            <div class="hero-overlay">
                <h1 class="hero-title">KEMENHAN<br>PUSDATIN</h1>
                <p class="hero-subtitle">Inventaris Kementrian Pertahanan Pusat Data dan Informasi</p>
            </div>
        </div>

        <!-- Right Login Form -->
        <div class="login-form-section">
            <div class="logo-wrapper">
                <img src="{{ asset('images/kemenhan-logo.png') }}" alt="Logo Kementerian Pertahanan">
            </div>

            {{-- Success message after forgot password --}}
            @if (session('forgot_status'))
                <div class="success-message">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -2px; margin-right: 4px;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    {{ session('forgot_status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error-message" style="width: 100%; max-width: 340px; margin-bottom: 16px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form class="login-form" action="{{ route('login') }}" method="POST" id="login-form">
                @csrf

                <div class="form-field">
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Username"
                        value="{{ old('username', session('login_attempted_email', '')) }}"
                        required
                        autofocus
                    >
                </div>

                <div class="form-field">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Password"
                        required
                    >
                </div>

                <button type="submit" class="submit-btn" id="submit-btn">Submit</button>
            </form>

            {{-- Forgot Password section — only visible after 2+ failed attempts --}}
            @if (session('login_failed_attempts', 0) >= 2)
                <div class="forgot-section">
                    <div class="forgot-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        Anda sudah gagal login 2x. Klik tombol di bawah untuk menerima password sementara melalui email.
                    </div>
                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <button type="submit" class="forgot-btn" id="forgot-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            Kirim Password Sementara
                        </button>
                    </form>
                    <p class="forgot-email-hint">
                        Password akan dikirim ke <strong>{{ session('login_attempted_email') }}</strong>
                    </p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>