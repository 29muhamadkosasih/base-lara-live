<div>
    @section('title', 'Keamanan Akun')
    @include('livewire.component-alert')

    <div class="row">
        {{-- ================= DAFTAR SESI AKTIF ================= --}}
        <div class="col-12 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="mb-0 fw-bold"><i class="ti ti-device-laptop me-2 text-primary"></i>Sesi Aktif</h5>
                        <small class="text-muted">Perangkat yang saat ini terhubung dengan akun Anda</small>
                    </div>
                    @if (count($sessions) > 1)
                        <button class="btn btn-outline-danger btn-xs" wire:click="logoutOtherDevices">
                            <i class="ti ti-logout me-1"></i> Keluarkan Perangkat Lain
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach ($sessions as $session)
                            <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    {{-- Device Icon --}}
                                    <div class="avatar avatar-md me-3 bg-label-primary rounded d-flex align-items-center justify-content-center">
                                        @if ($session['device_type'] === 'mobile')
                                            <i class="ti ti-device-mobile ti-md text-primary"></i>
                                        @elseif ($session['device_type'] === 'tablet')
                                            <i class="ti ti-device-tablet ti-md text-primary"></i>
                                        @else
                                            <i class="ti ti-device-laptop ti-md text-primary"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center">
                                            <span class="fw-semibold text-dark me-2">
                                                {{ $session['browser'] }} di {{ $session['platform'] }}
                                            </span>
                                            @if ($session['is_current_device'])
                                                <span class="badge bg-success" style="font-size: 0.7rem;">Sesi Ini</span>
                                            @endif
                                        </div>
                                        <small class="text-muted d-block">
                                            IP: <strong>{{ $session['ip_address'] }}</strong> &bull; Aktif: {{ $session['last_active']->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <div>
                                    @if (!$session['is_current_device'])
                                        @if ($confirmLogoutId === $session['id'])
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-danger btn-xs" wire:click="logoutSession('{{ $session['id'] }}')">Ya</button>
                                                <button class="btn btn-secondary btn-xs" wire:click="$set('confirmLogoutId', null)">Tidak</button>
                                            </div>
                                        @else
                                            <button class="btn btn-outline-danger btn-sm btn-icon" 
                                                wire:click="$set('confirmLogoutId', '{{ $session['id'] }}')" 
                                                title="Putuskan Koneksi Perangkat">
                                                <i class="ti ti-logout"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= TIMELINE AKTIVITAS AKUN ================= --}}
        <div class="col-12 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="ti ti-history me-2 text-primary"></i>Log Aktivitas Akun</h5>
                    <small class="text-muted">15 Riwayat aktivitas terakhir yang Anda lakukan pada sistem</small>
                </div>
                <div class="card-body">
                    <ul class="timeline timeline-dashed mt-3">
                        @forelse ($activities as $activity)
                            @php
                                $icon = 'ti-settings';
                                $color = 'bg-secondary';
                                if (strpos($activity->action, 'auth') !== false) {
                                    $icon = 'ti-key';
                                    $color = 'bg-primary';
                                } elseif (strpos($activity->action, 'created') !== false) {
                                    $icon = 'ti-plus';
                                    $color = 'bg-success';
                                } elseif (strpos($activity->action, 'updated') !== false) {
                                    $icon = 'ti-edit';
                                    $color = 'bg-warning';
                                } elseif (strpos($activity->action, 'deleted') !== false) {
                                    $icon = 'ti-trash';
                                    $color = 'bg-danger';
                                }
                                $ip = $activity->properties['ip_address'] ?? '-';
                                $ua = $activity->properties['user_agent'] ?? '';
                                $parsedUA = \App\Support\UserAgentParser::parse($ua);
                            @endphp
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point {{ $color }}">
                                    <i class="ti {{ $icon }} text-white" style="font-size: 0.9rem;"></i>
                                </span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0 fw-semibold text-dark">{{ $activity->description }}</h6>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1 text-muted small">
                                        Action: <code class="bg-light px-1 rounded">{{ $activity->action }}</code>
                                    </p>
                                    <div class="d-flex align-items-center flex-wrap gap-2 mt-2">
                                        <span class="badge bg-label-secondary" style="font-size: 0.7rem;">
                                            <i class="ti ti-world me-1"></i>IP: {{ $ip }}
                                        </span>
                                        @if($ua)
                                            <span class="badge bg-label-info" style="font-size: 0.7rem;">
                                                <i class="ti ti-device-laptop me-1"></i>{{ $parsedUA['browser'] }} ({{ $parsedUA['platform'] }})
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="text-center text-muted py-4">
                                Belum ada aktivitas terekam.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
    /* CSS Timeline kustom untuk visualisasi Bootstrap 5 yang serasi */
    .timeline {
        position: relative;
        padding-left: 1.5rem;
        list-style: none;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
        padding-left: 1.5rem;
        border-left: 1px dashed #d9dee3;
    }
    .timeline-item:last-child {
        border-left: none;
        padding-bottom: 0;
    }
    .timeline-point {
        position: absolute;
        left: -0.65rem;
        top: 0;
        width: 1.3rem;
        height: 1.3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 0 4px #fff;
        z-index: 2;
    }
    .timeline-event {
        position: relative;
        top: -3px;
    }
    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    </style>
</div>

