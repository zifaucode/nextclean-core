@extends('layouts.admin.app')

@section('title', 'Manajemen Kehadiran')
@section('page_title', 'Data Kehadiran Karyawan')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Kehadiran Karyawan</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="attendance-table">
                <thead>
                    <tr>
                        <th width="30">No</th>
                        <th>Karyawan</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th class="text-center">Lokasi</th>
                        <th class="text-center">Foto</th>
                        <th class="text-center">Status</th>
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
        $('#attendance-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.attendance.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'employee_name', name: 'employee.user.name' },
                { data: 'date', name: 'clock_in', searchable: false },
                { data: 'clock_in', name: 'clock_in', searchable: false },
                { data: 'clock_out', name: 'clock_out', searchable: false },
                { data: 'location', name: 'location', orderable: false, searchable: false, className: 'text-center' },
                { data: 'photo', name: 'photo', orderable: false, searchable: false, className: 'text-center' },
                { data: 'status', name: 'status', orderable: false, searchable: false, className: 'text-center' }
            ]
        });
    });
</script>
@endpush
