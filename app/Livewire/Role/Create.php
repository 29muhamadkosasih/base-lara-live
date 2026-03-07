<?php

namespace App\Livewire\Role;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('Tambah Role')]
class Create extends Component
{
    public $name;
    public $guard_name = 'web';
    public $selectedPermissions = [];

    protected function rules()
    {
        return [
            'name' => ['required', 'min:3', Rule::unique('roles', 'name')],
            'selectedPermissions' => ['array'],
            'selectedPermissions.*' => ['exists:permissions,name'],
        ];
    }

    public function render()
    {
        $permissions = Permission::query()->orderBy('name')->get(['id', 'name']);

        return view('livewire.role.create', compact('permissions'));
    }

    protected function toast(string $type, string $message): void
    {
        session()->flash($type, $message);
        $this->dispatch('notify', type: $type === 'message' ? 'success' : $type, message: $message);
    }

    public function store()
    {
        $this->validate();

        $role = Role::create([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);

        $role->syncPermissions($this->selectedPermissions);

        $this->toast('message', 'Data berhasil dibuat!');
        return $this->redirectRoute('roles.index', navigate: true);
    }
}
