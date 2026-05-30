@extends('layouts.admin.app')

@section('title', 'Nextclean - Dashboard Super Admin')
@section('page_title', 'Ringkasan Sistem Global')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Superadmin Light Premium Theme */
    :root {
        --saas-primary: #0ea5e9;
        --saas-accent: #8b5cf6;
        --font-family-base: 'Plus Jakarta Sans', sans-serif;
    }

    body {
        font-family: var(--font-family-base);
        background-color: #f8fafc;
        background-image:
            radial-gradient(at 0% 0%, rgba(14, 165, 233, 0.05) 0px, transparent 50%),
            radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.05) 0px, transparent 50%);
        background-attachment: fixed;
    }

    /* Premium Cards */
    .saas-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 1.25rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .saas-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.1);
    }

    .stat-value {
        font-size: 2.25rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #0f172a;
    }

    .icon-box {
        width: 56px;
        height: 56px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .icon-box.primary {
        background: rgba(14, 165, 233, 0.1);
        color: #0ea5e9;
    }

    .icon-box.accent {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }

    .icon-box.success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .icon-box.warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    /* Tables */
    .table-premium thead th {
        background: #f8fafc;
        color: #64748b;
        border-bottom: 2px solid #e2e8f0;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 1rem;
    }

    .table-premium tbody td {
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        padding: 1rem;
        color: #334155;
    }

    .table-premium tbody tr:hover td {
        background: #f8fafc;
    }

    .tenant-badge {
        background: rgba(14, 165, 233, 0.1);
        color: #0284c7;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Glow Effects */
    .glow-point {
        position: absolute;
        width: 150px;
        height: 150px;
        background: var(--saas-primary);
        filter: blur(100px);
        opacity: 0.08;
        z-index: 0;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<div class="position-relative">
    <div class="glow-point" style="top: -50px; left: -50px;"></div>
    <div class="glow-point" style="bottom: 0; right: 0; background: var(--saas-accent);"></div>

    <!-- Stats Grid -->
    <div class="row g-4 mb-4 position-relative" style="z-index: 1;">
        <!-- Total Tenants -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="saas-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b;">Klien Aktif</p>
                        <h3 class="stat-value mb-0">{{ number_format($stats['total_tenants']) }}</h3>
                    </div>
                    <div class="icon-box primary">
                        <i class="bi bi-buildings-fill"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 mt-3" style="font-size: 0.85rem;">
                    <span class="text-success fw-bold"><i class="bi bi-arrow-up-right"></i> Sistem Berkembang</span>
                </div>
            </div>
        </div>

        <!-- Total Outlets -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="saas-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b;">Total Outlets</p>
                        <h3 class="stat-value mb-0">{{ number_format($stats['total_outlets']) }}</h3>
                    </div>
                    <div class="icon-box accent">
                        <i class="bi bi-shop-window"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 mt-3" style="font-size: 0.85rem; color: #64748b;">
                    Dari seluruh klien platform
                </div>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="saas-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b;">Transaksi Platform</p>
                        <h3 class="stat-value mb-0">{{ number_format($stats['total_transactions']) }}</h3>
                    </div>
                    <div class="icon-box success">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 mt-3" style="font-size: 0.85rem; color: #64748b;">
                    Diproses oleh seluruh klien
                </div>
            </div>
        </div>

        <!-- Total GMV -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="saas-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b;">Total Volume (GMV)</p>
                        <h3 class="stat-value mb-0" style="font-size: 1.5rem;">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                    </div>
                    <div class="icon-box warning">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 mt-3" style="font-size: 0.85rem; color: #64748b;">
                    Nilai Transaksi Bruto
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Tables -->
    <div class="row g-4 position-relative" style="z-index: 1;">
        <!-- Chart -->
        <div class="col-12 col-lg-7">
            <div class="saas-card p-4 h-100">
                <h5 class="fw-bold mb-4" style="color: #0f172a;">Pertumbuhan Transaksi Platform ({{ date('Y') }})</h5>
                <div style="height: 300px;">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Tenants -->
        <div class="col-12 col-lg-5">
            <div class="saas-card p-0 h-100 d-flex flex-column">
                <div class="p-4 border-bottom" style="border-color: #f1f5f9 !important;">
                    <h5 class="fw-bold mb-0" style="color: #0f172a;">Klien Baru Bergabung</h5>
                </div>
                <div class="table-responsive flex-grow-1">
                    <table class="table table-premium table-borderless mb-0">
                        <thead>
                            <tr>
                                <th>Brand / Pemilik</th>
                                <th>Terdaftar</th>
                                <th class="text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTenants as $tenant)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($tenant->brand_logo)
                                        <img src="{{ asset($tenant->brand_logo) }}" class="rounded shadow-sm" style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                        <div class="rounded d-flex align-items-center justify-content-center bg-light border" style="width: 32px; height: 32px;">
                                            <i class="bi bi-shop text-muted"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold text-dark">{{ $tenant->brand_name ?? 'Menunggu Pengaturan' }}</div>
                                            <div style="font-size: 0.75rem; color: #64748b;">{{ $tenant->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div style="font-size: 0.85rem; color: #475569;">{{ $tenant->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="align-middle text-end">
                                    @if($tenant->brand_name)
                                    <span class="tenant-badge">Aktif</span>
                                    @else
                                    <span class="tenant-badge" style="background: rgba(245, 158, 11, 0.1); color: #d97706;">Tertunda</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Belum ada klien yang terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Prepare Data for Chart
    const rawData = @json($growthChart);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

    // Initialize array with 12 zeros
    const chartData = Array(12).fill(0);

    // Fill in the actual data
    rawData.forEach(item => {
        chartData[item.month - 1] = item.total;
    });

    const ctx = document.getElementById('growthChart').getContext('2d');

    // Create Gradient for Line
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(14, 165, 233, 0.3)');
    gradient.addColorStop(1, 'rgba(14, 165, 233, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Volume Bruto (Rp)',
                data: chartData,
                borderColor: '#0ea5e9',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#0ea5e9',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#ffffff',
                    titleColor: '#0f172a',
                    bodyColor: '#475569',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    padding: 12,
                    boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.1)',
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR'
                                }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#64748b',
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#64748b'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });
</script>
@endpush