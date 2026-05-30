<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pengeluaran NextClean</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        h2 { text-align: center; color: #333; }
    </style>
</head>
<body>
    <h2>Data Pengeluaran Operasional NextClean</h2>
    <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th width="30" class="text-center">No</th>
                <th>Tanggal</th>
                <th>Outlet</th>
                <th>Nama Pengeluaran</th>
                <th>Jumlah (Rp)</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $index => $e)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ date('d/m/Y', strtotime($e->date)) }}</td>
                <td>{{ $e->outlet->name ?? '-' }}</td>
                <td>{{ $e->name }}</td>
                <td class="text-right">{{ number_format($e->amount, 0, ',', '.') }}</td>
                <td>{{ $e->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
