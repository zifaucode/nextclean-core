@extends('layouts.admin.app')

@section('title', 'Laporan Gaji Karyawan')
@section('page_title', 'Laporan Gaji Karyawan')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.salary.index') }}" method="GET" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label for="month" class="form-label">Bulan</label>
                    <select name="month" id="month" class="form-select">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="year" class="form-label">Tahun</label>
                    <select name="year" id="year" class="form-select">
                        @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-4 text-end">
                    <button type="button" class="btn btn-success" onclick="window.print()"><i class="bi bi-printer"></i> Cetak</button>
                </div>
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Kode Karyawan</th>
                        <th>Nama</th>
                        <th>Outlet</th>
                        <th>Posisi</th>
                        <th class="text-center">Jml. Kehadiran</th>
                        <th class="text-end">Gaji Pokok</th>
                        <th class="text-end">Total Gaji</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salaries as $salary)
                        <tr>
                            <td>{{ $salary['employee_code'] }}</td>
                            <td>{{ $salary['name'] }}</td>
                            <td>{{ $salary['outlet'] }}</td>
                            <td>{{ $salary['position'] }}</td>
                            <td class="text-center">{{ $salary['attendance_count'] }} Hari</td>
                            <td class="text-end">Rp {{ number_format($salary['base_salary'], 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($salary['total_salary'], 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.salary.pdf', ['employee_code' => $salary['employee_code'], 'month' => $month, 'year' => $year]) }}" class="btn btn-sm btn-danger" target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i> Cetak Slip
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data karyawan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
