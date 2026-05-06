@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <span class="text-muted small text-uppercase">Total User</span>
                    <h3 class="mb-0 mt-2">{{ $stats['users'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <span class="text-muted small text-uppercase">Total Product</span>
                    <h3 class="mb-0 mt-2">{{ $stats['products'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <span class="text-muted small text-uppercase">Aktivitas Hari Ini</span>
                    <h3 class="mb-0 mt-2">{{ $stats['today_activities'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <span class="text-muted small text-uppercase">Total Log Aktivitas</span>
                    <h3 class="mb-0 mt-2">{{ $stats['activities'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Aktivitas Terbaru</h5>
            <a href="{{ route('audit_logs.index') }}" wire:navigate class="btn btn-sm btn-outline-primary">Lihat semua</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentActivities as $log)
                        <tr>
                            <td class="text-nowrap">{{ $log->created_at->format('d M Y, H:i') }}</td>
                            <td>{{ $log->user?->name ?? 'System' }}</td>
                            <td><span class="badge bg-label-primary text-uppercase">{{ $log->action }}</span></td>
                            <td>{{ $log->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
