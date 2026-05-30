@extends('layouts.admin.app')

@section('title', 'Pengeluaran')
@section('page_title', 'Manajemen Pengeluaran Operasional')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Pengeluaran</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.expense.export.excel') }}" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>
            <a href="{{ route('admin.expense.export.pdf') }}" class="btn btn-danger btn-sm" target="_blank">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
            <a href="{{ route('admin.expense.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Catat Pengeluaran
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="expense-table">
                <thead>
                    <tr>
                        <th width="30">No</th>
                        <th>Tanggal</th>
                        <th>Outlet</th>
                        <th>Nama Pengeluaran</th>
                        <th>Jumlah</th>
                        <th class="text-center">Aksi</th>
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
        $('#expense-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.expense.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'date', name: 'date' },
                { data: 'outlet_name', name: 'outlet.name' },
                { data: 'name', name: 'name' },
                { data: 'amount', name: 'amount' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            order: [[1, 'desc']],
            drawCallback: function() {
                $('.delete-form').on('submit', function(e) {
                    e.preventDefault();
                    let form = this;
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data pengeluaran ini akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }
        });
    });
</script>
@endpush
