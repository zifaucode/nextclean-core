@extends('layouts.admin.app')

@section('title', 'Catat Pengeluaran')
@section('page_title', 'Tambah Pengeluaran Baru')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.expense.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Outlet</label>
                        <select name="outlet_id" class="form-select" required>
                            <option value="">Pilih Outlet</option>
                            @foreach($outlets as $outlet)
                                <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Pengeluaran</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Listrik, Sabun, dll">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah (Rp)</label>
                        <input type="number" name="amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.expense.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
