@extends('layouts.admin.app')

@section('title', 'Manajemen Pelanggan')
@section('page_title', 'Data Pelanggan')

@section('content')
<div class="card">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Pelanggan Laundry</h5>
        <a href="{{ route('admin.customer.create') }}" class="btn btn-primary mb-3">Tambah Pelanggan</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="customer-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Member Code</th>
                        <th>Outlet</th>
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
        $('#customer-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.customer.data') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'member_code',
                    name: 'member_code'
                },
                {
                    data: 'outlet_name',
                    name: 'outlet.name',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
</script>
@endpush