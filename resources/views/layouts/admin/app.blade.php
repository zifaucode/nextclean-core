<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ auth()->check() ? auth()->user()->tenant_brand_name : 'NextClean' }}</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- Custom Design System Styles -->
    <style>
        :root {
            /* Colors */
            --nc-primary: #D80073;
            --nc-primary-hover: #ab005a;
            --nc-bg: #fdfafb;
            --nc-surface: #ffffff;
            --nc-border: #f0e6ea;
            --nc-text-main: #191c1d;
            --nc-text-muted: #656f82;
            --nc-sidebar-bg: #D80073;
            --nc-sidebar-hover: rgba(255, 255, 255, 0.1);
            --nc-sidebar-active-bg: #ffffff;
            --nc-sidebar-active-text: #D80073;
            
            /* Typography */
            --font-family-base: 'Inter', sans-serif;
            
            /* Spacing & Layout */
            --sidebar-width: 260px;
            --topbar-height: 64px;
            --max-container: 1440px;
        }

        body {
            font-family: var(--font-family-base);
            background-color: var(--nc-bg);
            color: var(--nc-text-main);
            overflow-x: hidden;
        }

        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        /* Layout Structure */
        #wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            background-color: var(--nc-sidebar-bg);
            color: #fff;
            flex-shrink: 0;
            height: 100vh;
            position: fixed;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s;
        }

        #sidebar .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            font-weight: 700;
            font-size: 20px;
            color: #fff;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        #sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.85rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 0.5rem;
            margin: 0.5rem 1.25rem;
            transition: all 0.2s;
        }

        #sidebar .nav-link i {
            font-size: 18px;
        }

        #sidebar .nav-link:hover {
            color: #fff;
            background-color: var(--nc-sidebar-hover);
        }

        #sidebar .nav-link.active {
            color: var(--nc-sidebar-active-text);
            background-color: var(--nc-sidebar-active-bg);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Main Content */
        #content-wrapper {
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        /* Topbar */
        #topbar {
            height: var(--topbar-height);
            background-color: var(--nc-surface);
            border-bottom: 1px solid var(--nc-border);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .search-bar {
            background-color: var(--nc-bg);
            border: 1px solid var(--nc-border);
            border-radius: 2rem;
            padding: 0.4rem 1rem;
            display: flex;
            align-items: center;
            width: 300px;
        }
        
        .search-bar input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            margin-left: 0.5rem;
            font-size: 14px;
        }

        .main-content {
            padding: 2rem;
            max-width: var(--max-container);
            margin: 0 auto;
            width: 100%;
            flex-grow: 1;
        }

        /* Cards */
        .card {
            background-color: var(--nc-surface);
            border: 1px solid var(--nc-border);
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            transition: border-color 0.2s ease;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            border-color: rgba(216, 0, 115, 0.2);
        }
        
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--nc-border);
            padding: 1rem 1.5rem;
        }

        /* Buttons */
        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--nc-primary);
            border-color: var(--nc-primary);
            color: #fff;
        }

        .btn-primary:hover {
            background-color: var(--nc-primary-hover);
            border-color: var(--nc-primary-hover);
        }

        .btn-secondary {
            background-color: #fff;
            border-color: var(--nc-border);
            color: var(--nc-text-main);
        }
        
        .btn-secondary:hover {
            background-color: #f3f4f5;
            border-color: #d1d5db;
            color: var(--nc-text-main);
        }

        /* Inputs */
        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid var(--nc-border);
            padding: 0.5rem 0.75rem;
            font-size: 14px;
            color: var(--nc-text-main);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--nc-primary);
            box-shadow: 0 0 0 0.25rem rgba(216, 0, 115, 0.1);
        }

        label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--nc-text-muted);
            margin-bottom: 0.5rem;
        }

        /* DataTables */
        table.dataTable {
            font-size: 14px;
            border-collapse: separate !important;
            border-spacing: 0;
        }
        table.dataTable thead th {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--nc-text-muted);
            background-color: #F1F5F9;
            border-bottom: 1px solid var(--nc-border);
            padding: 12px 16px;
        }
        table.dataTable tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--nc-border);
            vertical-align: middle;
        }
        table.dataTable tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Badges / Chips */
        .badge {
            border-radius: 9999px;
            padding: 0.35em 0.8em;
            font-weight: 600;
            font-size: 12px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.show {
                transform: translateX(0);
            }
            #content-wrapper {
                width: 100%;
                margin-left: 0;
            }
            .main-content {
                padding: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand d-flex align-items-center gap-2" style="border-bottom: none; margin-top: 1rem; margin-bottom: 1rem;">
                @if(auth()->check() && auth()->user()->tenant_brand_logo)
                    <div class="bg-white rounded d-flex justify-content-center align-items-center overflow-hidden" style="width: 40px; height: 40px;">
                        <img src="{{ asset(auth()->user()->tenant_brand_logo) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                @else
                    <div class="bg-white text-primary rounded d-flex justify-content-center align-items-center" style="width: 40px; height: 40px; color: var(--nc-primary) !important;">
                        <i class="bi bi-droplet-fill fs-5"></i>
                    </div>
                @endif
                <div class="d-flex flex-column" style="line-height: 1.2;">
                    <span class="fs-5 fw-bold text-white">{{ auth()->check() ? auth()->user()->tenant_brand_name : 'NextClean' }}</span>
                    <span style="font-size: 11px; font-weight: 500; color: rgba(255,255,255,0.8);">Admin</span>
                </div>
            </a>
            <div class="py-2 d-flex flex-column h-100">
                @include('layouts.admin.sidebar')
                
                <div class="mt-auto px-4 mb-4">
                    <button class="btn btn-light text-primary w-100 fw-bold py-2 d-flex justify-content-center align-items-center gap-2" style="border-radius: 0.5rem; color: var(--nc-primary) !important;">
                        <i class="bi bi-plus-lg"></i> New Order
                    </button>
                </div>
            </div>
        </nav>

        <!-- Main Content Wrapper -->
        <div id="content-wrapper">
            <!-- Topbar -->
            <header id="topbar">
                <button class="btn btn-light d-md-none me-3" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="search-bar d-none d-md-flex">
                    <i class="bi bi-search text-muted"></i>
                    <input type="text" placeholder="Cari pesanan...">
                </div>

                <div class="ms-auto d-flex align-items-center gap-3">
                    <button class="btn btn-link text-dark p-0 position-relative">
                        <i class="bi bi-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    </button>
                    <button class="btn btn-link text-dark p-0">
                        <i class="bi bi-gear fs-5"></i>
                    </button>
                    <div class="dropdown ms-2">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=D80073&color=fff" class="rounded-circle me-2" style="width: 32px; height: 32px;" alt="User">
                            <span class="d-none d-md-inline" style="font-weight: 500; font-size: 14px;">{{ auth()->user()->name ?? 'Admin' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 0.5rem;" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="main-content">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h1 class="h3 mb-0" style="color: var(--nc-text-main);">@yield('page_title')</h1>
                    </div>
                </div>

                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="mt-auto py-3 text-center text-muted" style="font-size: 13px;">
                &copy; {{ date('Y') }} {{ auth()->check() ? auth()->user()->tenant_brand_name : 'NextClean' }} System. All rights reserved.
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Sidebar Toggle for Mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // DataTable defaults
        $.extend( true, $.fn.dataTable.defaults, {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json',
                search: "",
                searchPlaceholder: "Cari data..."
            },
            dom: '<"row align-items-center mb-3"<"col-md-6"l><"col-md-6"f>>rt<"row align-items-center mt-3"<"col-md-6"i><"col-md-6"p>>'
        });

        // Toastr Configuration
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        // Reusable SweetAlert2 delete confirmation
        $(document).on('submit', '.delete-form', function(e) {
            e.preventDefault();
            var form = this;
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D80073',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-primary px-4 me-2',
                    cancelButton: 'btn btn-secondary px-4'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>

    @if(session('success'))
        <script>
            toastr.success("{{ session('success') }}", "Berhasil");
        </script>
    @endif

    @if(session('error'))
        <script>
            toastr.error("{{ session('error') }}", "Gagal");
        </script>
    @endif

    @stack('scripts')
</body>
</html>
