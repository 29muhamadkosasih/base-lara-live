<div>
    @section('title', 'Produk')
    @include('livewire.component-alert')

    <div class="row">
        {{-- ================= FORM TAMBAH / EDIT ================= --}}
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ $isEdit ? 'Edit Produk' : 'Tambah Produk' }}</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        {{-- Nama Produk --}}
                        <div class="mb-2">
                            <label for="name" class="mb-2">Nama Produk</label>
                            <input type="text" wire:model.defer="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Masukkan nama produk...">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Detail Produk --}}
                        <div class="mb-2">
                            <label for="detail" class="mb-2">Detail Produk</label>
                            <textarea wire:model.defer="detail" id="detail" rows="3"
                                class="form-control @error('detail') is-invalid @enderror" placeholder="Masukkan detail produk..."></textarea>
                            @error('detail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-outline-secondary me-2" wire:click="resetInput">
                                {{ $isEdit ? 'Back' : 'Reset' }}
                            </button>
                            <button type="submit" class="btn btn-primary">
                                {{ $isEdit ? 'Update' : 'Submit' }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- ================= TABEL DATA ================= --}}
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        {{-- Kolom kiri: Judul --}}
                        <div class="col-md-6 col-12 mb-3 mb-md-0">
                            <h5 class="mb-0">Data Produk</h5>
                        </div>
                        {{-- Kolom kanan: Form Search dan Tombol --}}
                        <div class="col-md-6 col-12">
                            <form wire:submit.prevent="applyFilter">
                                <div class="input-group">
                                    <input type="text" wire:model.defer="search" class="form-control"
                                        placeholder="Ketik kata kunci...">
                                    <button class="btn btn-primary btn-sm" type="submit"> Cari
                                    </button>
                                    <button class="btn btn-secondary btn-sm" type="button" wire:click="resetFilter">
                                        <i class="ti ti-x"></i>
                                    </button>
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
                                    <th class="text-white">Detail</th>
                                    <th width="120px" class="text-center text-white">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datas as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $datas->firstItem() + $index }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->detail }}</td>

                                        <td class="text-center">
                                            <div class="btn-group gap-1" role="group" aria-label="Aksi">

                                                <button wire:click="edit({{ $item->id }})"
                                                    class="btn btn-warning btn-icon btn-sm me-1">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <button wire:click="confirmDelete({{ $item->id }})"
                                                    class="btn btn-danger btn-icon btn-sm">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">
                                            Tidak ada data
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="p-3">
                        {{ $datas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MODAL DELETE ================= --}}
    <div class="modal fade @if ($showConfirm) show d-block @endif" tabindex="-1"
        @if ($showConfirm) style="background-color: rgba(0,0,0,0.5);" @endif
        aria-labelledby="deleteModalLabel" aria-hidden="{{ $showConfirm ? 'false' : 'true' }}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title text-danger fw-bold">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>
