<!DOCTYPE html>
<html>
<head>
    <title>Slip Gaji - {{ $employee->user->name ?? $employee->employee_code }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; line-height: 1.4; color: #333; margin: 0; padding: 0; }
        .container { width: 100%; margin: 0 auto; padding: 20px; box-sizing: border-box; }
        
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; color: #1e3a8a; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 2px 0; font-size: 10px; color: #666; }
        .header .title { margin-top: 10px; font-size: 14px; font-weight: bold; background: #f3f4f6; display: inline-block; padding: 5px 15px; border-radius: 5px; border: 1px solid #d1d5db; }

        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; }
        .info-table td:nth-child(1) { width: 15%; font-weight: bold; }
        .info-table td:nth-child(2) { width: 2%; }
        .info-table td:nth-child(3) { width: 33%; }
        .info-table td:nth-child(4) { width: 15%; font-weight: bold; }
        .info-table td:nth-child(5) { width: 2%; }
        .info-table td:nth-child(6) { width: 33%; }

        .details-wrapper { width: 100%; display: table; margin-bottom: 20px; }
        .col-left { display: table-cell; width: 48%; padding-right: 2%; vertical-align: top; }
        .col-right { display: table-cell; width: 48%; padding-left: 2%; vertical-align: top; border-left: 1px dashed #ccc; }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; padding: 6px; text-align: left; font-size: 10px; color: #4b5563; text-transform: uppercase; }
        .data-table td { padding: 6px; border-bottom: 1px solid #f3f4f6; }
        .data-table .amt { text-align: right; font-weight: bold; }
        
        .total-box { margin-top: 20px; background-color: #f8fafc; border: 1px solid #cbd5e1; padding: 10px; border-radius: 5px; }
        .total-box table { width: 100%; }
        .total-box td { font-size: 14px; font-weight: bold; }
        .total-box .total-amt { text-align: right; color: #1e40af; font-size: 16px; }

        .footer { margin-top: 40px; width: 100%; display: table; }
        .signature-box { display: table-cell; width: 50%; text-align: center; }
        .signature-box p { margin: 0 0 50px 0; }
        .signature-line { display: inline-block; width: 150px; border-bottom: 1px solid #000; }
        .signature-name { font-weight: bold; margin-top: 5px; display: block; }
        
        .note { margin-top: 20px; font-size: 9px; color: #9ca3af; font-style: italic; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>NEXTCLEAN LAUNDRY</h1>
            <p>Sistem Manajemen Operasional NextClean</p>
            <p>Cabang: {{ $employee->outlet->name ?? 'Pusat' }}</p>
            <div class="title">SLIP GAJI KARYAWAN</div>
        </div>

        <table class="info-table">
            <tr>
                <td>Kode Karyawan</td><td>:</td><td>{{ $employee->employee_code }}</td>
                <td>Bulan / Tahun</td><td>:</td><td>{{ $monthName }} {{ $year }}</td>
            </tr>
            <tr>
                <td>Nama Lengkap</td><td>:</td><td>{{ $employee->user->name ?? '-' }}</td>
                <td>Jabatan</td><td>:</td><td>{{ $employee->position }}</td>
            </tr>
            <tr>
                <td>Kehadiran</td><td>:</td><td>{{ $attendanceCount }} Hari</td>
                <td>Tgl. Cetak</td><td>:</td><td>{{ date('d/m/Y') }}</td>
            </tr>
        </table>

        <div class="details-wrapper">
            <div class="col-left">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th colspan="2">PENDAPATAN (EARNINGS)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gaji Pokok</td>
                            <td class="amt">Rp {{ number_format($baseSalary, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Tunjangan / Lainnya</td>
                            <td class="amt">Rp 0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="col-right">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th colspan="2">POTONGAN (DEDUCTIONS)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Pinjaman Kasbon</td>
                            <td class="amt">Rp 0</td>
                        </tr>
                        <tr>
                            <td>Potongan Keterlambatan</td>
                            <td class="amt">Rp 0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="total-box">
            <table>
                <tr>
                    <td>PENERIMAAN BERSIH (NET PAY)</td>
                    <td class="total-amt">Rp {{ number_format($totalSalary, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <div class="signature-box">
                <p>Penerima,</p>
                <span class="signature-line"></span>
                <span class="signature-name">{{ $employee->user->name ?? 'Karyawan' }}</span>
            </div>
            <div class="signature-box">
                <p>Disetujui Oleh,</p>
                <span class="signature-line"></span>
                <span class="signature-name">Manajer HRD / Keuangan</span>
            </div>
        </div>
        
        <div class="note">
            Dokumen ini dihasilkan secara otomatis oleh Sistem NextClean. Valid tanpa stempel basah.
        </div>
    </div>
</body>
</html>
