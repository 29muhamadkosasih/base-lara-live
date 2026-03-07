@php
    $setting = \App\Models\SettingApp::first();
    $logoUrl = $setting?->logo
        ? asset('storage/uploads/logos/' . $setting->logo)
        : asset('assets/img/favicon/favicon.ico');
    $user = auth()->user();
    $brandName = $setting->brand ?? 'Base App Template';
    $userName = $user?->name ?? 'User';
    $userRole = $user?->getRoleNames()->first() ?? 'User';
    $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0d6efd&color=ffffff';
@endphp
<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">
    <div class="navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4 layout-menu-toggle" href="javascript:void(0);" role="button"
            aria-label="Toggle navigation" aria-controls="layout-menu" aria-expanded="false">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center w-100">
        <div class="navbar-nav align-items-center w-100">
            <div class="nav-item navbar-search-wrapper mb-0 w-100">
                <div class="position-relative d-flex align-items-center w-100 min-vh-0">
                    <a href="{{ route('home') }}" wire:navigate
                        class="app-brand demo d-inline-flex d-md-none align-items-center text-decoration-none position-absolute start-50 translate-middle-x">
                        <img src="{{ $logoUrl }}" width="42" alt="Logo" class="h-auto d-block" />
                    </a>

                    <a href="{{ route('home') }}" wire:navigate
                        class="app-brand demo d-none d-md-inline-flex align-items-center text-decoration-none me-auto">
                        <h4 class="mb-0 text-start">{{ $brandName }}</h4>
                    </a>
                </div>
            </div>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" role="button"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" data-bs-display="static"
                    aria-expanded="false">
                    <div class="avatar avatar-online">
                        <img src="{{ $avatarUrl }}" alt="{{ $userName }}" class="rounded-circle" />
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <div class="dropdown-item-text">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ $avatarUrl }}" alt="{{ $userName }}"
                                            class="rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-medium d-block">{{ $userName }}</span>
                                    <small class="text-muted">{{ $userRole }}</small>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                    @can('setting-apps')
                        <li>
                            <a class="dropdown-item" href="{{ route('setting_apps.index') }}" wire:navigate>
                                <i class="ti ti-settings me-2 ti-sm"></i>
                                <span class="align-middle">Settings</span>
                            </a>
                        </li>
                    @endcan

                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ti ti-logout me-2 ti-sm"></i>
                            <span class="align-middle">Logout</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
