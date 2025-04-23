<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>Bank MOP | Your Report Partner</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/head_diamond_compnet_dark.svg') }}">
    <link rel="icon" href="{{ asset('assets/img/head_diamond_compnet_dark.svg') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('assets/img/head_diamond_compnet_dark.svg') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/head_diamond_compnet_dark.svg') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link href="{{ asset('bt-theme/css/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('bt-theme/css/custom-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('bt-theme/css/all-style.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('bt-theme/front-search/css/style-main.css') }}">
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    @livewireStyles
</head>

<body>
    <div class="d-flex flex-column min-vh-100">
        @include('partials.header')
        <livewire:subheader :slug="request()->query('slug')" />
        <section class="pt-0 flex-grow-1">
            <div class="container px-lg-5">
                <div class="row gx-lg-5">
                    @yield('content')
                </div>
            </div>
        </section>
        @include('partials.footer')
    </div>

    @livewireScripts
    <script src="{{ asset('bt-theme/js/scripts.js') }}"></script>
    <script src="{{ asset('bt-theme/front-search/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure you want to log out?',
                    text: "You will log out from this session.",
                    showCancelButton: true,
                    confirmButtonColor: '#e02e2a',
                    cancelButtonColor: 'transparent',
                    confirmButtonText: 'Logout',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            if (!sessionStorage.getItem('announcementShown')) {
                $.ajax({
                    url: '/api/announcement/active',
                    method: 'GET',
                    success: function (data) {
                        if (data && data.title) {
                            let htmlContent = `
                                <div style="text-align: left;">
                                    <h5 class="fw-bold">${data.title}</h5>
                                    <p>${data.content}</p>
                                </div>
                            `;
    
                            Swal.fire({
                                html: htmlContent,
                                icon: data.type ?? 'info',
                                confirmButtonText: 'Close',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                customClass: {
                                    popup: 'text-start',
                                }
                            });
    
                            if (data.show_once_per_session) {
                                sessionStorage.setItem('announcementShown', true);
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>