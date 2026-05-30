@extends('layouts.admin.app')

@section('title', 'Setup Brand Laundry')
@section('page_title', 'Lengkapi Profil Brand Anda')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-shop fs-1"></i>
                    </div>
                    <h4 class="fw-bold">Selamat Datang di Sistem Laundry Nextclean</h4>
                    <p class="text-muted" style="font-size: 14px;">Sebelum mulai mengelola Outlet, silakan atur Nama Brand dan Logo Bisnis Laundry Anda terlebih dahulu.</p>
                </div>

                <form action="{{ route('admin.setup.brand.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nama Brand Laundry</label>
                        <input type="text" name="brand_name" class="form-control form-control-lg @error('brand_name') is-invalid @enderror" value="{{ old('brand_name', auth()->user()->brand_name) }}" placeholder="Contoh: WashUP Premium Laundry" required style="border-radius: 0.5rem; font-size: 15px;">
                        @error('brand_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Logo Laundry</label>
                        <input type="file" name="brand_logo" class="form-control form-control-lg @error('brand_logo') is-invalid @enderror" accept="image/*" required style="border-radius: 0.5rem; font-size: 15px;">
                        <div class="form-text" style="font-size: 12px;">Format: JPG, PNG, atau SVG. Ukuran maksimal 2MB.</div>
                        @error('brand_logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary btn-lg py-3" style="border-radius: 0.5rem; font-weight: 600;">
                            Simpan & Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection