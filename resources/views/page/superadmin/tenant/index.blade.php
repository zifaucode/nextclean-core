@extends('layouts.admin.app')

@section('title', 'Manajemen Tenant (Admin)')
@section('page_title', 'Daftar Tenant')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0" style="font-size: 14px;">Kelola akun Pemilik Bisnis Laundry (Admin) yang terdaftar di NextClean.</p>
    <a href="{{ route('superadmin.tenant.create') }}" class="btn btn-primary btn-sm px-4" style="border-radius: 0.5rem;">
        <i class="bi bi-plus-lg me-1"></i> Tambah Tenant Baru
    </a>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 1rem;">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="text-muted" style="font-size: 13px;">
                    <tr>
                        <th class="border-bottom-0 pb-3">NAMA PEMILIK</th>
                        <th class="border-bottom-0 pb-3">EMAIL</th>
                        <th class="border-bottom-0 pb-3">BRAND LAUNDRY</th>
                        <th class="border-bottom-0 pb-3">BERGABUNG SEJAK</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px;">
                    @forelse($tenants as $tenant)
                    <tr>
                        <td class="fw-medium text-dark">{{ $tenant->name }}</td>
                        <td class="text-muted">{{ $tenant->email }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $tenant->brand_name ?? 'N/A' }}</span>
                        </td>
                        <td class="text-muted">{{ $tenant->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada tenant yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
