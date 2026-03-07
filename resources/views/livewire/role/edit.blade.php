<div>
    @section('title', 'Edit Role')
    @include('livewire.component-alert')

    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Edit Role</h5>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="update">
                        <div class="row g-2 mb-2">
                            <div class="col mb-0">
                                <label for="name" class="mb-2">Name</label>
                                <input type="text" id="name" wire:model="name" class="form-control"
                                    placeholder="Enter Role Name" />
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col mb-0">
                                <label for="guard" class="mb-2">Guard</label>
                                <input type="text" id="guard" class="form-control" wire:model="guard_name" readonly />
                            </div>
                        </div>

                        @php
                            $groupedPermissions = collect($permissions)
                                ->groupBy(fn($item) => strtoupper(substr($item->name, 0, 1)))
                                ->map(fn($group) => $group->groupBy(fn($item) => explode('.', $item->name)[0]));
                        @endphp

                        <div class="col mb-2">
                            <label class="mb-2 fw-bold">Permission</label>
                            <div class="row">
                                @foreach ($groupedPermissions as $letter => $groups)
                                    <div class="col-md-3 mb-3">
                                        <strong class="fs-5 text-uppercase text-primary">{{ $letter }}</strong>
                                        @foreach ($groups as $groupName => $items)
                                            <div class="mt-2">
                                                <strong class="d-block fw-bold">
                                                    {{ ucwords(str_replace('_', ' ', $groupName)) }}
                                                </strong>
                                                <ul class="list-unstyled ms-3">
                                                    @foreach ($items as $value)
                                                        <li>
                                                            <div class="form-check">
                                                                <input type="checkbox"
                                                                    wire:model="selectedPermissions"
                                                                    value="{{ $value->name }}"
                                                                    class="form-check-input"
                                                                    id="perm-{{ $value->id }}">
                                                                <label class="form-check-label"
                                                                    for="perm-{{ $value->id }}">
                                                                    {{ ucwords(str_replace('_', ' ', $value->name)) }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <a href="{{ route('roles.index') }}" wire:navigate class="btn btn-outline-secondary me-2">
                                Back
                            </a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
