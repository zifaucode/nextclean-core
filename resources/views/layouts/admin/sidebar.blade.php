<ul class="nav flex-column w-100">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
    </li>

    @if(auth()->check() && auth()->user()->hasRole('super-admin'))
    <div class="px-4 mt-3 mb-1">
        <span style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; color: rgba(255,255,255,0.6);">SaaS Management</span>
    </div>
    
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('superadmin.tenant.*') ? 'active' : '' }}" href="{{ route('superadmin.tenant.index') }}">
            <i class="bi bi-buildings"></i> Tenant / Klien
        </a>
    </li>
    @endif
    
    @php
        $isSetupIncomplete = auth()->check() && auth()->user()->hasRole('admin-outlet') && (empty(auth()->user()->brand_name) || empty(auth()->user()->brand_logo));
    @endphp

    @if($isSetupIncomplete)
    <li class="nav-item mt-4">
        <a class="nav-link active bg-danger text-white border-0 shadow-sm" href="{{ route('admin.setup.brand') }}">
            <i class="bi bi-exclamation-triangle text-white"></i> Selesaikan Setup
        </a>
    </li>
    @else
    
    <div class="px-4 mt-3 mb-1">
        <span style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; color: rgba(255,255,255,0.6);">Master Data</span>
    </div>
    
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.product.*') ? 'active' : '' }}" href="{{ route('admin.product.index') }}">
            <i class="bi bi-box-seam"></i> Layanan / Produk
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.outlet.*') ? 'active' : '' }}" href="{{ route('admin.outlet.index') }}">
            <i class="bi bi-shop"></i> Outlets
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.customer.*') ? 'active' : '' }}" href="{{ route('admin.customer.index') }}">
            <i class="bi bi-people"></i> Pelanggan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.employee.*') ? 'active' : '' }}" href="{{ route('admin.employee.index') }}">
            <i class="bi bi-briefcase"></i> Karyawan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}" href="{{ route('admin.user.index') }}">
            <i class="bi bi-person-badge"></i> Akun User
        </a>
    </li>

    <div class="px-4 mt-3 mb-1">
        <span style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; color: rgba(255,255,255,0.6);">Operasional</span>
    </div>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}" href="{{ route('admin.attendance.index') }}">
            <i class="bi bi-calendar-check"></i> Data Kehadiran
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.transaction.*') ? 'active' : '' }}" href="{{ route('admin.transaction.index') }}">
            <i class="bi bi-cart"></i> Transaksi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.expense.*') ? 'active' : '' }}" href="{{ route('admin.expense.index') }}">
            <i class="bi bi-cash-stack"></i> Pengeluaran
        </a>
    </li>
    
    <div class="px-4 mt-3 mb-1">
        <span style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; color: rgba(255,255,255,0.6);">Laporan</span>
    </div>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.salary.*') ? 'active' : '' }}" href="{{ route('admin.salary.index') }}">
            <i class="bi bi-wallet2"></i> Gaji Karyawan
        </a>
    </li>
    @endif
</ul>
