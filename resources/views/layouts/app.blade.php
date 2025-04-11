<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>MoP-GPT | Your MoP Report Partner</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/head_diamond_compnet.svg') }}">
    <link rel="icon" href="{{ asset('assets/img/head_diamond_compnet.svg') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('assets/img/head_diamond_compnet.svg') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/head_diamond_compnet.svg') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="{{ asset('bt-theme/css/styles.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('bt-theme/front-search/css/style-main.css') }}">
    <link rel="stylesheet" href="{{ asset('bt-theme/front-search/css/responsive.css') }}">
    @livewireStyles
    <style>
        nav.navbar.navbar-expand-lg.navbar-light {
            border-bottom: 1px solid #e8ecf3;
        }

        a.navbar-brand.nav-link.active {
            color: #426799 !important;
            font-weight: bold;
        }

        .container.px-lg-5 .col-lg-9 {
            display: flex;
            align-items: center;
            font-size: 12px;
        }

        .container.px-lg-5 .col-lg-9 a {
            font-size: 16px;
            padding-left: 0 !important;
        }

        h5.card-title.text-light.text-center span {
            font-size: 10px !important;
        }

        div#v-pills-tabContent h5 {
            font-size: 16px;
            font-weight: normal;
        }

        div#v-pills-tabContent .card-body {
            display: flex;
            justify-content: space-between;
        }

        div#v-pills-tabContent .card-body a {
            padding: 0 10px;
            text-align: center;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
            padding-right: 0;
        }


        div#nav-tabContent h5 {
            font-size: 1rem;
            font-weight: normal;
        }

        div#nav-tabContent span.text-muted {
            font-size: 12px;
        }

        .nav-tabs .nav-link.active,
        .nav-tabs .nav-item.show .nav-link {
            color: #0d6efd !important;
        }

        /* .alert-dismissible .btn-close {
            filter: brightness(0) invert(1);
        } */

        .hasdjjhasd {
            box-shadow: 0 9px 11.5px -3px #00000026,
                0 18.5px 28.5px #0000000d,
                0 7px 37.5px #0000000a;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container px-lg-5">
            <div class="col-lg-9">
                <a class="navbar-brand nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                    <i class="bi bi-houses-fill"></i>
                    Home
                </a>

                <a class="navbar-brand nav-link {{ Request::is('new-post') ? 'active' : '' }}"
                    href="{{ url('new-post') }}">
                    <i class="bi bi-pencil-square"></i>
                    New Post
                </a>

                <a class="navbar-brand nav-link {{ Request::is('allposts') ? 'active' : '' }}"
                    href="{{ url('allposts') }}">
                    <i class="bi bi-menu-app-fill"></i>
                    All Post
                </a>
            </div>
            @auth
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none" id="avatarDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    @php
                    $user = Auth::user();
                    $initials = collect(explode(' ', $user->name))->map(fn($word) =>
                    strtoupper(substr($word, 0,
                    1)))->join('');
                    @endphp

                    @if ($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}"
                        title="{{ $user->name }}" class="avatar img-fluid rounded-circle"
                        style="width: 40px; height: 40px; object-fit: cover;">
                    @else
                    <div class="avatar d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white"
                        style="width: 40px; height: 40px; font-weight: bold;">
                        {{ $initials }}
                    </div>
                    @endif
                </a>

                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="avatarDropdown">
                    <li class="">
                        <form id="logout-form" method="POST" action="{{ url('/admin/logout') }}" class="m-0">
                            @csrf
                            <button type="button" class="dropdown-item text-danger" id="logout-btn">
                                <svg class="icon-xl-heavy" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" height="24" width="24">
                                    <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2"
                                        stroke="currentColor" d="M16 17L21 12L16 7"></path>
                                    <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2"
                                        stroke="currentColor" d="M21 12H9">
                                    </path>
                                    <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2"
                                        stroke="currentColor"
                                        d="M12 5H7C5.89543 5 5 5.89543 5 7V17C5 18.1046 5.89543 19 7 19H12">
                                    </path>
                                </svg>
                                Log out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <div class="dropdown-menu dropdown-menu-end">
                <form id="logout-form" method="POST" action="{{ url('/admin/logout') }}">
                    @csrf
                    <button type="button" class="dropdown-item" id="logout-btn">
                        <svg class="icon-xl-heavy" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            height="24" width="24">
                            <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="currentColor"
                                d="M16 17L21 12L16 7"></path>
                            <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="currentColor"
                                d="M21 12H9">
                            </path>
                            <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="currentColor"
                                d="M12 5H7C5.89543 5 5 5.89543 5 7V17C5 18.1046 5.89543 19 7 19H12"></path>
                        </svg>
                        Log out
                    </button>
                </form>
            </div>
            @endauth

            @guest
            <a href="{{ url('admin/login') }}" class="btn btn-secondary text-light rounded-pill">
                Login
            </a>
            <a href="{{ url('admin/register') }}" class="btn btn-primary text-light rounded-pill">
                Register
            </a>
            @endguest
        </div>
    </nav>

    <livewire:subheader :slug="request()->query('slug')" />

    <section class="pt-4">
        <div class="container px-lg-5">
            <div class="row gx-lg-5">
                @yield('content')
            </div>
        </div>
    </section>
    <footer class="py-5">
        <div class="container">
            <p class="m-0 text-center text-dark">
                Copyright &copy; Your Website 2023
            </p>
        </div>
    </footer>
    @livewireScripts
    <script src="{{ asset('bt-theme/js/scripts.js') }}"></script>
    <script src="{{ asset('bt-theme/front-search/js/main.js') }}"></script>
</body>

</html>