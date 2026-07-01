<div wire:poll.30000ms>
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Aktivitas Terbaru</h5>
            <div class="d-flex align-items-center gap-2">
                {{-- Live indicator --}}
                <span class="badge d-flex align-items-center gap-1 px-2 py-1"
                      style="background:rgba(40,199,111,0.15); color:#28c76f; font-size:0.75rem; border-radius:20px;">
                    <span style="
                        width:7px; height:7px; border-radius:50%;
                        background:#28c76f; display:inline-block;
                        animation: livePulse 1.5s infinite;
                    "></span>
                    LIVE
                </span>
                <a href="{{ route('audit_logs.index') }}" wire:navigate class="btn btn-sm btn-outline-primary">Lihat semua</a>
            </div>
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

    <style>
        @keyframes livePulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.4; transform: scale(1.4); }
        }
    </style>
</div>
