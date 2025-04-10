@section('content')
@extends('layouts.app')
<div class="typing-container">
    <div class="row g-0">
        @foreach($tags as $tag)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 rounded-0 h-100 position-relative" style="background-color: {{ $tag->color }}">
                <i class="{{ $tag->icon }} text-light text-center py-2 fs-1 fw-bold"></i>
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title text-light text-center">
                        {{ $tag->name }}
                        <span class="badge bg-light text-dark">{{ $tag->reports_count
                            }}</span>
                    </h5>
                    <p class="card-text text-light text-center">{{ $tag->description }}
                    </p>
                    {{-- <a href="{{ route('tag.show', $tag->slug) }}" class="stretched-link"></a> --}}
                    <a href="{{ route('tag.show', ['slug' => $tag->slug]) }}" class="stretched-link"></a>
                </div>
                <div class="card-footer text-center">
                    <small class="text-muted">
                        @if ($tag->reports->isNotEmpty())
                        <strong class="text-light">New:</strong>
                        <a href="{{ asset('storage/' . $tag->reports->first()->pdf_file) }}" target="_blank"
                            class="text-light text-decoration-none">
                            {{ $tag->reports->first()->title }}
                        </a>
                        @else
                        <small class="text-muted">Belum ada postingan</small>
                        @endif
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection