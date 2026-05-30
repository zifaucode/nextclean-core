@extends('layouts.admin.app')

@section('title', 'Detail Outlet')
@section('page_title', 'Detail Outlet: ' . $outlet->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-primary fw-bold"><i class="bi bi-info-circle me-2"></i>Detail Informasi Outlet</h6>
                <a href="{{ route('admin.outlet.edit', $outlet->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
            </div>
            <div class="card-body p-4">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-muted fw-bold small text-uppercase" width="160"><i class="bi bi-shop-window me-2"></i>Nama Outlet</td>
                        <td width="20">:</td>
                        <td class="fw-bold">{{ $outlet->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold small text-uppercase"><i class="bi bi-telephone me-2"></i>Telepon</td>
                        <td>:</td>
                        <td>{{ $outlet->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold small text-uppercase"><i class="bi bi-geo-alt me-2"></i>Alamat</td>
                        <td>:</td>
                        <td>{{ $outlet->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold small text-uppercase"><i class="bi bi-clock me-2"></i>Jam Operasional</td>
                        <td>:</td>
                        <td>
                            @if($outlet->open_time && $outlet->close_time)
                                {{ date('H:i', strtotime($outlet->open_time)) }} - {{ date('H:i', strtotime($outlet->close_time)) }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="text-muted fw-bold small text-uppercase"><i class="bi bi-cash-stack me-2"></i>Biaya Tambahan</td>
                        <td>:</td>
                        <td>Rp {{ number_format($outlet->additional_fee ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </table>

                <hr class="mb-4 mt-2 opacity-10">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.outlet.index') }}" class="btn btn-light px-4 border"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                    
                    <form action="{{ route('admin.outlet.destroy', $outlet->id) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4"><i class="bi bi-trash me-2"></i>Hapus Outlet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
