<?php

namespace App\Livewire\Role;

use App\Support\AuditLogger;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('Edit Role')]
class Edit extends Component
{
    public $roleId;
    public $name;
    public $guard_name = 'web';
    public $selectedPermissions = [];

    // Load data role saat komponen di-mount
    public function mount($id)
    {
        $role = Role::findOrFail($id);

        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
    }

    public function render()
    {
        $permissions = Permission::query()->orderBy('name')->get(['id', 'name']);

        return view('livewire.role.edit', compact('permissions'));
    }

    protected function toast(string $type, string $message): void
    {
        session()->flash($type, $message);
        $this->dispatch('notify', type: $type === 'message' ? 'success' : $type, message: $message);
    }

    public function update()
    {
        $this->validate([
            'name' => ['required', 'min:3', Rule::unique('roles', 'name')->ignore($this->roleId)],
            'selectedPermissions' => ['array'],
            'selectedPermissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::findOrFail($this->roleId);

        $role->update([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);

        $role->syncPermissions($this->selectedPermissions);
        AuditLogger::log('role.updated', $role, 'Role diperbarui.', [
            'after' => [
                'name' => $this->name,
                'permissions' => $this->selectedPermissions,
            ],
        ]);

        $this->toast('message', 'Data berhasil diperbarui!');
        return $this->redirectRoute('roles.index', navigate: true);
    }
}
