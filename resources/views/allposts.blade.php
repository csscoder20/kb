@extends('layouts.app')

@section('content')
<nav class="overflow-auto">
    <div class="nav nav-tabs flex-nowrap" id="nav-tab" role="tablist">
        @foreach($tags as $index => $tag)
        @php
        $isActive = isset($activeSlug) && $activeSlug === $tag->slug;
        @endphp

        @php
        $aliases = [
        'routing-switching' => 'RS',
        'security' => 'Security',
        'wireless' => 'Wireless',
        'communication-collaboration' => 'Collab',
        'devnet' => 'DevNet',
        'data-center-virtualization' => 'Data Center',
        ];
        @endphp
        <button
            class="nav-link text-secondary {{ request('slug', $tags->first()->slug) === $tag->slug ? 'active' : '' }}"
            id="v-pills-{{ $tag->slug }}-tab" data-bs-toggle="pill" data-bs-target="#v-pills-{{ $tag->slug }}"
            type="button" role="tab" aria-controls="v-pills-{{ $tag->slug }}"
            aria-selected="{{ request('slug', $tags->first()->slug) === $tag->slug ? 'true' : 'false' }}"
            data-slug="{{ $tag->slug }}">
            <i class="{{ $tag->icon }}"></i>
            {{ $aliases[$tag->slug] ?? $tag->name }}
        </button>
        @endforeach
    </div>
</nav>
<div class="tab-content mt-3" id="nav-tabContent">
    @foreach($tags as $tag)
    @php
    $isActive = isset($activeSlug) && $activeSlug === $tag->slug;
    @endphp
    <div class="tab-pane fade {{ $isActive ? 'show active' : '' }}" id="v-pills-{{ $tag->slug }}" role="tabpanel"
        aria-labelledby="v-pills-{{ $tag->slug }}-tab">
        @forelse($tag->reports as $report)
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <h5>{{ $report->title }}</h5>
                    <span class="text-muted">{{ $report->created_at }}</span>
                </div>
                <a href="{{ asset('storage/' . $report->pdf_file) }}" target="_blank"
                    class="text-decoration-none text-secondary">
                    <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                </a>
            </div>
        </div>
        @empty
        <p>Belum ada laporan pada tag ini.</p>
        @endforelse
    </div>
    @endforeach
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const navLinks = document.querySelectorAll(".nav-link[data-slug]");

        navLinks.forEach(link => {
            link.addEventListener("click", function () {
                const slug = this.getAttribute("data-slug");
                const url = new URL(window.location.href);
                url.searchParams.set('slug', slug);
                window.history.pushState({}, '', url);
            });
        });
    });
</script>
@endsection