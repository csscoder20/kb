@extends('layouts.app')

@section('content')
<div class="chat-container"></div>

<!-- Typing container -->
<div class="typing-container">
    <div class="typing-content">
        <div class="typing-textarea">
            <textarea id="chat-input" spellcheck="false" placeholder="Search here ..." required autofocus></textarea>
            <span id="send-btn" class="material-symbols-rounded">send</span>
        </div>
        <div class="typing-controls">
            <span id="theme-btn" class="material-symbols-rounded">light_mode</span>
            <span id="delete-btn" class="material-symbols-rounded">delete</span>
        </div>
    </div>
</div>
@endsection