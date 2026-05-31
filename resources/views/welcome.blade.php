<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextClean - Sistem Manajemen Laundry Modern</title>
    
    <!-- Scripts & Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .hover-text-primary:hover { color: #ab005a !important; }
    </style>
</head>
<body class="antialiased overflow-x-hidden">
    <!-- Top Navigation -->
    <header class="navbar-custom d-flex justify-content-between align-items-center w-100 px-4 px-lg-5 fixed-top transition-colors">
        <a href="#" class="logo-wrapper d-flex align-items-center gap-2 text-decoration-none">
            <div class="bg-primary-fixed rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <span class="material-symbols-outlined text-primary fs-4 filled">local_laundry_service</span>
            </div>
            <span class="fs-4 fw-bold text-primary tracking-tight" style="letter-spacing: -1px;">NextClean</span>
        </a>
        <nav class="d-none d-md-flex align-items-center gap-5">
            <a class="text-secondary text-decoration-none fw-semibold small transition-colors hover-text-primary position-relative nav-link-custom" href="#fitur">Fitur</a>
            <a class="text-secondary text-decoration-none fw-semibold small transition-colors hover-text-primary position-relative nav-link-custom" href="#manfaat">Manfaat</a>
        </nav>
        <div class="d-flex align-items-center gap-3">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary border-outline-variant btn-sm fw-semibold px-4 rounded-pill">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-4 fw-semibold rounded-pill hover-lift me-2">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-4 fw-semibold rounded-pill shadow-sm hover-lift">Coba Gratis</a>
                @endif
            @endauth
        </div>
    </header>

    <main class="pb-5">
        <!-- Hero Section -->
        <section class="container pt-5 pb-5 mt-4">
            <div class="row g-5 align-items-center py-5">
                <div class="col-lg-6 d-flex flex-column gap-4">
                    <h1 class="text-display-lg fw-bold text-on-surface">
                        Manajemen Laundry Modern, <span class="text-primary">Efisien & Cepat</span>
                    </h1>
                    <p class="lead text-secondary">
                        NextClean membantu Anda mengelola bisnis laundry dengan lebih mudah. Dari kasir POS hingga laporan keuangan otomatis, semua dalam satu sistem terintegrasi.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 pt-3">
                        <a class="btn btn-primary btn-lg d-inline-flex align-items-center justify-content-center gap-2 px-4 shadow-sm" href="{{ route('login') }}">
                            Mulai Sekarang <span class="material-symbols-outlined fs-5">arrow_forward</span>
                        </a>
                        <a class="btn btn-outline-secondary border-outline-variant btn-lg px-4" href="#fitur">
                            Pelajari Fitur
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image-wrapper">
                        <img alt="Dashboard Mockup" src="{{ asset('image/landing-page/screen.png') }}"/>
                    </div>
                </div>
            </div>
        </section>

        <!-- Logo Cloud -->
        <section class="border-top border-outline-variant bg-surface py-5">
            <div class="container text-center">
                <p class="small text-secondary fw-bold text-uppercase tracking-wider mb-4">Dipercaya oleh 500+ Bisnis Laundry di Seluruh Indonesia</p>
                <div class="logo-cloud d-flex flex-wrap justify-content-center align-items-center gap-4 gap-md-5">
                    <span class="fs-4 fw-bold text-on-surface">FreshWash</span>
                    <span class="fs-4 fw-bold text-on-surface">KlinKita</span>
                    <span class="fs-4 fw-bold text-on-surface">LaundryPro</span>
                    <span class="fs-4 fw-bold text-on-surface">BeningLaundry</span>
                    <span class="fs-4 fw-bold text-on-surface">CepatBersih</span>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="bg-surface-container-low py-5 border-top border-bottom border-outline-variant" id="fitur">
            <div class="container py-5">
                <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                    <h2 class="text-display-lg fw-bold text-on-surface mb-4">Fitur Utama</h2>
                    <p class="lead text-secondary">Semua alat yang Anda butuhkan untuk menjalankan bisnis laundry profesional dengan skala apapun.</p>
                </div>
                <div class="row g-4">
                    <!-- Feature Card 1 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="bg-surface-container-lowest p-4 rounded-4 border border-outline-variant hover-border-primary transition-colors h-100 shadow-sm">
                            <div class="bg-primary-fixed rounded-3 d-flex align-items-center justify-content-center mb-4 p-2" style="width: 56px; height: 56px;">
                                <img src="{{ asset('image/landing-page/icon-management-pesanan.png') }}" alt="Manajemen Pesanan" class="img-fluid" />
                            </div>
                            <h3 class="fs-5 fw-bold text-on-surface mb-3">Manajemen Pesanan</h3>
                            <p class="text-secondary mb-0">Lacak status cucian dari penerimaan hingga pengambilan dengan sistem barcode yang akurat.</p>
                        </div>
                    </div>
                    <!-- Feature Card 2 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="bg-surface-container-lowest p-4 rounded-4 border border-outline-variant hover-border-primary transition-colors h-100 shadow-sm">
                            <div class="bg-primary-fixed rounded-3 d-flex align-items-center justify-content-center mb-4 p-2" style="width: 56px; height: 56px;">
                                <img src="{{ asset('image/landing-page/icon-kasir-pos.png') }}" alt="Kasir POS Terintegrasi" class="img-fluid" />
                            </div>
                            <h3 class="fs-5 fw-bold text-on-surface mb-3">Kasir POS Terintegrasi</h3>
                            <p class="text-secondary mb-0">Proses transaksi cepat dengan dukungan berbagai metode pembayaran digital dan cetak struk.</p>
                        </div>
                    </div>
                    <!-- Feature Card 3 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="bg-surface-container-lowest p-4 rounded-4 border border-outline-variant hover-border-primary transition-colors h-100 shadow-sm">
                            <div class="bg-primary-fixed rounded-3 d-flex align-items-center justify-content-center mb-4 p-2" style="width: 56px; height: 56px;">
                                <img src="{{ asset('image/landing-page/icon-laporan-otomatis.png') }}" alt="Laporan Otomatis" class="img-fluid" />
                            </div>
                            <h3 class="fs-5 fw-bold text-on-surface mb-3">Laporan Otomatis</h3>
                            <p class="text-secondary mb-0">Pantau pendapatan harian, mingguan, dan bulanan dengan visualisasi grafik yang mudah dipahami.</p>
                        </div>
                    </div>
                    <!-- Feature Card 4 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="bg-surface-container-lowest p-4 rounded-4 border border-outline-variant hover-border-primary transition-colors h-100 shadow-sm">
                            <div class="bg-primary-fixed rounded-3 d-flex align-items-center justify-content-center mb-4 p-2" style="width: 56px; height: 56px;">
                                <img src="{{ asset('image/landing-page/icon-pelanggan.png') }}" alt="Manajemen Pelanggan" class="img-fluid" />
                            </div>
                            <h3 class="fs-5 fw-bold text-on-surface mb-3">Manajemen Pelanggan</h3>
                            <p class="text-secondary mb-0">Simpan data pelanggan, berikan poin loyalitas, dan kirim notifikasi otomatis via WhatsApp.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Business Benefits Section -->
        <section class="py-5" id="manfaat">
            <div class="container py-5">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 order-2 order-lg-1">
                        <div class="stats-graphic d-flex align-items-center justify-content-center p-4">
                            <div class="stats-card">
                                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                                    <span class="fw-bold fs-5 text-on-surface">Omzet Bulanan</span>
                                    <span class="text-primary fw-bold fs-5">+45%</span>
                                </div>
                                <div class="d-flex flex-column gap-3">
                                    <div class="bg-surface-container rounded-pill" style="height: 16px; width: 100%;"></div>
                                    <div class="bg-surface-container rounded-pill" style="height: 16px; width: 83%;"></div>
                                    <div class="bg-surface-container rounded-pill" style="height: 16px; width: 66%;"></div>
                                    
                                    <div class="d-flex justify-content-between pt-3">
                                        <div class="w-50 pe-2">
                                            <div class="bg-primary-container-alpha rounded-3 d-flex align-items-end p-2" style="height: 80px;">
                                                <div class="bg-primary w-100 rounded-2" style="height: 50%;"></div>
                                            </div>
                                        </div>
                                        <div class="w-50 ps-2">
                                            <div class="bg-primary-container-alpha rounded-3 d-flex align-items-end p-2" style="height: 80px;">
                                                <div class="bg-primary w-100 rounded-2" style="height: 100%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2 d-flex flex-column gap-4">
                        <div>
                            <span class="text-primary fw-bold small text-uppercase tracking-wider mb-3 d-block">Mengapa Memilih NextClean?</span>
                            <h2 class="text-display-lg fw-bold text-on-surface mb-4">Skalakan Bisnis Laundry Anda dengan Pasti</h2>
                            <p class="lead text-secondary">
                                Tinggalkan pencatatan manual yang membingungkan. NextClean dirancang khusus untuk mempercepat operasional, mengurangi kesalahan, dan meningkatkan kepuasan pelanggan Anda.
                            </p>
                        </div>
                        
                        <div class="d-flex flex-column gap-4 mt-2">
                            <div class="d-flex gap-3">
                                <div class="bg-primary-fixed rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                    <span class="material-symbols-outlined text-primary">trending_up</span>
                                </div>
                                <div>
                                    <h4 class="fs-5 fw-bold text-on-surface mb-2">Tingkatkan ROI secara Signifikan</h4>
                                    <p class="text-secondary mb-0">Optimalkan proses penerimaan hingga pengambilan cucian, memungkinkan Anda melayani lebih banyak pelanggan setiap harinya tanpa menambah karyawan.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-3">
                                <div class="bg-primary-fixed rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                    <span class="material-symbols-outlined text-primary">speed</span>
                                </div>
                                <div>
                                    <h4 class="fs-5 fw-bold text-on-surface mb-2">Efisiensi Operasional 10x Lipat</h4>
                                    <p class="text-secondary mb-0">Notifikasi otomatis dan laporan real-time menghemat berjam-jam waktu admin Anda setiap minggu, sehingga Anda bisa fokus pada ekspansi bisnis.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-3">
                                <div class="bg-primary-fixed rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                    <span class="material-symbols-outlined text-primary">verified_user</span>
                                </div>
                                <div>
                                    <h4 class="fs-5 fw-bold text-on-surface mb-2">Keamanan & Kontrol Penuh</h4>
                                    <p class="text-secondary mb-0">Cegah kecurangan dengan sistem akses staf yang terstruktur dan pencatatan kas yang tidak bisa dimanipulasi.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-primary text-white py-5">
            <div class="container py-5 text-center">
                <div class="mx-auto d-flex flex-column align-items-center" style="max-width: 800px;">
                    <h2 class="text-display-lg fw-bold mb-4">Siap untuk Mengembangkan Bisnis Laundry Anda?</h2>
                    <p class="lead mb-5" style="color: #fff0f2;">
                        Bergabunglah dengan ratusan pengusaha laundry lainnya yang telah beralih ke NextClean. Mulai sekarang, tanpa kartu kredit, batalkan kapan saja.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 w-100 justify-content-center">
                        <a class="btn btn-light text-primary fw-bold btn-lg px-5 py-3 shadow" href="{{ route('login') }}">
                            Mulai Uji Coba Gratis
                        </a>
                        <a class="btn btn-outline-light btn-lg px-5 py-3" href="#" style="background-color: rgba(255,255,255,0.1);">
                            Hubungi Tim Sales
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-surface border-top border-outline-variant py-5">
        <div class="container py-4">
            <div class="row g-5 mb-5">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-primary fs-3 filled">local_laundry_service</span>
                        <span class="fs-4 fw-bold text-primary tracking-tight">NextClean</span>
                    </div>
                    <p class="text-secondary" style="max-width: 320px;">
                        Platform manajemen operasional laundry terdepan untuk meningkatkan efisiensi dan kepuasan pelanggan bisnis Anda.
                    </p>
                </div>
                <div class="col-md-3">
                    <h4 class="fw-bold small text-on-surface text-uppercase tracking-wider mb-4">Tautan</h4>
                    <ul class="list-unstyled d-flex flex-column gap-3">
                        <li><a class="text-secondary text-decoration-none hover-text-primary transition-colors" href="#fitur">Fitur</a></li>
                        <li><a class="text-secondary text-decoration-none hover-text-primary transition-colors" href="#manfaat">Manfaat Bisnis</a></li>
                        <li><a class="text-secondary text-decoration-none hover-text-primary transition-colors" href="#">Panduan Pengguna</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h4 class="fw-bold small text-on-surface text-uppercase tracking-wider mb-4">Kontak</h4>
                    <ul class="list-unstyled d-flex flex-column gap-3">
                        <li class="d-flex align-items-center gap-2 text-secondary">
                            <span class="material-symbols-outlined fs-5">mail</span>
                            hello@nextclean.id
                        </li>
                        <li class="d-flex align-items-center gap-2 text-secondary">
                            <span class="material-symbols-outlined fs-5">phone</span>
                            0812-3456-7890
                        </li>
                        <li class="d-flex align-items-center gap-2 text-secondary">
                            <span class="material-symbols-outlined fs-5">location_on</span>
                            Jakarta, Indonesia
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-top border-outline-variant pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <p class="small text-secondary mb-0">
                    &copy; 2024 NextClean. Hak Cipta Dilindungi.
                </p>
                <div class="d-flex gap-4">
                    <a class="small text-secondary text-decoration-none hover-text-primary transition-colors" href="#">Syarat & Ketentuan</a>
                    <a class="small text-secondary text-decoration-none hover-text-primary transition-colors" href="#">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
