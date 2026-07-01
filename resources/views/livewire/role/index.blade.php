<div>
    @section('title', 'Role')
    @include('livewire.component-alert')

    <div>
        {{-- TABEL DATA --}}
        <div class="col-12 col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        {{-- Kolom kiri: Judul --}}
                        <div class="col-md-6 col-12 mb-3 mb-md-0">
                            <h5 class="mb-0">Data Role</h5>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="row mb-0">
                                <div class="col-12">
                                    <form wire:submit.prevent="applyFilter">
                                        <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
                                            <!-- SEARCH -->
                                            <div class="input-group input-group-sm" style="max-width: 400px;">
                                                <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                                                    placeholder="Ketik kata kunci...">

                                                <button class="btn btn-primary btn-sm" type="submit">
                                                    Cari
                                                </button>
                                            </div>
                                            <!-- SHOW ENTRIES -->
                                            <div class="d-flex align-items-center">
                                                <label class="mb-0 me-2">Show</label>

                                                <select wire:model="perPage"
                                                    wire:change="updatePerPage($event.target.value)"
                                                    class="form-select form-select-sm w-auto">
                                                    <option value="5">5</option>
                                                    <option value="10">10</option>
                                                    <option value="15">15</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="all">All</option>
                                                </select>

                                                <span class="ms-2">entries</span>
                                            </div>
                                            <!-- TOMBOL TAMBAH -->
                                            <a class="btn btn-primary btn-sm" href="{{ route('roles.create') }}" wire:navigate>
                                                Tambah
                                            </a>
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
                                    <th class="text-white">Nama</th>
                                    <th class="text-white">Permission</th>
                                    <th width="120px" class="text-center text-white">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datas as $index => $listData)
                                    <tr>
                                        <td class="text-center">{{ $datas->firstItem() + $index }}</td>
                                        <td>{{ $listData->name }}</td>
                                        <td>
                                            @foreach ($listData->permissions as $perm)
                                                <span
                                                    class="badge rounded-pill bg-label-primary mb-1">{{ $perm->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('roles.edit', $listData->id) }}" wire:navigate
                                                    class="btn btn-sm btn-warning me-1 btn-icon" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <button wire:click="confirmDelete({{ $listData->id }})"
                                                    class="btn btn-sm btn-danger btn-icon" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            Tidak ada data role ditemukan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $datas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DELETE --}}
    @if ($showConfirm)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger fw-bold">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus role ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="closeModal">Batal</button>
                        <button class="btn btn-danger" wire:click="delete">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
