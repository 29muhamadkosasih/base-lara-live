<div>
    @section('title', 'System Logs')
    @include('livewire.component-alert')

    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        {{-- Kolom kiri: Judul --}}
                        <div class="col-md-4 col-12 mb-3 mb-md-0">
                            <h5 class="mb-0">System Log Viewer</h5>
                        </div>
                        {{-- Kolom kanan: Form Search, Filter Level, Perpage dan Tombol Action --}}
                        <div class="col-md-8 col-12">
                            <div class="row mb-0">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
                                        <!-- SEARCH -->
                                        <div class="input-group input-group-sm" style="max-width: 300px;">
                                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                                                placeholder="Ketik kata kunci...">
                                            @if($search || $level)
                                                <button class="btn btn-secondary btn-sm" type="button"
                                                    wire:click="$set('search', '')">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            @endif
                                        </div>

                                        <!-- FILTER LEVEL -->
                                        <div class="d-flex align-items-center">
                                            <select wire:model.live="level" class="form-select form-select-sm w-auto">
                                                <option value="">Semua Level</option>
                                                <option value="DEBUG">DEBUG</option>
                                                <option value="INFO">INFO</option>
                                                <option value="NOTICE">NOTICE</option>
                                                <option value="WARNING">WARNING</option>
                                                <option value="ERROR">ERROR</option>
                                                <option value="CRITICAL">CRITICAL</option>
                                                <option value="ALERT">ALERT</option>
                                                <option value="EMERGENCY">EMERGENCY</option>
                                            </select>
                                        </div>

                                        <!-- SHOW ENTRIES -->
                                        <div class="d-flex align-items-center">
                                            <label class="mb-0 me-2">Show</label>
                                            <select wire:model.live="perPage" class="form-select form-select-sm w-auto">
                                                <option value="15">15</option>
                                                <option value="30">30</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                            <span class="ms-2">entries</span>
                                        </div>

                                        <!-- TOMBOL KOSONGKAN -->
                                        <button type="button" class="btn btn-danger btn-sm" wire:click="$set('showConfirm', true)">
                                            Kosongkan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle mb-0">
                            <thead class="bg-primary">
                                <tr>
                                    <th width="180" class="text-white">Waktu</th>
                                    <th width="120" class="text-center text-white">Level</th>
                                    <th width="80" class="text-center text-white">Env</th>
                                    <th class="text-white">Pesan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $index => $log)
                                    @php
                                        $badgeClass = 'bg-secondary';
                                        if (in_array($log['level'], ['ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY'])) {
                                            $badgeClass = 'bg-danger';
                                        } elseif ($log['level'] === 'WARNING') {
                                            $badgeClass = 'bg-warning text-dark';
                                        } elseif (in_array($log['level'], ['INFO', 'NOTICE'])) {
                                            $badgeClass = 'bg-info';
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-nowrap small text-muted">
                                            <i class="ti ti-calendar me-1"></i>{{ $log['timestamp'] }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $badgeClass }} px-2 py-1" style="font-size: 0.75rem;">
                                                {{ $log['level'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-label-secondary px-2 py-1" style="font-size: 0.75rem;">
                                                {{ $log['env'] }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <div class="fw-semibold text-dark">{{ $log['title'] }}</div>
                                            @if ($log['stacktrace'])
                                                <div class="mt-2">
                                                    <button class="btn btn-xs btn-outline-secondary p-1 py-0" type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#stack-{{ $index }}" 
                                                        aria-expanded="false" 
                                                        aria-controls="stack-{{ $index }}">
                                                        <i class="ti ti-code me-1"></i> Lihat Stacktrace
                                                    </button>
                                                    <div class="collapse mt-2" id="stack-{{ $index }}">
                                                        <pre class="bg-dark text-light p-3 rounded-3 small overflow-auto text-start" style="max-height: 250px; font-family: monospace; white-space: pre-wrap; font-size: 0.8rem;">{{ $log['stacktrace'] }}</pre>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            Tidak ada data log ditemukan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION MANUAL -->
                    @if ($totalPages > 1)
                        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                            <div>
                                <small class="text-muted">Total data log: <strong>{{ $totalLogs }}</strong> baris</small>
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                                        <button class="page-item page-link" wire:click="setPage({{ $currentPage - 1 }})">Prev</button>
                                    </li>
                                    
                                    @php
                                        $start = max(1, $currentPage - 2);
                                        $end = min($totalPages, $currentPage + 2);
                                    @endphp
        
                                    @if($start > 1)
                                        <li class="page-item"><button class="page-link" wire:click="setPage(1)">1</button></li>
                                        @if($start > 2)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                            @endif

                            @for ($page = $start; $page <= $end; $page++)
                                <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                                    <button class="page-link" wire:click="setPage({{ $page }})">{{ $page }}</button>
                                </li>
                            @endfor

                            @if($end < $totalPages)
                                @if($end < $totalPages - 1)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                                <li class="page-item"><button class="page-link" wire:click="setPage({{ $totalPages }})">{{ $totalPages }}</button></li>
                            @endif

                            <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                                <button class="page-link" wire:click="setPage({{ $currentPage + 1 }})">Next</button>
                            </li>
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL DELETE --}}
    <div class="modal fade @if ($showConfirm) show d-block @endif" tabindex="-1"
        @if ($showConfirm) style="background-color: rgba(0,0,0,0.5);" @endif
        aria-hidden="{{ $showConfirm ? 'false' : 'true' }}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title text-danger fw-bold"><i class="ti ti-alert-triangle me-1"></i>Konfirmasi Kosongkan Log</h5>
                    <button type="button" class="btn-close" wire:click="$set('showConfirm', false)"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus semua isi file log sistem? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="$set('showConfirm', false)">Batal</button>
                    <button class="btn btn-danger" wire:click="clearLog">Hapus Semua</button>
                </div>
            </div>
        </div>
    </div>
</div>
