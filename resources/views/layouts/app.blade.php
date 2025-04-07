<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>MoP-GPT | Your MoP Report Partner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/head_diamond_compnet.svg') }}">
    <link rel="icon" href="{{ asset('assets/img/head_diamond_compnet.svg') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('assets/img/head_diamond_compnet.svg') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/head_diamond_compnet.svg') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('theme/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/style.css') }}">
    @stack('styles')
</head>

<body>
    <div class="wrapper">
        @include('partials.sidebar')
        <div class="main">
            @include('partials.header')
            @yield('content')
            @include('partials.footer')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('custom/js/script.js') }}"></script>
    <script src="{{ asset('theme/js/app.js') }}"></script>
    @stack('scripts')
    <script>
        window.authUser = @json(auth()->user());
    </script>

    <script>
        function scrollToBottom() {
            const chatContainer = document.querySelector('.chat-container');
            chatContainer.scrollTop = chatContainer.scrollHeight;
            }
    
        scrollToBottom();
    </script>

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