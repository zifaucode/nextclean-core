@extends('layouts.admin.app')

@section('title', 'Transaksi Baru')
@section('page_title', 'Input Transaksi Laundry')

@section('content')
<form action="{{ route('admin.transaction.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Data Pelanggan & Outlet</h5>
                </div>
                <div class="card-body">
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
                        <label class="form-label">Pelanggan</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">Pilih Pelanggan</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between">
                    <h5 class="mb-0">Detail Item</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-item">
                        <i class="bi bi-plus"></i> Tambah Baris
                    </button>
                </div>
                <div class="card-body">
                    <table class="table" id="items-table">
                        <thead>
                            <tr>
                                <th>Layanan / Produk</th>
                                <th width="120">Jenis</th>
                                <th width="150">Harga (Rp)</th>
                                <th width="100">Qty</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="items[0][product_id]" class="form-select product-select" required>
                                        <option value="">-- Pilih Layanan --</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}" data-type="{{ $p->type }}" data-price="{{ $p->price }}">
                                                {{ $p->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" class="form-control item-type" readonly></td>
                                <td><input type="text" class="form-control item-price" readonly></td>
                                <td><input type="number" name="items[0][quantity]" class="form-control" step="0.1" min="0.1" required></td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-item py-1 px-2" style="border-radius:0.375rem;"><i class="bi bi-trash"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.transaction.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // Data produk dari server disiapkan ke format string untuk mempermudah append
    const productOptions = `
        <option value="">-- Pilih Layanan --</option>
        @foreach($products as $p)
            <option value="{{ $p->id }}" data-type="{{ $p->type }}" data-price="{{ $p->price }}">{{ $p->name }}</option>
        @endforeach
    `;

    let itemIndex = 1;
    $('#add-item').click(function() {
        let row = `
            <tr>
                <td>
                    <select name="items[${itemIndex}][product_id]" class="form-select product-select" required>
                        ${productOptions}
                    </select>
                </td>
                <td><input type="text" class="form-control item-type" readonly></td>
                <td><input type="text" class="form-control item-price" readonly></td>
                <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control" step="0.1" min="0.1" required></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-item py-1 px-2" style="border-radius:0.375rem;"><i class="bi bi-trash"></i></button></td>
            </tr>
        `;
        $('#items-table tbody').append(row);
        itemIndex++;
    });

    $(document).on('click', '.remove-item', function() {
        if ($('#items-table tbody tr').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert('Minimal harus ada 1 item!');
        }
    });

    // Event listener untuk saat produk dipilih
    $(document).on('change', '.product-select', function() {
        let selectedOption = $(this).find('option:selected');
        let type = selectedOption.data('type') || '';
        let price = selectedOption.data('price') || '';
        
        let tr = $(this).closest('tr');
        tr.find('.item-type').val(type);
        tr.find('.item-price').val(price ? new Intl.NumberFormat('id-ID').format(price) : '');
    });
</script>
@endpush
