<?php

namespace App\Livewire\Permission;

use App\Support\AuditLogger;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

#[Layout('layouts.app')]
#[Title('Permission')]
class Index extends Component
{
    use WithPagination;

    public $name, $dataId;
    public $isEdit = false;
    public $showConfirm = false;

    // filter/search manual
    public $search = '';
    public $filter = '';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function applyFilter()
    {
        $this->filter = $this->search;
        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->reset(['search', 'filter']);
        $this->resetPage();
    }

    public function updatePerPage($value)
    {
        $this->perPage = $value === 'all' ? PHP_INT_MAX : (int) $value;
        $this->resetPage();
    }

    public function render()
    {
        $datas = Permission::query()->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))->latest()->paginate($this->perPage);

        return view('livewire.permission.index', compact('datas'));
    }

    public function resetInput()
    {
        $this->reset(['name', 'dataId', 'isEdit']);
        $this->resetValidation();
    }

    protected function toast(string $type, string $message): void
    {
        session()->flash($type, $message);
        $this->dispatch('notify', type: $type === 'message' ? 'success' : $type, message: $message);
    }

    public function store()
    {
        $validated = $this->validate([
            'name' => 'required|min:3|unique:permissions,name',
        ]);

        $permission = Permission::create($validated);
        AuditLogger::log('permission.created', $permission, 'Permission baru ditambahkan.', [
            'attributes' => $validated,
        ]);

        $this->toast('message', 'Data berhasil ditambahkan.');
        $this->resetInput();
    }

    public function edit($id)
    {
        $data = Permission::findOrFail($id);
        $this->dataId = $data->id;
        $this->name = $data->name;
        $this->isEdit = true;
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => ['required', 'min:3', Rule::unique('permissions', 'name')->ignore($this->dataId)],
        ]);

        $permission = Permission::findOrFail($this->dataId);
        $before = $permission->only(['name']);
        $permission->update($validated);
        AuditLogger::log('permission.updated', $permission, 'Permission diperbarui.', [
            'before' => $before,
            'after' => $validated,
        ]);

        $this->toast('message', 'Data berhasil diperbarui.');
        $this->resetInput();
    }

    public function confirmDelete($id)
    {
        $this->dataId = $id;
        $this->showConfirm = true;
    }

    public function closeModal()
    {
        $this->showConfirm = false;
        $this->dataId = null;
    }

    public function delete()
    {
        $permission = Permission::find($this->dataId);

        if ($permission) {
            AuditLogger::log('permission.deleted', $permission, 'Permission dihapus.', [
                'attributes' => $permission->only(['name']),
            ]);

            $permission->delete();
        }

        $this->toast('message', 'Data berhasil dihapus.');
        $this->closeModal();
        $this->resetInput();
    }
}
