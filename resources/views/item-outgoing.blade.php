<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Keluar - Kemenhan Pusdatin</title>
    <meta name="description" content="Halaman Barang Keluar Sistem Inventaris Kementerian Pertahanan Pusat Data dan Informasi">
    <link rel="icon" href="{{ asset('images/kemenhan-logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        }

        /* ===== LAYOUT ===== */
        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 240px;
            background-color: #1a1210;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border-right: 1px solid rgba(255,255,255,0.06);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 100;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 20px 28px 20px;
        }

        .sidebar-brand img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            font-size: 13px;
            font-weight: 600;
            line-height: 1.3;
            color: rgba(255,255,255,0.9);
        }

        .sidebar-nav {
            flex: 1;
            padding: 0 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            border-radius: 10px;
            text-decoration: none;
            color: rgba(255,255,255,0.55);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-item:hover {
            color: rgba(255,255,255,0.85);
            background-color: rgba(255,255,255,0.05);
        }

        .nav-item.active {
            color: #ffffff;
            background-color: rgba(255,255,255,0.08);
        }

        .nav-item svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            opacity: 0.7;
        }

        .nav-item.active svg {
            opacity: 1;
        }

        .sidebar-footer {
            padding: 16px 12px 20px 12px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            border-radius: 10px;
            background: none;
            border: none;
            color: rgba(255,255,255,0.55);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            color: #f87171;
            background-color: rgba(248,113,113,0.08);
        }

        .logout-btn svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            flex: 1;
            margin-left: 240px;
            padding: 24px 28px 40px 28px;
            min-height: 100vh;
            overflow-y: auto;
        }

        /* ===== HEADER ===== */
        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .header-left h1 {
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .header-left p {
            font-size: 14px;
            color: rgba(255,255,255,0.5);
            font-weight: 400;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-direction: column;
            align-items: flex-end;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            background-color: #5c1a1a;
            border: 1px solid #5c1a1a;
            color: #ffffff;
        }

        .btn-export:hover {
            background-color: #7a2323;
        }

        .btn-export svg {
            width: 16px;
            height: 16px;
        }

        .header-date {
            font-size: 12px;
            color: rgba(255,255,255,0.4);
            font-weight: 400;
            margin-top: 6px;
        }

        /* ===== MAIN CARD ===== */
        .content-card {
            background-color: #2a1f1c;
            border-radius: 14px;
            padding: 24px;
            border: 1px solid rgba(255,255,255,0.04);
        }

        .content-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .content-card-title {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .content-card-subtitle {
            font-size: 13px;
            color: rgba(255,255,255,0.45);
            font-weight: 400;
        }

        .btn-pinjam {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 20px;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            background-color: #5c1a1a;
            border: 1px solid #5c1a1a;
            color: #ffffff;
            white-space: nowrap;
        }

        .btn-pinjam:hover {
            background-color: #7a2323;
        }

        .btn-pinjam svg {
            width: 16px;
            height: 16px;
        }

        /* ===== TOOLBAR (Search + Date) ===== */
        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 16px;
        }

        .search-box {
            position: relative;
            width: 260px;
        }

        .search-box input {
            width: 100%;
            padding: 9px 14px 9px 36px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.04);
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 400;
            outline: none;
            transition: all 0.2s ease;
        }

        .search-box input::placeholder {
            color: rgba(255,255,255,0.3);
        }

        .search-box input:focus {
            border-color: rgba(255,255,255,0.15);
            background: rgba(255,255,255,0.06);
        }

        .search-box svg {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            color: rgba(255,255,255,0.3);
            pointer-events: none;
        }

        .date-filter {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            position: relative;
        }

        .date-filter-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.04);
            color: rgba(255,255,255,0.6);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 400;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .date-filter-btn:hover {
            border-color: rgba(255,255,255,0.15);
            background: rgba(255,255,255,0.06);
        }

        .date-filter-btn svg {
            width: 14px;
            height: 14px;
        }

        .date-input-hidden {
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* ===== DATA TABLE ===== */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead th {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255,255,255,0.35);
            padding: 0 0 12px 0;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .data-table tbody td {
            font-size: 13px;
            font-weight: 400;
            color: rgba(255,255,255,0.75);
            padding: 12px 8px 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            vertical-align: middle;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .data-table tbody tr {
            transition: background-color 0.15s ease;
        }

        .data-table tbody tr:hover {
            background-color: rgba(255,255,255,0.02);
        }

        .data-table .item-name {
            font-weight: 500;
            color: rgba(255,255,255,0.9);
        }

        /* ===== STATUS BADGE ===== */
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-badge.pinjam {
            background: rgba(251, 191, 36, 0.12);
            color: #fbbf24;
        }

        .status-badge.keluar {
            background: rgba(239, 68, 68, 0.12);
            color: #f87171;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            color: rgba(255,255,255,0.3);
        }

        .empty-state svg {
            width: 48px;
            height: 48px;
            margin-bottom: 16px;
            opacity: 0.25;
        }

        .empty-state p {
            font-size: 14px;
            font-weight: 400;
        }

        .empty-state .empty-sub {
            font-size: 12px;
            color: rgba(255,255,255,0.2);
            margin-top: 4px;
        }

        /* ===== PAGINATION ===== */
        .pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.04);
            margin-top: 8px;
        }

        .pagination-info {
            font-size: 12px;
            color: rgba(255,255,255,0.35);
        }

        .pagination-links {
            display: flex;
            gap: 4px;
        }

        .pagination-links a,
        .pagination-links span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pagination-links a {
            color: rgba(255,255,255,0.5);
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
        }

        .pagination-links a:hover {
            color: #ffffff;
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.12);
        }

        .pagination-links .active-page {
            color: #ffffff;
            background: #5c1a1a;
            border: 1px solid #5c1a1a;
        }

        .pagination-links .disabled-page {
            color: rgba(255,255,255,0.15);
            background: transparent;
            border: 1px solid rgba(255,255,255,0.04);
            cursor: default;
        }

        /* ===== MODAL ===== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .modal-overlay.show {
            display: flex;
            opacity: 1;
        }

        .modal {
            background: #2a1f1c;
            border-radius: 16px;
            width: 520px;
            max-width: 95vw;
            max-height: 90vh;
            overflow-y: auto;
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 24px 48px rgba(0,0,0,0.4);
            transform: scale(0.95);
            transition: transform 0.25s ease;
        }

        .modal-overlay.show .modal {
            transform: scale(1);
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px 0 24px;
        }

        .modal-title {
            font-size: 17px;
            font-weight: 700;
            color: #ffffff;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.5);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            background: rgba(255,255,255,0.1);
            color: #ffffff;
        }

        .modal-close svg {
            width: 16px;
            height: 16px;
        }

        .modal-body {
            padding: 20px 24px 24px 24px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group:last-of-type {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.04);
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 400;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: rgba(255,255,255,0.18);
            background: rgba(255,255,255,0.06);
        }

        .form-control::placeholder {
            color: rgba(255,255,255,0.25);
        }

        select.form-control {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='rgba(255,255,255,0.4)' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }

        select.form-control option {
            background: #2a1f1c;
            color: #ffffff;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-error {
            font-size: 11px;
            color: #f87171;
            margin-top: 4px;
        }

        .btn-submit {
            width: 100%;
            padding: 11px;
            border-radius: 8px;
            border: none;
            background: #5c1a1a;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background: #7a2323;
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        /* ===== ALERT ===== */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: #4ade80;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        .alert svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .alert-close {
            margin-left: auto;
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            opacity: 0.6;
            padding: 2px;
            display: flex;
            transition: opacity 0.2s ease;
        }

        .alert-close:hover {
            opacity: 1;
        }

        .alert-close svg {
            width: 14px;
            height: 14px;
        }

        /* ===== DELETE BUTTON ===== */
        .btn-delete {
            background: none;
            border: none;
            color: rgba(255,255,255,0.3);
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            font-weight: 500;
        }

        .btn-delete:hover {
            color: #f87171;
            background: rgba(248,113,113,0.08);
        }

        .btn-delete svg {
            width: 14px;
            height: 14px;
        }

        /* ===== SELESAI BUTTON ===== */
        .btn-selesai {
            background: none;
            border: none;
            color: #34d399;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid rgba(52, 211, 153, 0.25);
        }

        .btn-selesai:hover {
            background: rgba(52, 211, 153, 0.08);
        }

        .btn-selesai svg {
            width: 13px;
            height: 13px;
        }

        /* ===== SCROLL AREA ===== */
        .scroll-area {
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }

        .scroll-area::-webkit-scrollbar {
            width: 4px;
        }

        .scroll-area::-webkit-scrollbar-track {
            background: transparent;
        }

        .scroll-area::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.1);
            border-radius: 2px;
        }

        /* ===== MODAL SCROLLBAR ===== */
        .modal::-webkit-scrollbar {
            width: 4px;
        }

        .modal::-webkit-scrollbar-track {
            background: transparent;
        }

        .modal::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.1);
            border-radius: 2px;
        }

        /* ===== SELECT CLEARABLE ===== */
        .select-clearable {
            position: relative;
            flex: 1;
        }

        .select-clearable select.form-control {
            width: 100%;
            padding-right: 36px;
        }

        .select-clearable.has-value select.form-control {
            padding-right: 56px;
        }

        .select-clear-btn {
            position: absolute;
            right: 32px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: none;
            background: rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.6);
            font-size: 13px;
            line-height: 1;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
            padding: 0;
            z-index: 2;
        }

        .select-clear-btn:hover {
            background: rgba(248,113,113,0.2);
            color: #f87171;
        }

        .select-clearable.has-value .select-clear-btn {
            display: flex;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 16px;
            }

            .header {
                flex-direction: column;
                gap: 16px;
            }

            .header-right {
                align-items: flex-start;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                width: 100%;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .data-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="app-layout">
        <!-- ===== SIDEBAR ===== -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('images/kemenhan-logo.png') }}" alt="Logo">
                <span class="sidebar-brand-text">Inventaris<br>Kemenhan Pusdatin</span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-item" id="nav-dashboard">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('item') }}" class="nav-item" id="nav-daftar-barang">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="8" y1="6" x2="21" y2="6"/>
                        <line x1="8" y1="12" x2="21" y2="12"/>
                        <line x1="8" y1="18" x2="21" y2="18"/>
                        <line x1="3" y1="6" x2="3.01" y2="6"/>
                        <line x1="3" y1="12" x2="3.01" y2="12"/>
                        <line x1="3" y1="18" x2="3.01" y2="18"/>
                    </svg>
                    Daftar Barang
                </a>
                <a href="{{ route('item-outgoing.index') }}" class="nav-item active" id="nav-barang-keluar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    Barang Keluar
                </a>
            </nav>

            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn" id="logout-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1-2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- ===== MAIN CONTENT ===== -->
        <main class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                        <h1 style="margin-bottom: 0;">Halo, {{ $user->name }}!</h1>
                        @php
                            $roleLabels = [
                                'admin' => 'Admin',
                                'kasub' => 'Kasub',
                                'kabid' => 'Kabid',
                            ];
                            $roleColors = [
                                'admin' => 'background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171;',
                                'kasub' => 'background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399;',
                                'kabid' => 'background: rgba(251, 191, 36, 0.15); border: 1px solid rgba(251, 191, 36, 0.3); color: #fbbf24;',
                            ];
                            $label = $roleLabels[$user->role] ?? ucfirst($user->role);
                            $style = $roleColors[$user->role] ?? 'background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #ffffff;';
                        @endphp
                        <span style="font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; {{ $style }}">
                            {{ $label }}
                        </span>
                    </div>
                    <p>Siap memonitoring inventaris?</p>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <button class="btn-export" id="btn-export" onclick="window.print()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Export
                        </button>
                    </div>
                    <span class="header-date">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
            <div class="alert alert-success" id="alert-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                {{ session('success') }}
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-error" id="alert-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="15" y1="9" x2="9" y2="15"/>
                    <line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
                {{ $errors->first() }}
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            @endif

            <!-- Content Card -->
            <div class="content-card">
                <div class="content-card-header">
                    <div>
                        <div class="content-card-title">Barang Keluar</div>
                        <div class="content-card-subtitle">Pantau barang yang dipinjam setiap divisi.</div>
                    </div>
                    @if(auth()->user()->role !== 'kabid')
                    <button class="btn-pinjam" id="btn-pinjam-open" onclick="openModal()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Pinjam Barang
                    </button>
                    @endif
                </div>

                <!-- Toolbar -->
                <div class="toolbar">
                    <div class="search-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" id="search-input" placeholder="Cari barang.." value="{{ request('search') }}" autocomplete="off">
                    </div>
                    <div class="date-filter" style="display:flex; align-items:center; gap:8px;">
                        <input type="date" id="start-date" class="form-control" style="width: 140px; padding: 7px 10px;" value="{{ request('start_date') }}" title="Mulai Tanggal">
                        <span style="color: rgba(255,255,255,0.4);">-</span>
                        <input type="date" id="end-date" class="form-control" style="width: 140px; padding: 7px 10px;" value="{{ request('end_date') }}" title="Sampai Tanggal">
                    </div>
                </div>

                <!-- Table -->
                @if($outgoings->isEmpty())
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                        <p>Belum ada data barang keluar</p>
                        <p class="empty-sub">Klik "+ Pinjam Barang" untuk menambahkan data</p>
                    </div>
                @else
                    <div class="scroll-area">
                        <table class="data-table" id="outgoing-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Inventaris</th>
                                    <th>Nama Barang</th>
                                    <th>Merk</th>
                                    <th>No. Seri</th>
                                    <th>Peminjam</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Pengembalian</th>
                                    <th>Keperluan</th>
                                    <th>Keterangan</th>
                                    <th>Dicatat Oleh</th>
                                    @if(auth()->user()->role !== 'kabid')
                                    <th></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($outgoings as $index => $outgoing)
                                <tr>
                                    <td style="font-size: 12px; color: rgba(255,255,255,0.35);">{{ $outgoings->firstItem() + $index }}</td>
                                    <td style="font-family: monospace; font-size: 12px; color: #fbbf24;">{{ $outgoing->item->no_inventaris ?? '-' }}</td>
                                    <td class="item-name">{{ $outgoing->item->nama_barang ?? '-' }}</td>
                                    <td>{{ $outgoing->item->merk ?? '-' }}</td>
                                    <td>{{ $outgoing->item->serial_number ?? '-' }}</td>
                                    <td>{{ $outgoing->borrower->nama ?? '-' }}</td>
                                    <td>{{ $outgoing->jumlah_keluar }}</td>
                                    <td>{{ $outgoing->tanggal_keluar->translatedFormat('d M Y') }}</td>
                                    <td>{{ $outgoing->tanggal_kembali ? $outgoing->tanggal_kembali->translatedFormat('d M Y') : '-' }}</td>
                                    <td>{{ $outgoing->keperluan ?? '-' }}</td>
                                    <td style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $outgoing->keterangan ?? '-' }}</td>
                                    <td style="font-size: 12px; color: rgba(255,255,255,0.4);">{{ $outgoing->recorder->name ?? '-' }}</td>
                                    @if(auth()->user()->role !== 'kabid')
                                    <td>
                                        <div style="display:flex; gap:6px; align-items:center;">
                                            <button type="button" class="btn-delete" title="Edit" style="color: #fbbf24;" onclick='openEditOutgoingModal(@json($outgoing))'>
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </button>
                                             <button type="button" class="btn-selesai" title="Selesaikan transaksi & kembalikan stok" onclick="confirmSelesai('{{ route('item-outgoing.destroy', $outgoing) }}')">
                                                 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                     <polyline points="20 6 9 17 4 12"/>
                                                 </svg>
                                                 Selesai
                                             </button>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($outgoings->hasPages())
                    <div class="pagination-wrapper">
                        <span class="pagination-info">
                            Menampilkan {{ $outgoings->firstItem() }}-{{ $outgoings->lastItem() }} dari {{ $outgoings->total() }} data
                        </span>
                        <div class="pagination-links">
                            {{-- Previous --}}
                            @if($outgoings->onFirstPage())
                                <span class="disabled-page">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><polyline points="15 18 9 12 15 6"/></svg>
                                </span>
                            @else
                                <a href="{{ $outgoings->previousPageUrl() }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><polyline points="15 18 9 12 15 6"/></svg>
                                </a>
                            @endif

                            {{-- Page Numbers --}}
                            @foreach($outgoings->getUrlRange(1, $outgoings->lastPage()) as $page => $url)
                                @if($page == $outgoings->currentPage())
                                    <span class="active-page">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}">{{ $page }}</a>
                                @endif
                            @endforeach

                            {{-- Next --}}
                            @if($outgoings->hasMorePages())
                                <a href="{{ $outgoings->nextPageUrl() }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><polyline points="9 18 15 12 9 6"/></svg>
                                </a>
                            @else
                                <span class="disabled-page">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><polyline points="9 18 15 12 9 6"/></svg>
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif
                @endif
            </div>
        </main>
    </div>

    <!-- ===== MODAL: Pinjam Barang ===== -->
    <div class="modal-overlay" id="modal-overlay">
        <div class="modal" id="modal-pinjam">
            <div class="modal-header">
                <h2 class="modal-title">Pinjam Barang</h2>
                <button class="modal-close" id="modal-close" onclick="closeModal()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('item-outgoing.store') }}" method="POST" id="form-pinjam">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="item_id">Barang</label>
                        <div style="display:flex; gap:10px;">
                            <div class="select-clearable" id="wrap-item_id">
                                <select class="form-control" name="item_id" id="item_id" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_barang }} (Stok: {{ $item->jumlah }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="select-clear-btn" title="Hapus pilihan">&times;</button>
                            </div>
                            <button type="button" class="btn-export" onclick="openItemModal()" style="padding: 9px 12px; background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.1);">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            </button>
                        </div>
                        @error('item_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="borrower_id">Peminjam</label>
                        <div style="display:flex; gap:10px;">
                            <div class="select-clearable" id="wrap-borrower_id">
                                <select class="form-control" name="borrower_id" id="borrower_id" required>
                                    <option value="">-- Pilih Peminjam --</option>
                                    @foreach($borrowers as $b)
                                        <option value="{{ $b->id }}" {{ old('borrower_id') == $b->id ? 'selected' : '' }}>
                                            {{ $b->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="select-clear-btn" title="Hapus pilihan">&times;</button>
                            </div>
                            <button type="button" class="btn-export" onclick="openBorrowerModal()" style="padding: 9px 12px; background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.1);">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            </button>
                        </div>
                        @error('borrower_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="jumlah_keluar">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah_keluar" id="jumlah_keluar" min="1" value="{{ old('jumlah_keluar', 1) }}" required>
                            @error('jumlah_keluar')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="tanggal_keluar">Tanggal Pinjam</label>
                            <input type="date" class="form-control" name="tanggal_keluar" id="tanggal_keluar" value="{{ old('tanggal_keluar', now()->format('Y-m-d')) }}" required>
                            @error('tanggal_keluar')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="tanggal_kembali">Tanggal Pengembalian</label>
                            <input type="date" class="form-control" name="tanggal_kembali" id="tanggal_kembali" value="{{ old('tanggal_kembali') }}">
                            @error('tanggal_kembali')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="keperluan">Keperluan</label>
                        <input type="text" class="form-control" name="keperluan" id="keperluan" placeholder="Contoh: Rapat divisi, Kegiatan lapangan..." value="{{ old('keperluan') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="keterangan">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    </div>

                    <button type="submit" class="btn-submit" id="btn-submit-pinjam">
                        Simpan Data Barang Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== SUB MODAL: Tambah Barang ===== -->
    <div class="modal-overlay" id="modal-overlay-item" style="z-index: 1010;">
        <div class="modal" id="modal-item">
            <div class="modal-header">
                <h2 class="modal-title">Tambah Barang Baru</h2>
                <button class="modal-close" onclick="closeItemModal()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-add-item" onsubmit="submitItemForm(event)">
                    <div class="form-group">
                        <label class="form-label">No Inventaris</label>
                        <input type="text" class="form-control" id="new_item_no_inventaris" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="new_item_nama_barang" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Total Stok</label>
                            <input type="number" class="form-control" id="new_item_jumlah" min="1" value="1" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kondisi</label>
                            <select class="form-control" id="new_item_kondisi" required>
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                                <option value="hilang">Hilang</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Merk (Opsional)</label>
                        <input type="text" class="form-control" id="new_item_merk">
                    </div>
                    <button type="submit" class="btn-submit">Simpan Barang</button>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== SUB MODAL: Tambah Peminjam ===== -->
    <div class="modal-overlay" id="modal-overlay-borrower" style="z-index: 1010;">
        <div class="modal" id="modal-borrower">
            <div class="modal-header">
                <h2 class="modal-title">Tambah Peminjam Baru</h2>
                <button class="modal-close" onclick="closeBorrowerModal()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-add-borrower" onsubmit="submitBorrowerForm(event)">
                    <div class="form-group">
                        <label class="form-label">Nama Peminjam</label>
                        <input type="text" class="form-control" id="new_borrower_nama" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Divisi (Opsional)</label>
                        <input type="text" class="form-control" id="new_borrower_divisi">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telepon (Opsional)</label>
                        <input type="text" class="form-control" id="new_borrower_telepon">
                    </div>
                    <button type="submit" class="btn-submit">Simpan Peminjam</button>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== MODAL: Edit Barang Keluar ===== -->
    <div class="modal-overlay" id="modal-overlay-edit-outgoing">
        <div class="modal" id="modal-edit-outgoing">
            <div class="modal-header">
                <h2 class="modal-title">Edit Barang Keluar</h2>
                <button class="modal-close" onclick="closeEditOutgoingModal()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-edit-outgoing" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label" for="edit_out_item_id">Barang</label>
                        <div class="select-clearable" id="wrap-edit_out_item_id">
                            <select class="form-control" name="item_id" id="edit_out_item_id" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach(\App\Models\Item::orderBy('nama_barang')->get() as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->nama_barang }} (Stok: {{ $item->jumlah }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="select-clear-btn" title="Hapus pilihan">&times;</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="edit_out_borrower_id">Peminjam</label>
                        <div class="select-clearable" id="wrap-edit_out_borrower_id">
                            <select class="form-control" name="borrower_id" id="edit_out_borrower_id" required>
                                <option value="">-- Pilih Peminjam --</option>
                                @foreach($borrowers as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="select-clear-btn" title="Hapus pilihan">&times;</button>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="edit_out_jumlah">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah_keluar" id="edit_out_jumlah" min="1" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="edit_out_tanggal">Tanggal Pinjam</label>
                            <input type="date" class="form-control" name="tanggal_keluar" id="edit_out_tanggal" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="edit_out_tanggal_kembali">Tanggal Pengembalian</label>
                            <input type="date" class="form-control" name="tanggal_kembali" id="edit_out_tanggal_kembali">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="edit_out_keperluan">Keperluan</label>
                        <input type="text" class="form-control" name="keperluan" id="edit_out_keperluan">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="edit_out_keterangan">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="edit_out_keterangan"></textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== MODAL: Konfirmasi Selesai ===== -->
    <div class="modal-overlay" id="modal-overlay-selesai">
        <div class="modal" style="width: 420px; text-align: center;">
            <div class="modal-body" style="padding: 32px 24px;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(52, 211, 153, 0.1); border: 1px solid rgba(52, 211, 153, 0.2); color: #34d399; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
                    <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
                <h3 style="font-size: 18px; font-weight: 700; color: #ffffff; margin-bottom: 8px;">Selesaikan Transaksi?</h3>
                <p style="font-size: 13px; color: rgba(255,255,255,0.6); line-height: 1.5; margin-bottom: 24px;">
                    Menandai transaksi ini selesai akan mengembalikan stok barang ke daftar barang dan mencatatnya ke riwayat aktivitas.
                </p>
                
                <form id="form-selesai-transaksi" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
                
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button type="button" onclick="closeSelesaiModal()" style="flex: 1; padding: 11px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.04); color: #ffffff; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;">
                        Batal
                    </button>
                    <button type="button" onclick="submitSelesaiForm()" style="flex: 1; padding: 11px; border-radius: 8px; border: none; background: #10b981; color: #ffffff; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;">
                        Ya, Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ===== SELECT CLEAR BUTTONS =====
        function initSelectClearable(selectId) {
            const wrapper = document.getElementById('wrap-' + selectId);
            if (!wrapper) return;
            const select = wrapper.querySelector('select');
            const clearBtn = wrapper.querySelector('.select-clear-btn');
            if (!select || !clearBtn) return;

            function updateState() {
                if (select.value) {
                    wrapper.classList.add('has-value');
                } else {
                    wrapper.classList.remove('has-value');
                }
            }

            select.addEventListener('change', updateState);

            clearBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                select.value = '';
                wrapper.classList.remove('has-value');
                select.dispatchEvent(new Event('change'));
            });

            // Initial state
            updateState();
        }

        // Init all clearable selects
        initSelectClearable('item_id');
        initSelectClearable('borrower_id');
        initSelectClearable('edit_out_item_id');
        initSelectClearable('edit_out_borrower_id');

        // ===== MODAL =====
        function openModal() {
            const overlay = document.getElementById('modal-overlay');
            overlay.style.display = 'flex';
            requestAnimationFrame(() => {
                overlay.classList.add('show');
            });
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const overlay = document.getElementById('modal-overlay');
            overlay.classList.remove('show');
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 250);
            document.body.style.overflow = '';
        }

        // Close modal on overlay click
        document.getElementById('modal-overlay').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });

        // Auto-open modal if there are validation errors (old input exists)
        @if($errors->any() && old('item_id'))
            openModal();
        @endif

        // ===== SEARCH =====
        let searchTimeout;
        document.getElementById('search-input').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const value = this.value;
            searchTimeout = setTimeout(() => {
                const url = new URL(window.location.href);
                if (value) {
                    url.searchParams.set('search', value);
                } else {
                    url.searchParams.delete('search');
                }
                url.searchParams.delete('page');
                window.location.href = url.toString();
            }, 500);
        });

        // ===== SUB MODALS =====
        function openItemModal() {
            const overlay = document.getElementById('modal-overlay-item');
            overlay.style.display = 'flex';
            requestAnimationFrame(() => overlay.classList.add('show'));
        }
        function closeItemModal() {
            const overlay = document.getElementById('modal-overlay-item');
            overlay.classList.remove('show');
            setTimeout(() => overlay.style.display = 'none', 250);
        }
        
        function openBorrowerModal() {
            const overlay = document.getElementById('modal-overlay-borrower');
            overlay.style.display = 'flex';
            requestAnimationFrame(() => overlay.classList.add('show'));
        }
        function closeBorrowerModal() {
            const overlay = document.getElementById('modal-overlay-borrower');
            overlay.classList.remove('show');
            setTimeout(() => overlay.style.display = 'none', 250);
        }

        async function submitItemForm(e) {
            e.preventDefault();
            const data = {
                no_inventaris: document.getElementById('new_item_no_inventaris').value,
                nama_barang: document.getElementById('new_item_nama_barang').value,
                jumlah: document.getElementById('new_item_jumlah').value,
                kondisi_barang: document.getElementById('new_item_kondisi').value,
                merk: document.getElementById('new_item_merk').value,
            };

            try {
                const res = await fetch("{{ route('items.storeAjax') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                if(result.success) {
                    const select = document.getElementById('item_id');
                    const option = new Option(`${result.data.nama_barang} (Stok: ${result.data.jumlah})`, result.data.id, true, true);
                    select.add(option);
                    select.dispatchEvent(new Event('change'));
                    closeItemModal();
                    document.getElementById('form-add-item').reset();
                } else {
                    alert('Gagal menambahkan barang. Bisa jadi Nomor Inventaris duplikat.');
                }
            } catch (err) {
                alert('Terjadi kesalahan saat menambahkan barang.');
            }
        }

        async function submitBorrowerForm(e) {
            e.preventDefault();
            const data = {
                nama: document.getElementById('new_borrower_nama').value,
                divisi: document.getElementById('new_borrower_divisi').value,
                telepon: document.getElementById('new_borrower_telepon').value,
            };

            try {
                const res = await fetch("{{ route('borrowers.storeAjax') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                if(result.success) {
                    const select = document.getElementById('borrower_id');
                    const option = new Option(result.data.nama, result.data.id, true, true);
                    select.add(option);
                    select.dispatchEvent(new Event('change'));
                    closeBorrowerModal();
                    document.getElementById('form-add-borrower').reset();
                } else {
                    alert('Gagal menambahkan peminjam.');
                }
            } catch (err) {
                alert('Terjadi kesalahan saat menambahkan peminjam.');
            }
        }

        // ===== DATE PICKER RANGE =====
        function updateDateRange() {
            const start = document.getElementById('start-date').value;
            const end = document.getElementById('end-date').value;
            const url = new URL(window.location.href);
            
            if (start) url.searchParams.set('start_date', start);
            else url.searchParams.delete('start_date');

            if (end) url.searchParams.set('end_date', end);
            else url.searchParams.delete('end_date');

            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        document.getElementById('start-date').addEventListener('change', updateDateRange);
        document.getElementById('end-date').addEventListener('change', updateDateRange);

        // Auto-dismiss alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(function(alert) {
            setTimeout(function() {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.3s ease';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            }, 5000);
        });

        // ===== EDIT OUTGOING MODAL =====
        function openEditOutgoingModal(outgoing) {
            const overlay = document.getElementById('modal-overlay-edit-outgoing');
            const form = document.getElementById('form-edit-outgoing');

            // Set action URL
            form.action = '/barang-keluar/' + outgoing.id;

            // Fill fields
            document.getElementById('edit_out_item_id').value = outgoing.item_id;
            document.getElementById('edit_out_item_id').dispatchEvent(new Event('change'));
            document.getElementById('edit_out_borrower_id').value = outgoing.borrower_id;
            document.getElementById('edit_out_borrower_id').dispatchEvent(new Event('change'));
            document.getElementById('edit_out_jumlah').value = outgoing.jumlah_keluar;
            document.getElementById('edit_out_tanggal').value = outgoing.tanggal_keluar ? outgoing.tanggal_keluar.split('T')[0] : '';
            document.getElementById('edit_out_tanggal_kembali').value = outgoing.tanggal_kembali ? outgoing.tanggal_kembali.split('T')[0] : '';
            document.getElementById('edit_out_keperluan').value = outgoing.keperluan || '';
            document.getElementById('edit_out_keterangan').value = outgoing.keterangan || '';

            overlay.style.display = 'flex';
            requestAnimationFrame(() => overlay.classList.add('show'));
            document.body.style.overflow = 'hidden';
        }

        function closeEditOutgoingModal() {
            const overlay = document.getElementById('modal-overlay-edit-outgoing');
            overlay.classList.remove('show');
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 250);
            document.body.style.overflow = '';
        }

        document.getElementById('modal-overlay-edit-outgoing').addEventListener('click', function(e) {
            if (e.target === this) closeEditOutgoingModal();
        });

        // ===== CONFIRM SELESAI MODAL =====
        function confirmSelesai(actionUrl) {
            const overlay = document.getElementById('modal-overlay-selesai');
            const form = document.getElementById('form-selesai-transaksi');
            form.action = actionUrl;
            
            overlay.style.display = 'flex';
            requestAnimationFrame(() => overlay.classList.add('show'));
            document.body.style.overflow = 'hidden';
        }

        function closeSelesaiModal() {
            const overlay = document.getElementById('modal-overlay-selesai');
            overlay.classList.remove('show');
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 250);
            document.body.style.overflow = '';
        }

        function submitSelesaiForm() {
            document.getElementById('form-selesai-transaksi').submit();
        }

        document.getElementById('modal-overlay-selesai').addEventListener('click', function(e) {
            if (e.target === this) closeSelesaiModal();
        });
    </script>
</body>
</html>
