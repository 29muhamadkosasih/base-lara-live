@php
    $vendorScripts = [
        'https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.6.0.min.js',
    ];

    $appScripts = [
        'assets/js/helpers.js',
        'assets/js/config.js',
        'assets/js/popper.js',
        'assets/js/bootstrap.js',
        'assets/js/node-waves.js',
        'assets/js/perfect-scrollbar.js',
        'assets/js/hammer.js',
        'assets/js/i18n.js',
        'assets/js/typeahead.js',
        'assets/js/menu.js',
        'assets/js/main.js',
        'assets/js/toastr.js',
        'assets/js/ui-toasts.js',
        'assets/js/extended-ui-sweetalert2.js',
        'assets/js/sweetalert2.js',
        'assets/js/bootstrap-select.js',
        'assets/js/select2.js',
        'assets/js/forms-selects.js',
    ];

    $toastTypes = ['success', 'error', 'info', 'warning'];
@endphp

<!-- Scripts -->
@foreach ($vendorScripts as $script)
    <script src="{{ $script }}" data-navigate-track></script>
@endforeach

<!-- JavaScript -->
@foreach ($appScripts as $script)
    <script src="{{ asset($script) }}" data-navigate-track></script>
@endforeach

<script data-navigate-once>
    window.appLayout = window.appLayout || {};

    window.appLayout.resetDropdownState = function(root = document) {
        root.querySelectorAll('.dropdown.show').forEach((element) => {
            element.classList.remove('show');
        });

        root.querySelectorAll('.dropdown-menu.show').forEach((menu) => {
            menu.classList.remove('show');
            menu.removeAttribute('data-bs-popper');
        });

        root.querySelectorAll('[data-bs-toggle="dropdown"]').forEach((toggle) => {
            toggle.setAttribute('aria-expanded', 'false');
        });
    };

    window.appLayout.disposeBootstrapUi = function() {
        if (typeof bootstrap === 'undefined') {
            return;
        }

        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((element) => {
            bootstrap.Tooltip.getInstance(element)?.dispose();
        });

        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach((element) => {
            bootstrap.Dropdown.getInstance(element)?.dispose();
        });

        window.appLayout.resetDropdownState();
    };

    window.appLayout.disposeMenu = function() {
        if (window.Helpers && window.Helpers.mainMenu && typeof window.Helpers.mainMenu.destroy === 'function') {
            window.Helpers.mainMenu.destroy();
            window.Helpers.mainMenu = null;
        }
    };

    window.appLayout.initMenu = function() {
        const layoutMenu = document.getElementById('layout-menu');

        if (!layoutMenu || typeof window.Menu === 'undefined' || !window.Helpers) {
            return;
        }

        window.appLayout.disposeMenu();

        const isHorizontalLayout = layoutMenu.classList.contains('menu-horizontal');

        window.Helpers.mainMenu = new window.Menu(layoutMenu, {
            orientation: isHorizontalLayout ? 'horizontal' : 'vertical',
            closeChildren: isHorizontalLayout,
            showDropdownOnHover: false,
        });

        window.Helpers.scrollToActive(false);
        window.Helpers.update();
    };

    window.appLayout.bindMenuTogglers = function() {
        document.querySelectorAll('.layout-menu-toggle').forEach((toggle) => {
            if (toggle.dataset.livewireBound === 'true') {
                return;
            }

            toggle.dataset.livewireBound = 'true';
            toggle.addEventListener('click', (event) => {
                event.preventDefault();

                if (!window.Helpers) {
                    return;
                }

                window.Helpers.toggleCollapsed();
            });
        });
    };

    window.appLayout.initBootstrapUi = function() {
        if (typeof bootstrap === 'undefined') {
            return;
        }

        window.appLayout.disposeBootstrapUi();

        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((element) => {
            bootstrap.Tooltip.getOrCreateInstance(element);
        });

        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach((element) => {
            bootstrap.Dropdown.getOrCreateInstance(element, {
                autoClose: element.dataset.bsAutoClose || true,
                display: element.dataset.bsDisplay || 'dynamic'
            });
        });
    };

    window.appLayout.closeOpenDropdowns = function() {
        if (typeof bootstrap === 'undefined') {
            return;
        }

        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach((element) => {
            const dropdown = bootstrap.Dropdown.getInstance(element);

            if (dropdown && element.getAttribute('aria-expanded') === 'true') {
                dropdown.hide();
            }
        });

        window.appLayout.resetDropdownState();
    };

    window.appLayout.closeMobileMenuOnNavigate = function() {
        if (!window.Helpers || !window.Helpers.isSmallScreen()) {
            return;
        }

        window.Helpers.setCollapsed(true);
    };

    if (!window.appLayout.navigateHandlerBound) {
        document.addEventListener('click', (event) => {
            const link = event.target.closest('[wire\\:navigate]');

            if (!link) {
                return;
            }

            window.appLayout.closeOpenDropdowns();
            window.appLayout.closeMobileMenuOnNavigate();
        });

        window.appLayout.navigateHandlerBound = true;
    }

    window.appLayout.boot = function() {
        window.appLayout.bindMenuTogglers();
        window.appLayout.initMenu();
        window.appLayout.initBootstrapUi();
    };

    document.addEventListener('livewire:navigated', () => {
        requestAnimationFrame(() => {
            window.appLayout.boot();
        });
    });
</script>

<script>
    window.appLayout = window.appLayout || {};
    window.appLayout.boot?.();

    window.appLayout.showToast = function(type, message) {
        if (typeof toastr === 'undefined' || !message) {
            return;
        }

        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right'
        };

        const toastType = ['success', 'error', 'info', 'warning'].includes(type) ? type : 'info';
        toastr[toastType](message);
    };

    if (!window.appLayout.livewireToastBound) {
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (event) => {
                window.appLayout.showToast(event.type, event.message);
            });
        });

        window.appLayout.livewireToastBound = true;
    }

    @foreach ($toastTypes as $type)
        @if (session()->has($type))
            window.appLayout.showToast(@js($type), @js(session($type)));
        @endif
    @endforeach

    @if (session()->has('message'))
        window.appLayout.showToast('success', @js(session('message')));
    @endif
</script>

@stack('scripts')
@livewireScripts
