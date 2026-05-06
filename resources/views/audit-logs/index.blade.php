@extends('layouts.app')
@section('title', 'Audit Log')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Audit Log</h5>
            <small class="text-muted">Jejak perubahan data siapa, kapan, dan apa yang diubah.</small>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle mb-0">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white">Waktu</th>
                            <th class="text-white">User</th>
                            <th class="text-white">Aksi</th>
                            <th class="text-white">Objek</th>
                            <th class="text-white">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td class="text-nowrap">{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                                <td class="text-nowrap">{{ $log->user?->name ?? 'System' }}</td>
                                <td><span class="badge bg-label-primary text-uppercase">{{ $log->action }}</span></td>
                                <td class="text-nowrap">{{ class_basename($log->subject_type ?? '-') }}
                                    #{{ $log->subject_id ?? '-' }}</td>
                                <td>{{ $log->description }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada audit log.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="p-3">
            {{ $logs->links() }}
        </div>
    </div>
@endsection
