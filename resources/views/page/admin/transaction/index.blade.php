@extends('layouts.admin.app')

@section('title', 'Daftar Transaksi')
@section('page_title', 'Manajemen Transaksi')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Transaksi Laundry</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.transaction.export.excel') }}" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>
            <a href="{{ route('admin.transaction.export.pdf') }}" class="btn btn-danger btn-sm" target="_blank">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
            <a href="{{ route('admin.transaction.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Transaksi Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="transaction-table">
                <thead>
                    <tr>
                        <th width="30">No</th>
                        <th>Invoice</th>
                        <th>Outlet</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th class="text-center">Status Cucian</th>
                        <th class="text-center">Status Bayar</th>
                        <th>Tanggal</th>
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
        $('#transaction-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.transaction.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'invoice_code', name: 'invoice_code' },
                { data: 'outlet_name', name: 'outlet.name' },
                { data: 'customer_name', name: 'customer.name' },
                { data: 'grand_total', name: 'grand_total' },
                { data: 'status', name: 'status', className: 'text-center' },
                { data: 'payment_status', name: 'payment_status', className: 'text-center' },
                { data: 'transaction_date', name: 'transaction_date' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            order: [[7, 'desc']],
            drawCallback: function() {
                $('.delete-form').on('submit', function(e) {
                    e.preventDefault();
                    let form = this;
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data transaksi akan dihapus permanen!",
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
