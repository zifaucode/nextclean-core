@extends('layouts.admin.app')

@section('title', 'Edit Karyawan')
@section('page_title', 'Edit Karyawan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <h6 class="mb-0 text-primary fw-bold"><i class="bi bi-person-badge me-2"></i>Edit Informasi Karyawan</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.employee.update', $employee->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Kode Karyawan (Opsional)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-upc-scan text-muted"></i></span>
                            <input type="text" name="employee_code" id="employee_code" class="form-control border-start-0 ps-0 @error('employee_code') is-invalid @enderror" value="{{ old('employee_code', $employee->employee_code) }}" placeholder="Biarkan kosong untuk auto-generate">
                            <button type="button" class="btn btn-primary" id="btn-generate-code" title="Generate Kode Karyawan"><i class="bi bi-arrow-clockwise"></i></button>
                            @error('employee_code')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold text-muted small text-uppercase">Akun User (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <select name="user_id" class="form-select border-start-0 ps-0 @error('user_id') is-invalid @enderror">
                                    <option value="">-- Pilih Akun User --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $employee->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('user_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">Outlet (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-shop text-muted"></i></span>
                                <select name="outlet_id" class="form-select border-start-0 ps-0 @error('outlet_id') is-invalid @enderror">
                                    <option value="">-- Pilih Outlet --</option>
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}" {{ old('outlet_id', $employee->outlet_id) == $outlet->id ? 'selected' : '' }}>{{ $outlet->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('outlet_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold text-muted small text-uppercase">Posisi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-briefcase text-muted"></i></span>
                                <input type="text" name="position" class="form-control border-start-0 ps-0 @error('position') is-invalid @enderror" value="{{ old('position', $employee->position) }}">
                            </div>
                            @error('position')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">Gaji Pokok (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">Rp</span>
                                <input type="number" name="salary" class="form-control border-start-0 ps-0 @error('salary') is-invalid @enderror" value="{{ old('salary', $employee->salary) }}" min="0">
                            </div>
                            @error('salary')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="mb-4 opacity-10">
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.employee.index') }}" class="btn btn-light px-4 border">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Update Karyawan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#btn-generate-code').click(function() {
            var btn = $(this);
            var icon = btn.find('i');
            icon.removeClass('bi-arrow-clockwise').addClass('bi-hourglass-split');
            btn.prop('disabled', true);

            $.ajax({
                url: "{{ route('admin.employee.generate-code') }}",
                type: "GET",
                success: function(response) {
                    $('#employee_code').val(response.code);
                    icon.removeClass('bi-hourglass-split').addClass('bi-arrow-clockwise');
                    btn.prop('disabled', false);
                },
                error: function() {
                    alert('Gagal menghasilkan kode karyawan.');
                    icon.removeClass('bi-hourglass-split').addClass('bi-arrow-clockwise');
                    btn.prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush
