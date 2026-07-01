@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
<style>
    .stat-card {
        border: none;
        border-radius: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12) !important;
    }
    .stat-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }
    .chart-card {
        border: none; border-radius: 12px;
    }
</style>
@endpush

@section('content')

    {{-- ===== STAT CARDS ===== --}}
    <div class="row g-4 mb-4">

        {{-- Total User --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(105,108,255,0.15); color:#696cff;">
                        <i class="ti ti-users"></i>
                    </div>
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold">Total User</span>
                        <h3 class="mb-0 mt-1">{{ $stats['users'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Product --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(255,171,0,0.15); color:#ffab00;">
                        <i class="ti ti-box"></i>
                    </div>
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold">Total Product</span>
                        <h3 class="mb-0 mt-1">{{ $stats['products'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Aktivitas Hari Ini --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(40,199,111,0.15); color:#28c76f;">
                        <i class="ti ti-activity"></i>
                    </div>
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold">Aktivitas Hari Ini</span>
                        <h3 class="mb-0 mt-1">{{ $stats['today_activities'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Log Aktivitas --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(234,84,85,0.15); color:#ea5455;">
                        <i class="ti ti-chart-bar"></i>
                    </div>
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold">Total Log Aktivitas</span>
                        <h3 class="mb-0 mt-1">{{ $stats['activities'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== CHART + PRODUK TERBARU ===== --}}
    <div class="row g-4 mb-4">

        {{-- Chart Aktivitas 7 Hari --}}
        <div class="col-12 col-lg-8">
            <div class="card chart-card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Grafik Aktivitas 7 Hari Terakhir</h5>
                    <span class="badge bg-label-primary">{{ now()->subDays(6)->format('d M') }} — {{ now()->format('d M Y') }}</span>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- Produk Terbaru --}}
        <div class="col-12 col-lg-4">
            <div class="card chart-card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Produk Terbaru</h5>
                    <a href="{{ route('products.index') }}" wire:navigate class="btn btn-sm btn-outline-primary">Lihat semua</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($latestProducts as $product)
                            <li class="list-group-item d-flex align-items-center gap-3 py-3">
                                @if($product->cover_image)
                                    <img src="{{ asset('storage/uploads/cover_images/' . $product->cover_image) }}"
                                         alt="{{ $product->name }}"
                                         class="rounded" style="width:40px;height:40px;object-fit:cover;">
                                @else
                                    <div class="rounded d-flex align-items-center justify-content-center"
                                         style="width:40px;height:40px;background:rgba(105,108,255,0.1);color:#696cff;">
                                        <i class="ti ti-box"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="fw-semibold text-truncate">{{ $product->name }}</div>
                                    <small class="text-muted">{{ $product->created_at->diffForHumans() }}</small>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">Belum ada produk.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== LIVE RECENT ACTIVITY (Livewire) ===== --}}
    <livewire:dashboard.recent-activity />

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    function initChart() {
        const canvas = document.getElementById('activityChart');
        if (!canvas) return;

        // Destroy existing chart instance if any (Livewire navigate)
        const existing = Chart.getChart(canvas);
        if (existing) existing.destroy();

        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
        const textColor = isDark ? '#b0b9c9' : '#6c757d';

        const labels = @json($chartData['labels']);
        const values = @json($chartData['values']);

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Aktivitas',
                    data: values,
                    backgroundColor: 'rgba(105,108,255,0.75)',
                    borderColor: 'rgba(105,108,255,1)',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y} aktivitas`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: gridColor },
                        ticks: { color: textColor }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: textColor, precision: 0 }
                    }
                }
            }
        });
    }

    // Init on first load
    document.addEventListener('DOMContentLoaded', initChart);
    // Re-init on Livewire navigate
    document.addEventListener('livewire:navigated', initChart);
})();
</script>
@endpush
