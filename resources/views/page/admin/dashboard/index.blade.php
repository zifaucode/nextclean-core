@extends('layouts.admin.app')

@section('title', 'Dashboard')
@section('page_title', 'Ringkasan Hari Ini')

@section('content')
<p class="text-muted mb-4" style="font-size: 14px;">Pantau operasional laundry Anda dalam satu tampilan.</p>

<div class="row g-4 mb-4">
    <!-- Stats Cards -->
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 1rem;">
            <div class="card-body position-relative p-4">
                <h6 class="text-muted fw-bold mb-3" style="font-size: 13px;">Pesanan Total</h6>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="fw-bold text-dark mb-0">{{ number_format($stats['today_transactions']) }}</h2>
                </div>
                <p class="text-muted mt-2 mb-0" style="font-size: 12px;">Hari ini</p>
                <div class="position-absolute top-0 end-0 m-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #ffd9e2; border-radius: 0.5rem;">
                    <i class="bi bi-receipt" style="color: #D80073;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 1rem;">
            <div class="card-body position-relative p-4">
                <h6 class="text-muted fw-bold mb-3" style="font-size: 13px;">Total Pendapatan</h6>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="fw-bold text-dark mb-0 fs-3">Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}</h2>
                </div>
                <p class="text-muted mt-2 mb-0" style="font-size: 12px;">Bulan ini</p>
                <div class="position-absolute top-0 end-0 m-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #f3f4f5; border-radius: 0.5rem;">
                    <i class="bi bi-cash" style="color: #656f82;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 1rem;">
            <div class="card-body position-relative p-4">
                <h6 class="text-muted fw-bold mb-3" style="font-size: 13px;">Pelanggan Terdaftar</h6>
                <h2 class="fw-bold text-dark mb-0">{{ number_format($stats['total_customers']) }}</h2>
                <p class="text-muted mt-2 mb-0" style="font-size: 12px;">Total di database</p>
                <div class="position-absolute top-0 end-0 m-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #f3f4f5; border-radius: 0.5rem;">
                    <i class="bi bi-person" style="color: #656f82;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 1rem;">
            <div class="card-body position-relative p-4">
                <h6 class="text-muted fw-bold mb-3" style="font-size: 13px;">Cucian Tertunda</h6>
                <h2 class="fw-bold text-dark mb-0">{{ number_format($stats['process_count']) }} Pesanan</h2>
                <p class="text-danger mt-2 mb-0 fw-bold" style="font-size: 12px;">Butuh penyelesaian</p>
                <div class="position-absolute top-0 end-0 m-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #ffd9e2; border-radius: 0.5rem;">
                    <i class="bi bi-basket" style="color: #D80073;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center p-4 pb-0">
                <h5 class="fw-bold mb-0">Pesanan Terbaru</h5>
                <a href="{{ route('admin.transaction.index') }}" class="text-decoration-none fw-bold" style="color: #D80073; font-size: 14px;">Lihat Semua</a>
            </div>
            <div class="card-body p-0 mt-3">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead style="background-color: #fdfafb;">
                            <tr>
                                <th class="text-muted border-0 py-3 px-4" style="font-size: 12px; letter-spacing: 0.05em;">ID PESANAN</th>
                                <th class="text-muted border-0 py-3 px-4" style="font-size: 12px; letter-spacing: 0.05em;">PELANGGAN</th>
                                <th class="text-muted border-0 py-3 px-4" style="font-size: 12px; letter-spacing: 0.05em;">LAYANAN</th>
                                <th class="text-muted border-0 py-3 px-4" style="font-size: 12px; letter-spacing: 0.05em;">STATUS</th>
                                <th class="text-muted border-0 py-3 px-4 text-end" style="font-size: 12px; letter-spacing: 0.05em;">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $trx)
                            <tr>
                                <td class="py-3 px-4 fw-bold">#{{ $trx->invoice_code }}</td>
                                <td class="py-3 px-4">{{ $trx->customer->name ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    {{ $trx->details->first()->item_name ?? 'Layanan' }}
                                    @if($trx->details->count() > 1)
                                        <span class="text-muted" style="font-size: 11px;">(+{{ $trx->details->count() - 1 }} lainnya)</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="badge {{ $trx->status == 'Selesai' ? 'bg-danger text-white' : 'bg-light text-dark border' }}" style="border-radius: 0.25rem;">
                                        {{ $trx->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-end">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada pesanan terbaru.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 1rem;">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold mb-1">Tren Pendapatan</h5>
                <p class="text-muted mb-0" style="font-size: 13px;">Distribusi 7 hari terakhir.</p>
            </div>
            <div class="card-body p-4">
                <div style="position: relative; height: 250px; width: 100%;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const chartData = @json($chartData);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.map(d => {
                // Konversi tanggal (YYYY-MM-DD) ke format hari (Sen, Sel, dll) agar lebih rapi seperti di desain
                const date = new Date(d.date);
                const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                return days[date.getDay()];
            }),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: chartData.map(d => d.total),
                backgroundColor: 'rgba(216, 0, 115, 0.8)',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    display: false,
                    beginAtZero: true
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endpush
