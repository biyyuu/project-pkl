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
                        value="{{ old('username') }}"
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

                <div class="form-field select-wrapper">
                    <select id="role" name="role" required onchange="this.classList.toggle('has-value', this.value !== '')">
                        <option value="" disabled selected>Select Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>

                <button type="submit" class="submit-btn" id="submit-btn">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>