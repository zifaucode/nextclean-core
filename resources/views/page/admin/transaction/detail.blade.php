@extends('layouts.admin.app')

@section('title', 'Detail Transaksi')
@section('page_title', 'Invoice: ' . $transaction->invoice_code)

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Transaksi</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Status</th>
                        <td>: 
                            <span class="badge bg-info mb-2">{{ $transaction->status }}</span>
                            <form action="{{ route('admin.transaction.status', $transaction->id) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm">
                                    <option value="Diterima" {{ $transaction->status == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="Dicuci" {{ $transaction->status == 'Dicuci' ? 'selected' : '' }}>Dicuci</option>
                                    <option value="Disetrika" {{ $transaction->status == 'Disetrika' ? 'selected' : '' }}>Disetrika</option>
                                    <option value="Selesai" {{ $transaction->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="Diambil" {{ $transaction->status == 'Diambil' ? 'selected' : '' }}>Diambil</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <th>Pembayaran</th>
                        <td>: 
                            <span class="badge bg-{{ $transaction->payment_status == 'Dibayar' ? 'success' : 'danger' }} mb-2">{{ $transaction->payment_status }}</span>
                            <form action="{{ route('admin.transaction.payment', $transaction->id) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="payment_status" class="form-select form-select-sm">
                                    <option value="Belum Bayar" {{ $transaction->payment_status == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                    <option value="Dibayar" {{ $transaction->payment_status == 'Dibayar' ? 'selected' : '' }}>Dibayar</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <th>Pelanggan</th>
                        <td>: {{ $transaction->customer->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Outlet</th>
                        <td>: {{ $transaction->outlet->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>: {{ $transaction->transaction_date }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Rincian Item</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Jenis</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->details as $detail)
                        <tr>
                            <td>{{ $detail->item_name }}</td>
                            <td>{{ $detail->type }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Total Harga</th>
                            <th class="text-end">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</th>
                        </tr>

                        <tr>
                            <th colspan="4" class="text-end">Biaya Tambahan</th>
                            <th class="text-end">Rp {{ number_format($transaction->additional_fee, 0, ',', '.') }}</th>
                        </tr>
                        <tr class="table-light">
                            <th colspan="4" class="text-end">Grand Total</th>
                            <th class="text-end text-primary">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
                <hr>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.transaction.index') }}" class="btn btn-secondary">Kembali</a>
                    <button class="btn btn-primary"><i class="bi bi-printer"></i> Cetak Nota</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
