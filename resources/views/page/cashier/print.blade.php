<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi {{ $transaction->invoice_code }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            width: 58mm; /* Standard 58mm thermal printer */
            margin: 0 auto;
            padding: 5mm;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .fw-bold { font-weight: bold; }
        .fs-14 { font-size: 14px; }
        
        .header { margin-bottom: 10px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10px; }
        
        .divider {
            border-bottom: 1px dashed #000;
            margin: 8px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 2px 0;
            vertical-align: top;
        }
        
        .item-row td {
            font-size: 11px;
        }
        .item-name {
            display: block;
            margin-bottom: 2px;
        }
        
        .totals td {
            font-weight: bold;
        }
        
        .footer {
            margin-top: 15px;
            font-size: 11px;
            text-align: center;
        }
        
        /* Print specifics */
        @media print {
            body { width: 100%; padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="header text-center">
        <h1>{{ $transaction->outlet->name ?? 'NextClean' }}</h1>
        <p>{{ $transaction->outlet->address ?? 'Alamat Outlet' }}</p>
        <p>Telp: {{ $transaction->outlet->phone ?? '-' }}</p>
    </div>

    <div class="divider"></div>

    <table>
        <tr>
            <td>No</td>
            <td>: {{ $transaction->invoice_code }}</td>
        </tr>
        <tr>
            <td>Tgl</td>
            <td>: {{ date('d/m/Y H:i', strtotime($transaction->transaction_date)) }}</td>
        </tr>
        <tr>
            <td>Plg</td>
            <td>: {{ $transaction->customer->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td>: {{ auth()->user()->name ?? '-' }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table>
        @foreach($transaction->details as $item)
        <tr class="item-row">
            <td colspan="3">
                <span class="item-name">{{ $item->item_name }}</span>
            </td>
        </tr>
        <tr class="item-row">
            <td class="text-left" style="width: 25%">{{ $item->quantity }}x</td>
            <td class="text-left" style="width: 35%">{{ number_format($item->price, 0, ',', '.') }}</td>
            <td class="text-right" style="width: 40%">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <table class="totals">
        <tr>
            <td class="text-left">Total</td>
            <td class="text-right">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
        </tr>
        @if($transaction->additional_fee > 0)
        <tr>
            <td class="text-left">B. Tambahan</td>
            <td class="text-right">Rp {{ number_format($transaction->additional_fee, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td class="text-left fs-14">Grand Total</td>
            <td class="text-right fs-14">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>
    
    <div class="text-center" style="font-size: 12px; margin-top: 5px;">
        Status: <strong>{{ strtoupper($transaction->payment_status) }}</strong>
    </div>

    <div class="footer">
        <p>Terima Kasih</p>
        <p>Layanan Laundry Terbaik</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 8px 12px; background: #ab005a; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; margin-right: 5px;">Cetak Ulang</button>
        <button onclick="window.close()" style="padding: 8px 12px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Tutup</button>
    </div>

    <script>
        window.onload = function() {
            window.print();
            // Jendela dibiarkan terbuka agar user bisa melihat preview
        }
    </script>
</body>
</html>
