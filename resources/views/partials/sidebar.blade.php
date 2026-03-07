@php
    $setting = \App\Models\SettingApp::first();
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
    $canAccessUserManagement = auth()->check() && auth()->user()->canAny($userManagementPermissions);
    $appName = $setting->thumbnail ?? 'Base App Template';
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical  menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" wire:navigate
            class="app-brand-link d-flex align-items-center flex-grow-1 text-decoration-none">
            <img src="{{ $logoUrl }}" width="50" alt="Logo" class="h-auto" />
            <span class="app-brand-text demo menu-text fw-bold ms-2">
                {{ $appName }}
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto" role="button"
            aria-label="Toggle menu" aria-controls="layout-menu" aria-expanded="true">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <li class="menu-item {{ $isHomeRoute ? 'active' : '' }}">
            <a href="{{ route('home') }}" wire:navigate class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>Home</div>
            </a>
        </li>

        <!-- Apps & Pages Header -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Apps & Pages</span>
        </li>

        @can('products.index')
            <!-- Products -->
            <li class="menu-item {{ $isProductRoute ? 'active' : '' }}">
                <a href="{{ route('products.index') }}" wire:navigate class="menu-link">
                    <i class="menu-icon tf-icons ti ti-files"></i>
                    <div>Product</div>
                </a>
            </li>
        @endcan

        @if ($canAccessUserManagement)
            <!-- User Management Header -->
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">User Management</span>
            </li>
        @endif

        @can('users.index')
            <!-- Users -->
            <li class="menu-item {{ $isUserRoute ? 'active' : '' }}">
                <a href="{{ route('users.index') }}" wire:navigate class="menu-link">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div>Users</div>
                </a>
            </li>
        @endcan

        @canany(['permissions.index', 'role.index'])
            <!-- Roles & Permissions -->
            <li class="menu-item {{ $rolePermissionMenuClass }}">
                <a href="javascript:void(0);" class="{{ $rolePermissionToggleClass }}"
                    aria-expanded="{{ $isRolePermissionOpen ? 'true' : 'false' }}">
                    <i class="menu-icon tf-icons ti ti-settings"></i>
                    <div data-i18n="Roles & Permissions">Roles & Permissions</div>
                </a>

                <ul class="menu-sub" @if ($rolePermissionSubmenuStyle) style="{{ $rolePermissionSubmenuStyle }}" @endif>
                    @can('role.index')
                        <li class="menu-item {{ $isRoleRoute ? 'active' : '' }}">
                            <a href="{{ route('roles.index') }}" wire:navigate class="menu-link">
                                <div data-i18n="Roles">Roles</div>
                            </a>
                        </li>
                    @endcan

                    @can('permissions.index')
                        <li class="menu-item {{ $isPermissionRoute ? 'active' : '' }}">
                            <a href="{{ route('permissions.index') }}" wire:navigate class="menu-link">
                                <div data-i18n="Permission">Permission</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
    </ul>
</aside>
