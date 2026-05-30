<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', auth()->check() ? auth()->user()->tenant_brand_name . ' - Cashier POS' : 'NextClean - Cashier POS')</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        :root {
            --bs-primary: #ab005a;
            --bs-primary-rgb: 171, 0, 90;
            --bs-body-font-family: 'Inter', sans-serif;
            --bs-body-bg: #f8f9fa;
            --nc-surface: #ffffff;
            --nc-surface-light: #f3f4f5;
            --nc-border: #e1e3e4;
            --nc-text: #191c1d;
            --nc-text-muted: #5a3f47;
        }

        body {
            font-family: var(--bs-body-font-family);
            background-color: var(--bs-body-bg);
            color: var(--nc-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .text-primary {
            color: var(--bs-primary) !important;
        }

        .bg-primary {
            background-color: var(--bs-primary) !important;
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: #8e0049;
            border-color: #8e0049;
        }

        .btn-outline-primary {
            color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            color: #fff;
        }

        .header-pos {
            height: 64px;
            background-color: var(--nc-surface);
            border-bottom: 1px solid var(--nc-border);
            z-index: 1030;
        }

        .search-bar {
            background-color: var(--nc-surface-light);
            border: 1px solid var(--nc-border);
            transition: all 0.2s;
        }

        .search-bar:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(171, 0, 90, 0.25);
        }

        .tracking-tight {
            letter-spacing: -0.02em;
        }

        .main-content {
            flex-grow: 1;
            max-width: 1440px;
            margin: 0 auto;
            width: 100%;
            padding: 2rem;
        }

        /* Utility classes to match tailwind design */
        .rounded-xl { border-radius: 1rem !important; }
        .rounded-2xl { border-radius: 1.5rem !important; }
        
        .shadow-sm-soft { box-shadow: 0 1px 3px 0 rgba(0,0,0,0.05) !important; }
        .shadow-md-soft { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1) !important; }

        .btn-icon-light {
            color: var(--nc-text-muted);
            background: transparent;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .btn-icon-light:hover {
            background: var(--nc-surface-light);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    <!-- SweetAlert2 & Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    @stack('styles')
</head>
<body>

    <!-- Header -->
    <header class="header-pos sticky-top d-flex align-items-center w-100 px-4">
        <div class="d-flex align-items-center w-100 justify-content-between">
            <div class="d-flex align-items-center gap-4">
                <div class="d-flex align-items-center gap-2">
                    @if(auth()->check() && auth()->user()->tenant_brand_logo)
                        <img src="{{ asset(auth()->user()->tenant_brand_logo) }}" alt="Logo" style="height: 32px; object-fit: contain;">
                    @else
                        <i class="bi bi-droplet-fill text-primary fs-4"></i>
                    @endif
                    <h1 class="h4 mb-0 fw-bold text-primary tracking-tight">{{ auth()->check() ? auth()->user()->tenant_brand_name : 'NextClean' }}</h1>
                </div>
                <div class="position-relative d-none d-md-block" style="width: 256px;">
                    <i class="bi bi-search position-absolute top-50 translate-middle-y text-secondary" style="left: 12px; font-size: 14px;"></i>
                    <input type="text" class="form-control rounded-pill search-bar" style="padding-left: 36px; font-size: 14px;" placeholder="Cari layanan...">
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <button class="btn-icon-light">
                    <i class="bi bi-bell fs-5"></i>
                </button>
                <button class="btn-icon-light">
                    <i class="bi bi-gear fs-5"></i>
                </button>
                <div class="dropdown">
                    <button class="btn p-0 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-circle overflow-hidden border" style="width: 32px; height: 32px; cursor: pointer;">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Kasir') }}&background=ab005a&color=fff" alt="Profile" class="w-100 h-100 object-fit-cover">
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="font-size: 14px;">
                        <li>
                            <div class="px-3 py-2 text-muted">
                                <span class="d-block fw-bold text-dark">{{ auth()->user()->name ?? 'Kasir' }}</span>
                                <small>{{ auth()->user()->email ?? '' }}</small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 py-2">
                                    <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 & Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('scripts')
</body>
</html>
