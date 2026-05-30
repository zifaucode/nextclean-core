@extends('layouts.admin.app')

@section('title', 'Edit Outlet')
@section('page_title', 'Edit Outlet')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <h6 class="mb-0 text-primary fw-bold"><i class="bi bi-shop me-2"></i>Edit Informasi Outlet</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.outlet.update', $outlet->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nama Outlet</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-shop-window text-muted"></i></span>
                            <input type="text" name="name" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" value="{{ old('name', $outlet->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nomor Telepon</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone text-muted"></i></span>
                            <input type="text" name="phone" class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror" value="{{ old('phone', $outlet->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Alamat Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 align-items-start pt-2"><i class="bi bi-geo-alt text-muted"></i></span>
                            <textarea name="address" class="form-control border-start-0 ps-0 @error('address') is-invalid @enderror" rows="3">{{ old('address', $outlet->address) }}</textarea>
                        </div>
                        @error('address')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold text-muted small text-uppercase">Jam Buka</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-clock text-muted"></i></span>
                                <input type="time" name="open_time" class="form-control border-start-0 ps-0 @error('open_time') is-invalid @enderror" value="{{ old('open_time', $outlet->open_time ? date('H:i', strtotime($outlet->open_time)) : '') }}">
                            </div>
                            @error('open_time')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">Jam Tutup</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-clock-history text-muted"></i></span>
                                <input type="time" name="close_time" class="form-control border-start-0 ps-0 @error('close_time') is-invalid @enderror" value="{{ old('close_time', $outlet->close_time ? date('H:i', strtotime($outlet->close_time)) : '') }}">
                            </div>
                            @error('close_time')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-muted small text-uppercase">Biaya Tambahan (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">Rp</span>
                                <input type="number" step="0.01" name="additional_fee" class="form-control border-start-0 ps-0 @error('additional_fee') is-invalid @enderror" value="{{ old('additional_fee', $outlet->additional_fee) }}">
                            </div>
                            @error('additional_fee')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="mb-4 opacity-10">
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.outlet.index') }}" class="btn btn-light px-4 border">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Update Outlet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
