<?php

namespace App\Livewire\Layout;

use App\Models\SettingApp;
use App\Models\User;
use Livewire\Component;

class Sidebar extends Component
{
    public function render()
    {
        $setting = SettingApp::first();
        $logoUrl = $setting?->logo
            ? asset('storage/uploads/logos/' . $setting->logo)
            : asset('assets/img/favicon/favicon.ico');

        $userManagementPermissions = ['permissions.index', 'role.index', 'users.index'];
        $isHomeRoute = request()->routeIs('home');
        $isProductRoute = request()->routeIs('products.*');
        $isUserRoute = request()->routeIs('users.*');
        $isRoleRoute = request()->routeIs('roles.*');
        $isPermissionRoute = request()->routeIs('permissions.*');

        $isRolePermissionOpen = $isRoleRoute || $isPermissionRoute;
        $rolePermissionMenuClass = $isRolePermissionOpen ? 'open active' : '';
        $rolePermissionToggleClass = $isRolePermissionOpen ? 'menu-link menu-toggle active' : 'menu-link menu-toggle';
        $rolePermissionSubmenuStyle = $isRolePermissionOpen ? 'display: block;' : '';
        /** @var User|null $user */
        $user = auth()->user();
        $canAccessUserManagement = $user?->canAny($userManagementPermissions) ?? false;
        $appName = $setting->thumbnail ?? 'Base App Template';

        return view('livewire.layout.sidebar', compact(
            'logoUrl',
            'isHomeRoute',
            'isProductRoute',
            'isUserRoute',
            'isRoleRoute',
            'isPermissionRoute',
            'rolePermissionMenuClass',
            'rolePermissionToggleClass',
            'rolePermissionSubmenuStyle',
            'canAccessUserManagement',
            'appName'
        ));
    }
}
