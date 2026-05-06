<div>
    @section('title', 'Audit Logs')
    @include('livewire.component-alert')

    <div class="row">
        {{-- TABEL DATA --}}
        <div class="col-12 col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6 col-12 mb-3 mb-md-0">
                            <h5 class="mb-0">Data Audit Logs</h5>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="row mb-0">
                                <div class="col-12">
                                    <form wire:submit.prevent="applyFilter">
                                        <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">

                                            <!-- SEARCH -->
                                            <div class="input-group input-group-sm" style="max-width: 400px;">
                                                <input type="text" wire:model.defer="search" class="form-control"
                                                    placeholder="Ketik kata kunci...">

                                                <button class="btn btn-primary btn-sm" type="submit">Cari</button>

                                                <button class="btn btn-secondary btn-sm" type="button"
                                                    wire:click="resetFilter">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </div>

                                            <!-- SHOW ENTRIES -->
                                            <div class="d-flex align-items-center">
                                                <label class="mb-0 me-2">Show</label>

                                                <select wire:model="perPage" class="form-select form-select-sm w-auto"
                                                    wire:change="updatePerPage($event.target.value)">
                                                    <option value="10">10</option>
                                                    <option value="15">15</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="all">All</option>
                                                </select>

                                                <span class="ms-2">entries</span>
                                            </div>

                                        </div>
                                    </form>
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
                                    <th width="5px" class="text-center text-white">No</th>
                                    <th class="text-white">Tanggal</th>
                                    <th class="text-white">User</th>
                                    <th class="text-white">Action</th>
                                    <th class="text-white">Subject</th>
                                    <th class="text-white">Description</th>
                                    <th class="text-white">Properties</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datas as $index => $log)
                                    <tr>
                                        <td class="text-center">{{ $datas->firstItem() + $index }}</td>
                                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ $log->user?->name ?? '-' }}</td>
                                        <td>{{ $log->action }}</td>
                                        <td>{{ $log->subject_type }} #{{ $log->subject_id }}</td>
                                        <td>{{ $log->description }}</td>
                                        <td style="max-width:240px; white-space:pre-wrap; overflow:auto;">
                                            @if ($log->properties)
                                                <pre class="mb-0 small">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-3">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3">
                        {{ $datas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
