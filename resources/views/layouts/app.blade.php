<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Red Card Cafe CMS</title>
    <link rel="icon" type="image/x-icon" href="{{asset('img/favicon.ico')}}">

    <!-- Styles -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/pace-progress/themes/black/pace-theme-flat-top.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css?v=3.2.0') }}">
    @yield('css')

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>


<body class="hold-transition sidebar-mini pace-blue">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-dark" style="background-color: #E40100">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item mr-3">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block mx-2">
                    <a href="{{ url('support') }}" class="nav-link">Support</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block mx-2">
                    <a href="{{ url('about') }}" class="nav-link">About</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->fname }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/account">Account</a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <img src="{{ asset('img/red_card_cafe_logo-removebg-preview.png') }}" alt="Restaurant Logo"
                    class="brand-image">
                <span class="brand-text font-weight-light">Red Card Cafe</span>
            </a>
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <div class="form-inline">
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column">
                            @role('master-admin')
                            <li class="nav-item">
                                <a href="{{ url('dashboard') }}"
                                    class="nav-link {{ request()->is('dashboard*') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-chart-line"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('order-queue') }}"
                                    class="nav-link {{ request()->is('order-queue*') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-list-ul"></i>
                                    <p>
                                        Order Queue
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('order-history') }}"
                                    class="nav-link {{ request()->is('order-history*') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-history"></i>
                                    <p>
                                        Order History
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('billing') }}"
                                    class="nav-link {{ request()->is('billing*') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-credit-card"></i>
                                    <p>
                                        Billing
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('staff') }}"
                                    class="nav-link {{ request()->is('staff*') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Staff
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('menu') }}"
                                    class="nav-link {{ request()->is('menu*') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-utensils"></i>
                                    <p>
                                        Menu
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('analyzation') }}"
                                    class="nav-link {{ request()->is('analyzation*') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-chart-area"></i>
                                    <p>
                                        Menu Analyzation
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('table') }}"
                                    class="nav-link {{ request()->is('table*') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-table"></i>
                                    <p>
                                        Table
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('tax') }}" class="nav-link {{ request()->is('tax*') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-percent"></i>
                                    <p>
                                        Tax
                                    </p>
                                </a>
                            </li>
                            @endrole

                            @role('waiter')
                            <li class="nav-item">
                                <a href="{{ url('order-queue') }}"
                                    class="nav-link {{ request()->is('order-queue*') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-list-ul"></i>
                                    <p>
                                        Order Queue
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('order-history') }}"
                                    class="nav-link {{ request()->is('order-history*') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-history"></i>
                                    <p>
                                        Order History
                                    </p>
                                </a>
                            </li>
                            @endrole

                            @role('cashier')
                            <li class="nav-item">
                                <a href="{{ url('order-queue') }}"
                                    class="nav-link {{ request()->is('order-queue*') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-list-ul"></i>
                                    <p>
                                        Order Queue
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('order-history') }}"
                                    class="nav-link {{ request()->is('order-history*') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-history"></i>
                                    <p>
                                        Order History
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('billing') }}"
                                    class="nav-link {{ request()->is('billing*') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-credit-card"></i>
                                    <p>
                                        Billing
                                    </p>
                                </a>
                            </li>
                            @endrole

                            @role('kitchen-staff')
                            <li class="nav-item">
                                <a href="{{ url('order-queue') }}"
                                    class="nav-link {{ request()->is('order-queue*') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-list-ul"></i>
                                    <p>
                                        Order Queue
                                    </p>
                                </a>
                            </li>
                            @endrole
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <main class="content-wrapper">
            @yield('content')
        </main>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Version 1.0.0
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2022 <a target="_blank">Red Card Cafe</a>.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

</body>
<!-- REQUIRED SCRIPTS -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js?v=3.2.0') }}"></script>
<script src="{{ asset('adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/pace-progress/pace.min.js')}}"></script>
<script>
    $(function () {
      bsCustomFileInput.init();
    });
</script>
@stack('script')

</html>