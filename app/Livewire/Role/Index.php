<?php

namespace App\Livewire\Role;

use App\Support\AuditLogger;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('Role')]
class Index extends Component
{
    use WithPagination;

    public $dataId;
    public $showConfirm = false;
    public $search = '';
    public $filter = '';
    public $perPage = 5;

    protected $paginationTheme = 'bootstrap';

    // reset halaman ketika search berubah
    public function updatingSearch()
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
        $query = Role::with('permissions')->select('id', 'name')->latest();

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return view('livewire.role.index', [
            'datas' => $query->paginate($this->perPage),
        ]);
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

    protected function toast(string $type, string $message): void
    {
        session()->flash($type, $message);
        $this->dispatch('notify', type: $type === 'message' ? 'success' : $type, message: $message);
    }

    public function delete()
    {
        $role = Role::find($this->dataId);

        if ($role) {
            AuditLogger::log('role.deleted', $role, 'Role dihapus.', [
                'attributes' => $role->only(['name']),
            ]);

            $role->delete();
            $this->toast('message', 'Data berhasil dihapus.');
        } else {
            $this->toast('warning', 'Data tidak ditemukan.');
        }

        $this->closeModal();
    }
}
