<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>MoP GPT | Your MoP Report Partner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('assets/img/cropped-Artboard-1-copy-2-32x32.png') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('assets/img/cropped-Artboard-1-copy-2-192x192.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/cropped-Artboard-1-copy-2-180x180.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/cropped-Artboard-1-copy-2-270x270.png') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('custom/css/style.css') }}">

    @stack('styles')
</head>

<body>
    @include('partials.header')

    <div class="container-lg">
        @yield('content')
    </div>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js">
    </script>
    <script src="{{ asset('custom/js/script.js') }}"></script>
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
        document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(function (el) {
    new bootstrap.Tooltip(el, {
    trigger: 'hover' // Hanya muncul saat hover
    });
    });
    });
    </script>
    @stack('scripts')
</body>

</html>