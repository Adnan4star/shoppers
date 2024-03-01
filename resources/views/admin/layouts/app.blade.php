<!doctype html>
<!--
    * Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
    * @version 1.0.0-beta19
    * @link https://tabler.io
    * Copyright 2018-2023 The Tabler Authors
    * Copyright 2018-2023 codecalm.net Paweł Kuna
    * Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{--attaching csrf token in header for ajax--}}

    <title>Dashboard - Premium</title>
    <!--Dropzone css files -->
    <link href="{{ asset('admin-assets/plugins/dropzone/min/dropzone.min.css') }}" rel="stylesheet"/>
    <!-- CSS files -->
    <link href="{{ asset('admin-assets/dist/css/tabler.min.css?1684106062') }}" rel="stylesheet"/>
    <link href="{{ asset('admin-assets/dist/css/tabler-flags.min.css?1684106062') }}" rel="stylesheet"/>
    <link href="{{ asset('admin-assets/dist/css/tabler-payments.min.css?1684106062') }}" rel="stylesheet"/>
    <link href="{{ asset('admin-assets/dist/css/tabler-vendors.min.css?1684106062') }}" rel="stylesheet"/>
    <link href="{{ asset('admin-assets/dist/css/demo.min.css?1684106062') }}" rel="stylesheet"/>
    <link href="{{ asset('admin-assets/dist/css/datetimepicker.css') }}" rel="stylesheet"/>
    {{-- <link href="{{ asset('admin-assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet"/> --}}

    
    <style>
        @import url('https://rsms.me/inter/inter.css');
        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }
        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>
<body >
    <script src="{{ asset('admin-assets/dist/js/demo-theme.min.js?1684106062') }}"></script>
    <div class="page">
        <!-- Navbar -->
        <header class="navbar navbar-expand-md d-print-none" >
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('admin-assets/static/logo.svg') }}" width="110" height="32" alt="Tabler" class="navbar-brand-image">
                    </a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">
                    <div class="nav-item d-none d-md-flex me-3">
                        <div class="btn-list">
                            
                        </div>
                    </div>
                    <div class="d-none d-md-flex">
                        <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip"
                        data-bs-placement="bottom">
                        <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
                    </a>
                    <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip"
                    data-bs-placement="bottom">
                    <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
                </a>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url({{asset('admin-assets/static/avatars/013m.jpg')}})"></span>
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ Auth::guard('admin')->user()->name }}</div>
                        <div class="mt-1 small text-muted">{{ Auth::guard('admin')->user()->email }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    {{-- <a href="#" class="dropdown-item">Status</a> --}}
                    {{-- <a href="./profile.html" class="dropdown-item">Profile</a> --}}
                    {{-- <a href="#" class="dropdown-item">Feedback</a> --}}
                    {{-- <div class="dropdown-divider"></div> --}}
                    {{-- <a href="./settings.html" class="dropdown-item">Settings</a> --}}
                    <a href="{{route('admin.logout')}}" class="dropdown-item">Logout</a>
                </div>
            </div>
        
        </header>

<!-- Page navbar start--> 
@include('admin.layouts.navbar')

<div class="page-wrapper">
    <!-- Page header -->

    <!-- Page body -->
    @yield('content')
    <!-- Page body ends -->
    
    <footer class="footer footer-transparent d-print-none">
        <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
                <div class="col-lg-auto ms-lg-auto">
                    <ul class="list-inline list-inline-dots mb-0">
                        <li class="list-inline-item"><a href="https://tabler.io/docs" target="_blank" class="link-secondary" rel="noopener">Documentation</a></li>
                        <li class="list-inline-item"><a href="./license.html" class="link-secondary">License</a></li>
                        <li class="list-inline-item"><a href="https://github.com/tabler/tabler" target="_blank" class="link-secondary" rel="noopener">Source code</a></li>
                        <li class="list-inline-item">
                            <a href="https://github.com/sponsors/codecalm" target="_blank" class="link-secondary" rel="noopener">
                                <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-pink icon-filled icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" /></svg>
                                Sponsor
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                    <ul class="list-inline list-inline-dots mb-0">
                        <li class="list-inline-item">
                            Copyright &copy; 2023
                            <a href="." class="link-secondary">Shoppers</a>.
                            All rights reserved.
                        </li>
                        <li class="list-inline-item">
                            <a href="./changelog.html" class="link-secondary" rel="noopener">
                                v1.0.0-beta19
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>
</div>

<!-- Libs JS -->
<script src="{{ asset('admin-assets/dist/libs/apexcharts/dist/apexcharts.min.js?1684106062') }}" defer></script>
<script src="{{ asset('admin-assets/dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1684106062') }}" defer></script>
<script src="{{ asset('admin-assets/dist/libs/jsvectormap/dist/maps/world.js?1684106062') }}" defer></script>
<script src="{{ asset('admin-assets/dist/libs/jsvectormap/dist/maps/world-merc.js?1684106062') }}" defer></script>
<!--Dropzone js-->
<script src="{{ asset('admin-assets/plugins/dropzone/min/dropzone.min.js') }}" defer></script>
<!-- Tabler Core -->
<script src="{{ asset('admin-assets/dist/js/tabler.min.js?1684106062') }}" defer></script>
<script src="{{ asset('admin-assets/dist/js/demo.min.js?1684106062') }}" defer></script>
<script src="{{ asset('admin-assets/dist/js/datetimepicker.js') }}" defer></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
{{-- <script src="{{ asset('admin-assets/plugins/select2/js/select2.min.js') }}" defer></script> --}}




    {{--attaching csrf token with ajax--}}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('customJs')
</body>
</html>