<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
#[Title('User')]
class Index extends Component
{
    use WithPagination;

    public $dataId;
    public $name;
    public $email;
    public $password;
    public $confirm_password;
    public $availableRoles = [];
    public $roles = [];
    public $isEdit = false;
    public $showConfirm = false;

    public $search = '';
    public $filter = '';

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'email' => $this->isEdit ? 'required|email|unique:users,email,' . $this->dataId : 'required|email|unique:users,email',
            'password' => $this->isEdit ? 'nullable|same:confirm_password|min:6' : 'required|same:confirm_password|min:6',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
        ];
    }

    public function mount()
    {
        $this->availableRoles = Role::query()->orderBy('name')->pluck('name')->toArray();
    }

    public function render()
    {
        $datas = User::query()
            ->with('roles')
            ->when($this->filter, fn ($q) => $q->where('name', 'like', '%' . $this->filter . '%'))
            ->latest()
            ->paginate(10);

        return view('livewire.user.index', compact('datas'));
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

    public function resetInput()
    {
        $this->reset(['name', 'email', 'password', 'confirm_password', 'roles', 'dataId', 'isEdit']);
        $this->resetValidation();
    }

    protected function toast(string $type, string $message): void
    {
        session()->flash($type, $message);
        $this->dispatch('notify', type: $type === 'message' ? 'success' : $type, message: $message);
    }

    public function store()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $user->syncRoles($this->roles);
            DB::commit();

            $this->toast('message', 'User berhasil ditambahkan.');
            $this->resetInput();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->toast('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data = User::findOrFail($id);

        $this->dataId = $data->id;
        $this->name = $data->name;
        $this->email = $data->email;
        $this->roles = $data->roles->pluck('name')->toArray();
        $this->isEdit = true;
        $this->resetValidation();
    }

    public function update()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $user = User::findOrFail($this->dataId);

            $data = [
                'name' => $this->name,
                'email' => $this->email,
            ];

            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }

            $user->update($data);

            $user->syncRoles($this->roles);

            DB::commit();

            $this->toast('message', 'Data berhasil diperbarui.');
            $this->resetInput();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->toast('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
        User::find($this->dataId)?->delete();

        $this->toast('message', 'Data berhasil dihapus.');
        $this->closeModal();
        $this->resetInput();
    }
}
