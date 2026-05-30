@extends('layouts.admin.app')

@section('title', 'Tambah Tenant Baru')
@section('page_title', 'Pendaftaran Tenant Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('superadmin.tenant.index') }}" class="text-decoration-none text-muted mb-3 d-inline-block" style="font-size: 14px;">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
    <p class="text-muted mb-0" style="font-size: 14px;">Mendaftarkan Admin Laundry baru. Sistem akan otomatis menduplikasi layanan/produk bawaan ke akun ini.</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
            <div class="card-body p-4">
                <form action="{{ route('superadmin.tenant.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nama Pemilik Bisnis</label>
                        <input type="text" name="name" class="form-control" style="border-radius: 0.5rem;" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Email Login</label>
                        <input type="email" name="email" class="form-control" style="border-radius: 0.5rem;" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Password Sementara</label>
                        <input type="password" name="password" class="form-control" style="border-radius: 0.5rem;" required minlength="8">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nama Brand Laundry (White-labeling)</label>
                        <input type="text" name="brand_name" class="form-control" placeholder="Contoh: WashUP Laundry" style="border-radius: 0.5rem;" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 0.5rem;">
                            <i class="bi bi-save me-1"></i> Simpan & Daftarkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
