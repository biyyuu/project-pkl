<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Inventaris</title>
    <link rel="icon" href="{{ asset('images/kemenhan-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .profile-card {
            background-color: #2a1f1c;
            border-radius: 4px;
            padding: 30px;
            border: 1px solid rgba(255,255,255,0.04);
            max-width: 600px;
            margin: 0 auto;
        }

        .profile-title {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255,255,255,0.55);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border-radius: 4px;
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(0,0,0,0.2);
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: rgba(255,255,255,0.18);
            background: rgba(255,255,255,0.06);
        }

        .error-message {
            color: #f87171;
            font-size: 11px;
            margin-top: 6px;
            display: block;
        }

        .btn-submit {
            background-color: #5c1a1a;
            border: none;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.2s ease;
            width: 100%;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #7a2323;
        }

        .alert-profile {
            padding: 14px 18px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-profile.success {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.25);
            color: #4ade80;
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .password-toggle {
            position: absolute;
            right: 16px;
            background: none;
            border: none;
            color: rgba(255,255,255,0.55);
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .password-toggle:hover {
            color: rgba(255,255,255,0.8);
        }
        
        .info-card {
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
    <div class="app-layout">
        @include('components.sidebar')

        <main class="main-content">
            <div class="header">
                <div class="header-left">
                    <h1>Profil Saya</h1>
                    <p>Ubah pengaturan akun keamanan Anda.</p>
                </div>
            </div>

            <div class="profile-card info-card">
                <div class="profile-title">Informasi Akun</div>
                <div class="form-group">
                    <label class="form-label">Email Aktif</label>
                    <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly style="background: rgba(255,255,255,0.02); color: rgba(255,255,255,0.5);">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Peran (Role)</label>
                    <input type="text" class="form-control" value="{{ ucfirst(auth()->user()->roles->first()->name ?? 'Tidak ada peran') }}" readonly style="background: rgba(255,255,255,0.02); color: rgba(255,255,255,0.5);">
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-title">Ubah Password</div>

                @if(session('success'))
                    <div class="alert-profile success">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('profile.update-password') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Password Saat Ini</label>
                        <div class="password-wrapper">
                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('current_password', this)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="form-control" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Simpan Password Baru</button>
                </form>
            </div>
        </main>
    </div>

    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;
            } else {
                input.type = 'password';
                btn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
            }
        }
    </script>
</body>
</html>
