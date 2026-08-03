<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Role - Inventaris Kemhan Pusdatin</title>
    <link rel="icon" href="{{ asset('images/kemenhan-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/itemlist.css') }}">
    <style>
        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }
        .permission-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #d1d5db;
            font-size: 14px;
            cursor: pointer;
            background: rgba(255,255,255,0.05);
            padding: 10px 15px;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.2s;
        }
        .permission-checkbox:hover {
            border-color: rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.08);
        }
        .permission-checkbox input[type="checkbox"] {
            accent-color: #fbbf24;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        .form-container {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: rgba(255,255,255,0.8);
            font-size: 14px;
        }
        .form-group input[type="text"] {
            width: 100%;
            background: rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 10px 15px;
            border-radius: 6px;
            color: white;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="app-layout">
        @include('components.sidebar')

        <main class="main-content">
            <div class="header">
                <div class="header-left">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                        <h1 style="margin-bottom: 0;">Tambah Role Baru</h1>
                    </div>
                </div>
                <a href="{{ route('roles.index') }}" class="btn-export" style="text-decoration: none; background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); color: white;">
                    Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="alert-danger" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25); padding: 14px 18px; border-radius: 4px; color: #f87171; font-size: 13px; font-weight: 500; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <form action="{{ route('roles.store') }}" method="POST" class="form-container">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">Nama Role (Username / Jabatan)</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: staff_gudang" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Login</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Contoh: staff@pusdatin.kemhan.go.id" required style="width: 100%; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); padding: 10px 15px; border-radius: 6px; color: white; font-size: 14px;">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required style="width: 100%; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); padding: 10px 15px; border-radius: 6px; color: white; font-size: 14px;">
                    </div>

                    <div class="form-group">
                        <label>Hak Akses (Permissions)</label>
                        <div class="permissions-grid">
                            @foreach($permissions as $permission)
                                <label class="permission-checkbox">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                        <button type="submit" class="btn-submit" style="background: #fbbf24; color: #000; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                            Simpan Role
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
