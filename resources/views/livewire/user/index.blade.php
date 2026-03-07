<div>
    @section('title', 'User')
    @include('livewire.component-alert')
    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ $isEdit ? 'Edit User' : 'Tambah User' }}</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        <div class="mb-2">
                            <label class="mb-2">Nama</label>
                            <input type="text" wire:model.defer="name"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Enter" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label class="mb-2">Email</label>
                            <input type="email" wire:model.defer="email"
                                class="form-control @error('email') is-invalid @enderror" placeholder="Enter" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label class="mb-2">Password</label>
                            <input type="password" wire:model.defer="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="Enter"
                                @required(!$isEdit)>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label class="mb-2">Konfirmasi Password</label>
                            <input type="password" wire:model.defer="confirm_password"
                                class="form-control @error('confirm_password') is-invalid @enderror"
                                placeholder="Enter" @required(!$isEdit)>
                            @error('confirm_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2" wire:key="user-roles-checkbox-{{ $isEdit ? 'edit-' . $dataId : 'create' }}">
                            <label class="mb-2 d-block">Pilih Role</label>

                            <div class="border rounded p-3 @error('roles') border-danger @enderror @error('roles.*') border-danger @enderror">
                                <div class="row">
                                    @foreach ($availableRoles as $role)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input type="checkbox" id="role-{{ Illuminate\Support\Str::slug($role) }}"
                                                    class="form-check-input" wire:model.live="roles"
                                                    value="{{ $role }}">
                                                <label class="form-check-label"
                                                    for="role-{{ Illuminate\Support\Str::slug($role) }}">
                                                    {{ ucfirst($role) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @error('roles')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                            @error('roles.*')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="text-end mt-3">
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

        <div class="col-12 col-lg-8">
            <div class="card shadow-sm mb-4">


                <div class="card-header">
                    <div class="row align-items-center">
                        {{-- Kolom kiri: Judul --}}
                        <div class="col-md-6 col-12 mb-3 mb-md-0">
                            <h5 class="mb-0">Data User</h5>
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
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th width="5px" class="text-center text-white">No</th>
                                    <th class="text-white">Nama</th>
                                    <th class="text-white">Email</th>
                                    <th class="text-white">Role</th>
                                    <th width="120x" class="text-center text-white">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datas as $index => $listData)
                                    <tr>
                                        <td class="text-center">{{ $datas->firstItem() + $index }}</td>
                                        <td>{{ $listData->name }}</td>
                                        <td>{{ $listData->email }}</td>
                                        <td>
                                            @foreach ($listData->roles as $role)
                                                <span class="badge bg-label-primary">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group gap-1" role="group">

                                                <button wire:click="edit({{ $listData->id }})"
                                                    class="btn btn-icon btn-sm btn-warning">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <button wire:click="confirmDelete({{ $listData->id }})"
                                                    class="btn btn-sm btn-icon btn-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            Tidak ada data
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
    <div class="modal fade @if ($showConfirm) show d-block @endif" tabindex="-1"
        @if ($showConfirm) style="background-color: rgba(0,0,0,0.5);" @endif
        aria-hidden="{{ $showConfirm ? 'false' : 'true' }}">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title text-danger fw-bold">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus user ini?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="closeModal">Batal</button>
                    <button class="btn btn-danger" wire:click="delete">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>
