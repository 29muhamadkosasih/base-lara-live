<?php

namespace Tests\Feature;

use App\Livewire\Permission\Index as PermissionIndex;
use App\Livewire\Product\Index as ProductIndex;
use App\Livewire\Role\Create as RoleCreate;
use App\Livewire\User\Index as UserIndex;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class LivewireCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_authenticated_user_can_open_all_livewire_crud_pages(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $user->assignRole($role);

        $this->actingAs($user)
            ->get(route('products.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('permissions.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('users.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('roles.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('roles.create'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('roles.edit', $role->id))
            ->assertOk();
    }

    public function test_product_and_permission_can_be_created_from_livewire(): void
    {
        Livewire::test(ProductIndex::class)
            ->set('name', 'Produk Demo')
            ->set('detail', 'Detail produk demo')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('products', [
            'name' => 'Produk Demo',
            'detail' => 'Detail produk demo',
        ]);

        Livewire::test(PermissionIndex::class)
            ->set('name', 'reports.index')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('permissions', [
            'name' => 'reports.index',
            'guard_name' => 'web',
        ]);
    }

    public function test_role_and_user_can_be_created_from_livewire(): void
    {
        Permission::create([
            'name' => 'users.index',
            'guard_name' => 'web',
        ]);

        Livewire::test(RoleCreate::class)
            ->set('name', 'Manager')
            ->set('selectedPermissions', ['users.index'])
            ->call('store')
            ->assertRedirect(route('roles.index'));

        $this->assertDatabaseHas('roles', [
            'name' => 'Manager',
            'guard_name' => 'web',
        ]);

        Livewire::test(UserIndex::class)
            ->set('name', 'User Manager')
            ->set('email', 'manager@example.com')
            ->set('password', 'secret123')
            ->set('confirm_password', 'secret123')
            ->set('roles', ['Manager'])
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'name' => 'User Manager',
            'email' => 'manager@example.com',
        ]);

        $this->assertTrue(User::where('email', 'manager@example.com')->first()->hasRole('Manager'));
    }
}
