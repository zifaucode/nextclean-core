@extends('layouts.admin.app')

@section('title', 'Tambah Pelanggan')
@section('page_title', 'Tambah Pelanggan')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.customer.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label for="phone" class="form-label">Nomor Telepon</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address">{{ old('address') }}</textarea>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="member_code" class="form-label">Kode Member (Opsional)</label>
                <div class="input-group">
                    <input type="text" class="form-control @error('member_code') is-invalid @enderror" id="member_code" name="member_code" value="{{ old('member_code') }}">
                    <button class="btn btn-outline-primary" type="button" id="btn-generate-code" title="Generate Kode Member">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
                @error('member_code') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label for="outlet_id" class="form-label">Outlet (Opsional)</label>
                <select class="form-select @error('outlet_id') is-invalid @enderror" id="outlet_id" name="outlet_id">
                    <option value="">-- Pilih Outlet --</option>
                    @foreach($outlets as $outlet)
                        <option value="{{ $outlet->id }}" {{ old('outlet_id') == $outlet->id ? 'selected' : '' }}>{{ $outlet->name }}</option>
                    @endforeach
                </select>
                @error('outlet_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#btn-generate-code').on('click', function() {
            var btn = $(this);
            btn.prop('disabled', true);
            var icon = btn.find('i');
            
            // Add a simple rotation animation to indicate loading
            icon.addClass('d-inline-block');
            icon.css({
                'transition': 'transform 0.5s ease',
                'transform': 'rotate(360deg)'
            });
            
            $.ajax({
                url: "{{ route('admin.customer.generate-code') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    $('#member_code').val(response.code);
                    toastr.success('Kode member berhasil dibuat!', 'Sukses');
                },
                error: function() {
                    toastr.error('Gagal menghasilkan kode member.', 'Error');
                },
                complete: function() {
                    setTimeout(function() {
                        btn.prop('disabled', false);
                        icon.css('transform', 'none');
                    }, 500);
                }
            });
        });
    });
</script>
@endpush
