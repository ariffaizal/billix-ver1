<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | Login</title>
    @vite(['resources/css/style.css', 'resources/css/style-dark.css', 'resources/css/auth.css'])
    <style>
        #auth #auth-right {
            height: 100%;
            background-image: url('{{ asset('assets/bg.jpg') }}');
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>

<body>
    <script>
        const body = document.body;
        const theme = localStorage.getItem('theme')

        if (theme)
            document.documentElement.setAttribute('data-bs-theme', theme)
    </script>
    <div id="auth">

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <img src="{{ asset('assets/logo.png') }}" alt="logo" style="height: 200px">
                        {{-- <span class="fs-2">{{ config('app.name') }}</span> --}}
                    </div>
                    <div class="mt-8">&nbsp;</div>
                    <h2 class="mt-8">Log in.</h2>
                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" placeholder="Username"
                                id="username" name="username" value="{{ old('username') }}" autocomplete="username"
                                required>
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" placeholder="Password"
                                id="password" name="password" required>
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" type="checkbox" id="remember_me">
                            <label class="form-check-label text-gray-600" for="remember_me">
                                Remember me
                            </label>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">

                </div>
            </div>
        </div>

    </div>
</body>

</html>
