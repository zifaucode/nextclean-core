@extends('layouts.admin.app')

@section('title', 'Manajemen Karyawan')
@section('page_title', 'Data Karyawan')

@section('content')
<div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Karyawan</h5>
            <a href="{{ route('admin.employee.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Karyawan
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="employee-table">
                    <thead>
                        <tr>
                            <th width="30">No</th>
                            <th>Kode</th>
                            <th>User (Akun)</th>
                            <th>Posisi</th>
                            <th>Gaji Pokok</th>
                            <th>Outlet</th>
                            <th class="text-center" width="100">Aksi</th>
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
        $('#employee-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.employee.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'employee_code', name: 'employee_code' },
                { data: 'user_name', name: 'user.name', orderable: false, searchable: false },
                { data: 'position', name: 'position' },
                { data: 'salary', name: 'salary' },
                { data: 'outlet_name', name: 'outlet.name', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });
    });
</script>
@endpush
