<header style="background-color: #e8ecf3;">
    <div class="container px-lg-5">
        <div class="row">
            <div class="alert alert-warning alert-dismissible fade show bg-transparent border-0 text-dark text-center my-3"
                role="alert">
                {{-- <strong>Holy guacamole!</strong> You should check in on some of those fields below. --}}
                <h1 style="font-size: 22px;">{{ $basics['title'] ?? 'Judul Default' }}</h1>
                <p style="font-size: 15px;">{{ $basics['description'] ?? 'Deskripsi default jika belum diatur' }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
</header>