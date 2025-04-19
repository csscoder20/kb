<footer class="py-3 mt-auto">
    <div class="container">
        <p class="text-muted text-center mb-0 text-small">
            &copy; {{ date('Y') }} {{ $basicConfig['footer'] ?? '-' }}
            Made with <span class="text-danger">&#10084;</span>
            by <strong>{{ $basicConfig['created_by'] ?? '-' }}</strong>
        </p>
    </div>
</footer>