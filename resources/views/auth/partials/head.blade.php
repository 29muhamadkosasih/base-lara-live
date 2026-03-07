<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $logoUrl }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @php
        $styles = [
            'assets/css/fontawesome.css',
            'assets/css/tabler-icons.css',
            'assets/css/flag-icons.css',
            'assets/css/core.css',
            'assets/css/theme-default.css',
            'assets/css/demo.css',
            'assets/css/perfect-scrollbar.css',
            'assets/css/toastr.css',
            'assets/css/page-auth.css',
            'assets/css/animate.css',
        ];
    @endphp

    <!-- CSS -->
    @foreach ($styles as $style)
        <link rel="stylesheet" href="{{ asset($style) }}">
    @endforeach

    @stack('styles')
</head>
