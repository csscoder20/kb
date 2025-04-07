@section('content')
@extends('layouts.app')
<main class="content">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="chat-container"></div>
            <div class="typing-container">
                <div class="container-lg mb-3">
                    <div class="typing-content">
                        <div class="typing-textarea">
                            <textarea id="chat-input" spellcheck="false" placeholder="Search here ..." required
                                autofocus></textarea>
                            <span id="send-btn" class="material-symbols-rounded">send</span>
                        </div>

                        <div class="typing-controls">
                            {{-- <span id="theme-btn" class="material-symbols-rounded" data-animation="true"
                                data-toggle="tooltip" data-placement="top" title="Ganti Tema">
                                light_mode
                            </span> --}}

                            <span id="delete-btn" class="material-symbols-rounded d-none text-danger"
                                data-animation="true" data-toggle="tooltip" data-placement="top" title="Hapus Semua">
                                delete
                            </span>
                        </div>
                    </div>
                </div>
                <p class="text-muted text-center mb-0 text-small">
                    &copy; {{ date('Y') }} {{ $basicConfig['footer'] ?? '-' }}
                    Made with <span class="text-danger">&#10084;</span>
                    by <strong>{{ $basicConfig['created_by'] ?? '-' }}</strong>
                </p>
            </div>
        </div>
    </div>
</main>
@endsection