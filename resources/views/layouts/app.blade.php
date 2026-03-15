@php
    $setting = \App\Models\SettingApp::first();
    $logoUrl = $setting?->logo ? asset('storage/uploads/logos/' . $setting->logo) : asset('assets/img/favicon/favicon.ico');
    $pageTitle = $title ?? trim($__env->yieldContent('title'));
    $appName = $setting->brand ?? 'Base App Template';
@endphp

<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-wide layout-menu-collapsed"
    dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="vertical-menu-template">

@include('layouts.partials.head', [
    'logoUrl' => $logoUrl,
    'pageTitle' => $pageTitle,
    'appName' => $appName,
])

<body>
    <!-- Layout Wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Sidebar Menu -->
            <livewire:layout.sidebar />

            <!-- Layout Page -->
            <div class="layout-page">
                <!-- Navbar -->
                <livewire:layout.navbar />

                <!-- Content Wrapper -->
                <div class="content-wrapper">
                    <div class="container-fluid flex-grow-1 container-p-y mb-1">
                        {{-- Untuk Blade biasa --}}
                        @yield('content')

                        {{-- Untuk Livewire v3 ({{ $slot }}) --}}
                        @isset($slot)
                            {{ $slot }}
                        @endisset
                    </div>
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- /Content Wrapper -->
            </div>
            <!-- /Layout Page -->
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    @include('layouts.partials.scripts')
</body>

</html>
