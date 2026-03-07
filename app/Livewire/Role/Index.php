<?php

namespace App\Livewire\Role;

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

    public function render()
    {
        $query = Role::with('permissions')->select('id', 'name')->latest();

        if (!empty($this->filter)) {
            $query->where('name', 'like', '%' . $this->filter . '%');
        }

        return view('livewire.role.index', [
            'datas' => $query->paginate(5),
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
            $role->delete();
            $this->toast('message', 'Data berhasil dihapus.');
        } else {
            $this->toast('warning', 'Data tidak ditemukan.');
        }

        $this->closeModal();
    }
}
