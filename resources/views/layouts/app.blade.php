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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="{{ asset('bt-theme/front-search/css/style-main.css') }}">
    <link rel="stylesheet" href="{{ asset('bt-theme/front-search/css/responsive.css') }}">
    @livewireStyles
    <style>
        a.btn.btn-primary {
            background-color: #4f46e5 !important;
            border: 0 !important;
        }

        nav.navbar.navbar-expand-lg.navbar-light {
            border-bottom: 1px solid #e8ecf3;
        }

        a.navbar-brand.nav-link.active {
            color: #4f46e5 !important;
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

        li.px-2.py-2.border-bottom {
            font-size: 12px;
        }

        /* Swall */
        button.swal2-confirm.swal2-styled.swal2-default-outline {
            border-radius: 30px;
        }

        button.swal2-cancel.swal2-styled.swal2-default-outline {
            border-radius: 30px;
            background-color: transparent !important;
            border: 2px solid #ecf0f6 !important;
            color: #000;
        }

        .swal2-popup.swal2-modal.swal2-show {
            width: 25rem !important;
            border-radius: 1rem;
            padding: 1.5rem;
        }

        .swal2-actions {
            width: 100%;
            justify-content: flex-end;
        }

        h2#swal2-title {
            border-bottom: 2px solid #ecf0f6;
            padding: 20px 0;
            text-align: left;
            font-size: 24px;
        }

        div#swal2-html-container {
            text-align: left;
            font-size: 16px;
            padding: 10px 0;
        }

        button:active,
        .btn:active {
            background-color: transparent !important;
            box-shadow: none !important;
            border-color: transparent !important;
        }

        button:hover,
        .btn:hover {
            border-radius: 5px;
        }

        ul.dropdown-menu.dropdown-menu-end.show {
            padding: 10px !important;
        }

        /* End Swall */
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
                    href="{{ url('allposts?slug=allposts') }}">
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
            <div class="mt-3 mt-lg-0 d-flex align-items-center">
                <a href="{{ url('admin/login') }}" class="btn btn-light mx-2">Login</a>
                <a href="{{ url('admin/register') }}" class="btn btn-primary">Create account</a>
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script>
        document.getElementById('logout-btn').addEventListener('click', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure you want to log out?',
                    text: "You will log out from this session.",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Logout',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
            });
    </script>
</body>

</html>