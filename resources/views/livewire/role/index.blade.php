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
                        {{-- Kolom kanan: Search & tombol --}}
                        <div class="col-md-6 col-12">
                            <form wire:submit.prevent="applyFilter">
                                <div class="input-group">
                                    <input type="text" wire:model.defer="search" class="form-control"
                                        placeholder="Ketik kata kunci...">
                                    <button class="btn btn-primary btn-sm" type="submit">Cari</button>
                                    <button class="btn btn-secondary btn-sm" type="button" wire:click="resetFilter">
                                        <i class="ti ti-x"></i>
                                    </button>
                                    <a class="btn btn-primary" href="{{ route('roles.create') }}" wire:navigate>
                                        Tambah
                                    </a>
                                </div>
                            </form>
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
