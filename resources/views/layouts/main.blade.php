<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<!--
-- Template : Mazer - Free and Open-source Bootstrap 5 Admin Dashboard Template and Landing Page
-- Source : http://zuramai.github.io/mazer
-- Crafted by : Saugi - https://saugi.me
-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | @yield('title')</title>
    
    <!-- 1. Panggil Ikon Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- 2. Panggil Font Nunito bawaan Mazer -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/style.css', 'resources/css/style-dark.css'])
    <link rel="stylesheet" href="{{ asset('assets/ext/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ext/sweetalert2/sweetalert2.min.css') }}">

    <script>
        const body = document.body;
        const theme = localStorage.getItem('theme')

        if (theme)
            document.documentElement.setAttribute('data-bs-theme', theme)
    </script>

    @yield('script_tag')
</head>

<body>

    <div id="app">
        <div id="main" class="layout-horizontal">
            <header class="mb-5">
                <div class="header-top">
                    <div class="container">
                        <div class="logo">
                            <a href="{{ route('dashboard') }}">
                                <img src="{{ asset('assets/logo.png') }}" alt="logo" style="height: 50px">
                                {{-- <span class="fs-4 font-bold">{{config('app.name')}}</span>  --}}
                            </a>
                        </div>
                        <div class="header-top-right">
                            @if (!empty($shift))
                                <div>
                                    <button class="btn btn-outline-danger" id="btnCloseShift"
                                        data-id="{{ $shift->id_user_shift }}">Close Shift</button>
                                </div>
                            @else
                                <div>
                                    <button class="btn btn-outline-primary" id="btnOpenShift">Open Shift</button>
                                </div>
                            @endif
                            <div class="dropdown">
                                <a href="#" id="topbarUserDropdown"
                                    class="user-dropdown d-flex align-items-center dropend dropdown-toggle "
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="p-0 me-2">
                                        <i class="bi bi-person-circle fs-2"></i>
                                    </div>
                                    <div class="text">
                                        <h6 class="user-dropdown-name">{{ Auth::user()->name }}</h6>
                                        <p class="user-dropdown-status text-sm text-muted">{{ Auth::user()->role }}</p>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg"
                                    aria-labelledby="topbarUserDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}"><i
                                                class="bi bi-person-gear"></i> Profile</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                <i class="bi bi-box-arrow-right"></i>
                                                {{ __('Log Out') }}
                                            </a>
                                        </form>
                                    </li>
                                </ul>
                            </div>

                            <!-- Burger button responsive -->
                            <a href="#" class="burger-btn d-block d-xl-none">
                                <i class="bi bi-justify fs-3"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <x-navigation />

            </header>

            <div class="content-wrapper container">

                <div class="page-heading">
                    <h3>@yield('title')</h3>
                </div>
                <div class="page-content">
                    <section class="row">
                        <div class="col-12">
                            @if (empty($shift))
                                {{-- Show alert open shift --}}
                                <div class="alert alert-warning">Reminder: You haven't opened your shift yet.
                                    Please do so to proceed with your tasks. Thank you!</div>
                                {{-- End show alert --}}
                            @endif

                            @yield('content')

                        </div>
                    </section>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Open Shift --}}
    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modalAddShift"
        aria-labelledby="modalAddShiftLabel">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAddShiftLabel">Open Shift</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form action="#" id="formAddShift">
                        @csrf
                        <div class="mt-3 mb-3">
                            <label for="initial_capital" class="form-label">Initial Capital</label>
                            <input type="number" name="initial_capital" id="initial_capital" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="information" class="form-label">Shift Information (Optional)</label>
                            <textarea name="information" id="information" class="form-control"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" form="formAddShift" id="submitAddShift">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- End Modal Open Shift --}}

    {{-- Modal Close Shift --}}
    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modalCloseShift"
        aria-labelledby="modalCloseShiftLabel">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalCloseShiftLabel">Close Shift</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form action="#" id="formCloseShift">
                        @csrf
                        <div class="mt-3 mb-3">
                            <label for="actual_cash" class="form-label">Actual Cash*</label>
                            <input type="number" name="actual_cash" id="actual_cash" class="form-control" required>
                        </div>
                        <div class="mt-3 mb-3">
                            <label for="cash_out" class="form-label">Cash Out*</label>
                            <input type="number" name="cash_out" id="cash_out" class="form-control"
                                value="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="cash_out_info" class="form-label">Cash Out Info (Required if cash out is
                                any)</label>
                            <textarea name="cash_out_info" id="cash_out_info" class="form-control"></textarea>
                        </div>
                        <input type="hidden" name="id_shift" id="id_shift" value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" form="formCloseShift" id="submitCloseShift">
                        Close Shift
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- End Modal Close Shift --}}

    <script src="{{ asset('assets/ext/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/ext/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/ext/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/dark.js') }}"></script>
    <script src="{{ asset('assets/js/horizontal-layout.js') }}"></script>


    @yield('script')

    @vite('resources/js/shift.js')
</body>

</html>
