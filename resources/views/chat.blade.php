@extends('layouts.app')

@section('content')
<div class="chat-container"></div>

<div class="typing-container fixed-bottom">
    <div class="container-lg mb-4">
        <div class="typing-content">
            <div class="typing-textarea">
                <textarea id="chat-input" spellcheck="false" placeholder="Search here ..." required
                    autofocus></textarea>
                <span id="send-btn" class="material-symbols-rounded">send</span>
            </div>
            <div class="typing-controls">
                <span id="theme-btn" class="material-symbols-rounded" data-bs-animation="true" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Ganti Tema">
                    light_mode
                </span>

                <span id="delete-btn" class="material-symbols-rounded d-none" data-bs-animation="true"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Semua">
                    delete
                </span>
            </div>
        </div>
    </div>
    <p class="text-muted text-center mb-0 text-small">&copy; {{ date('Y') }} MoP-GPT. All rights reserved. | Made
        with <span class="text-danger"> &#10084;</span> by <strong>CSP Engineer</strong></p>
</div>
@endsection