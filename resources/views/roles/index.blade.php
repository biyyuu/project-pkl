<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Role - Inventaris Kemhan Pusdatin</title>
    <link rel="icon" href="{{ asset('images/kemenhan-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/itemlist.css') }}">
    <style>
        .btn-action-group {
            display: flex;
            gap: 6px;
            align-items: center;
        }
        .btn-edit, .btn-delete-item {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 4px;
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.04);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-edit svg, .btn-delete-item svg {
            width: 15px;
            height: 15px;
        }
        .btn-edit { color: #fbbf24; }
        .btn-edit:hover { background: rgba(251,191,36,0.12); border-color: rgba(251,191,36,0.25); }
        .btn-delete-item { color: #f87171; }
        .btn-delete-item:hover { background: rgba(248,113,113,0.12); border-color: rgba(248,113,113,0.25); }
        
        .alert-error-msg {
            background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25);
            padding: 14px 18px; border-radius: 4px; color: #f87171; font-size: 13px; font-weight: 500; margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
        }
        .permission-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            margin: 2px;
            color: #d1d5db;
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
                        <h1 style="margin-bottom: 0;">Manajemen Role</h1>
                    </div>
                    <p>Atur role dan hak akses pengguna dalam sistem.</p>
                </div>
                <a href="{{ route('roles.create') }}" class="btn-export" style="text-decoration: none;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah Role
                </a>
            </div>

            @if(session('success'))
                <div class="alert-success" style="background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.25); padding: 14px 18px; border-radius: 4px; color: #4ade80; font-size: 13px; font-weight: 500; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error-msg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <span class="card-title">Daftar Role</span>
                    <span class="card-badge">Total: {{ $roles->total() }} role</span>
                </div>
                
                @if($roles->isEmpty())
                    <div class="empty-state" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; color: rgba(255,255,255,0.3);">
                        <p>Belum ada data role</p>
                    </div>
                @else
                    <div class="scroll-area" style="max-height: 520px;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="20%">Nama Role</th>
                                    <th width="65%">Permissions</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <tr>
                                    <td style="font-family: monospace; font-size: 12px;">{{ $role->id }}</td>
                                    <td class="item-name" style="text-transform: capitalize;">{{ $role->name }}</td>
                                    <td>
                                        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                            @foreach($role->permissions->take(8) as $permission)
                                                <span class="permission-badge">{{ $permission->name }}</span>
                                            @endforeach
                                            @if($role->permissions->count() > 8)
                                                <span class="permission-badge" style="background: rgba(34, 197, 94, 0.2); color: #4ade80;">+{{ $role->permissions->count() - 8 }} lagi</span>
                                            @endif
                                            @if($role->permissions->isEmpty())
                                                <span style="color: rgba(255,255,255,0.3); font-size: 12px; font-style: italic;">Tidak ada hak akses</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-action-group">
                                            <a href="{{ route('roles.edit', $role->id) }}" class="btn-edit" title="Edit Role">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </a>
                                            @if($role->name !== 'admin')
                                            <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus role ini?')" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-delete-item" title="Hapus Role">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"/>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                <div style="margin-top: 15px;">
                    {{ $roles->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>
