@extends('layouts.admin.app')

@section('title', 'Manajemen Outlet')
@section('page_title', 'Daftar Outlet')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Outlet</h5>
        <a href="{{ route('admin.outlet.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Tambah Outlet
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="outlet-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Outlet</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Jam Operasional</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#outlet-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.outlet.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'phone', name: 'phone' },
                { data: 'address', name: 'address' },
                { 
                    data: 'open_time', 
                    render: function(data, type, row) {
                        if (row.open_time === '-' && row.close_time === '-') return '-';
                        return row.open_time + ' - ' + row.close_time;
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });
    });
</script>
@endpush
