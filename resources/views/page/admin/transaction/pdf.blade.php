<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi NextClean</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        h2 { text-align: center; color: #333; }
        .badge { padding: 4px 8px; border-radius: 4px; color: #fff; font-size: 10px; }
        .bg-success { background-color: #198754; }
        .bg-danger { background-color: #dc3545; }
        .bg-info { background-color: #0dcaf0; }
        .bg-warning { background-color: #ffc107; color: #000; }
        .bg-primary { background-color: #0d6efd; }
        .bg-secondary { background-color: #6c757d; }
    </style>
</head>
<body>
    <h2>Data Transaksi NextClean</h2>
    <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th width="30" class="text-center">No</th>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Outlet</th>
                <th>Pelanggan</th>
                <th>Total (Rp)</th>
                <th class="text-center">Status</th>
                <th class="text-center">Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $t)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $t->invoice_code }}</td>
                <td>{{ date('d/m/Y H:i', strtotime($t->transaction_date)) }}</td>
                <td>{{ $t->outlet->name ?? '-' }}</td>
                <td>{{ $t->customer->name ?? '-' }}</td>
                <td class="text-right">{{ number_format($t->grand_total, 0, ',', '.') }}</td>
                <td class="text-center">{{ $t->status }}</td>
                <td class="text-center">{{ $t->payment_status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
