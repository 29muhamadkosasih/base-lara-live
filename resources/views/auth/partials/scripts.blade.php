@php
    $vendorScripts = [
        'https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.6.0.min.js',
    ];

    $scripts = [
        'assets/js/helpers.js',
        'assets/js/config.js',
        'assets/js/popper.js',
        'assets/js/main.js',
        'assets/js/toastr.js',
        'assets/js/ui-toasts.js',
        'assets/js/pages-auth.js',
    ];

    $toastTypes = ['success', 'error', 'info', 'warning'];
@endphp

<!-- Scripts -->
@foreach ($vendorScripts as $script)
    <script src="{{ $script }}" data-navigate-track></script>
@endforeach

<!-- JavaScript -->
@foreach ($scripts as $script)
    <script src="{{ asset($script) }}" data-navigate-track></script>
@endforeach

<script>
    function disableSubmitButton(event) {
        event.preventDefault();

        document.getElementById('submit-btn')?.classList.add('d-none');
        document.getElementById('loading-btn')?.classList.remove('d-none');

        event.target.submit();
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof toastr === 'undefined') {
            return;
        }

        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right'
        };

        @foreach ($toastTypes as $type)
            @if (session()->has($type))
                toastr.{{ $type }}(@js(session($type)));
            @endif
        @endforeach
        @if (session()->has('message'))
            toastr.success(@js(session('message')));
        @endif
    });
</script>

@stack('scripts')
