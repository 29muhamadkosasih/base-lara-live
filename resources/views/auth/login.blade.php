@php
    $setting = \App\Models\SettingApp::first();
    $logoUrl = $setting?->logo ? asset('storage/uploads/logos/' . $setting->logo) : asset('storage/default-logo.png');
    $pageTitle = 'Login';
@endphp

<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="assets/" data-template="vertical-menu-template-no-customizer">

@include('auth.partials.head', [
    'logoUrl' => $logoUrl,
    'pageTitle' => $pageTitle,
])

<body>
    <div class="authentication-wrapper authentication-cover authentication-bg">
        <div class="authentication-inner row">
            <!-- Left Illustration -->
            <div class="d-none d-lg-flex col-lg-8 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img src="{{ asset('assets/img/illustrations/freepik__background__1490.png') }}" alt="login-cover"
                        style="height: 80%" class="img-fluid my-5 auth-illustration">

                    <img src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}" alt="login-cover"
                        class="platform-bg">
                </div>
            </div>

            <!-- Login Form -->
            <div class="d-flex col-12 col-lg-4 align-items-center p-sm-4 p-4">
                <div class="w-px-400 mx-auto">
                    <div class="app-brand mb-2 text-start">
                        <a href="/" class="app-brand-link d-inline-flex align-items-center gap-2">
                            <img src="{{ $logoUrl }}" alt="Logo" height="55">
                        </a>
                    </div>
                    <p class="mb-2" style="font-size: 22px; font-weight: 600;">
                        {{ $setting->brand ?? 'Base App Template' }}</p>

                    <form method="POST" action="{{ route('login') }}" onsubmit="disableSubmitButton(event)">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" placeholder="Enter your email" autofocus required>
                            @error('email')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    placeholder="••••••••" required>
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            @error('password')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <!-- Remember Me Checkbox -->
                        <div class="mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me">
                                <label class="form-check-label" for="remember-me"> Remember Me </label>
                            </div>
                        </div>
                        <button type="submit" id="submit-btn" class="btn btn-primary d-grid w-100">Sign in</button>
                        <button id="loading-btn" class="btn btn-primary w-100 waves-effect waves-light d-none"
                            type="button" disabled>
                            <span class="spinner-grow me-1"></span> Loading...
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('auth.partials.scripts')

</body>

</html>
