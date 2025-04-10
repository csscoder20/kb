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

    <style>
        nav.navbar.navbar-expand-lg.navbar-light {
            border-bottom: 1px solid #e8ecf3;
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

        /* .nav-pills .nav-link {
            background: none;
            border: 0;
            border-radius: var(--bs-nav-pills-border-radius);
            text-align: left;
            padding: 0.5rem 0;
            background-color: transparent !important;
            font-size: 15px !important;
        }

        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            color: var(--bs-nav-pills-link-active-color);
            background-color: var(--bs-nav-pills-link-active-bg);
            color: #0d6efd !important;
            font-weight: bold !important;
            font-size: 15px !important;
        } */

        div#nav-tabContent .card-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            /* font-weight: bold; */
        }
    </style>
</head>

<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container px-lg-5">
            <div class="col-lg-9">
                <a class="navbar-brand nav-link" aria-current="page" href="{{ ('/') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                    Search Post
                </a>
                <a class="navbar-brand nav-link" href="#!">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-pencil-fill" viewBox="0 0 16 16">
                        <path
                            d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                    </svg>
                    New Post
                </a>
                <a class="navbar-brand nav-link" href="#!">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-border-all" viewBox="0 0 16 16">
                        <path d="M0 0h16v16H0zm1 1v6.5h6.5V1zm7.5 0v6.5H15V1zM15 8.5H8.5V15H15zM7.5 15V8.5H1V15z" />
                    </svg>
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
                        <form id="logout-form" method="POST" action="{{ url('/admin/logout') }}">
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

    @include('partials.subheader')

    <section class="pt-4">
        <div class="container px-lg-5">
            <div class="row gx-lg-5">
                @yield('content')
            </div>
        </div>
    </section>
    {{-- <footer class="fixed-bottom py-5"> --}}
        <footer class="py-5">
            <div class="container">
                <p class="m-0 text-center text-dark">
                    Copyright &copy; Your Website 2023
                </p>
            </div>
        </footer>

        <script src="{{ asset('bt-theme/js/scripts.js') }}"></script>
</body>

</html>