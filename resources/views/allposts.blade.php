@extends('layouts.app')
<link href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet" />
@section('content')
<nav class="overflow-auto">
    @guest
    <div class="alert alert-warning " role="alert">
        <strong>Notice:</strong> You cannot upload, preview, download, or find any reports until you are logged in.
    </div>
    @endguest
    <div class="nav nav-tabs flex-nowrap" id="nav-tab" role="tablist">
        <button
            class="nav-link text-secondary {{ request('slug') === 'allposts' || is_null(request('slug')) ? 'active' : '' }}"
            id="v-pills-all-tab" data-bs-toggle="pill" data-bs-target="#v-pills-all" type="button" role="tab"
            aria-controls="v-pills-all"
            aria-selected="{{ request('slug') === 'allposts' || is_null(request('slug')) ? 'true' : 'false' }}"
            data-slug="allposts"> {{-- Ubah dari "" jadi "allposts" --}}
            <i class="bi bi-menu-app-fill"></i> All
        </button>

        @foreach($tags as $tag)
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
        <button class="nav-link text-secondary {{ request('slug') === $tag->slug ? 'active' : '' }}"
            id="v-pills-{{ $tag->slug }}-tab" data-bs-toggle="pill" data-bs-target="#v-pills-{{ $tag->slug }}"
            type="button" role="tab" aria-controls="v-pills-{{ $tag->slug }}"
            aria-selected="{{ request('slug') === $tag->slug ? 'true' : 'false' }}" data-slug="{{ $tag->slug }}">
            <i class="{{ $tag->icon }}"></i> {{ $aliases[$tag->slug] ?? $tag->name }}
        </button>
        @endforeach
    </div>
</nav>

<div class="tab-content mt-3" id="nav-tabContent">
    <div class="tab-pane fade {{ request('slug') === 'allposts' || is_null(request('slug')) ? 'show active' : '' }}"
        id="v-pills-all" role="tabpanel">
        @php
        $allReports = $tags->flatMap->reports->sortByDesc('created_at');
        @endphp

        @if($allReports->count())
        <div class="card border-0">
            <div class="card-body p-0">
                <table class="display report-table" data-slug="allposts">
                    <thead>
                        <tr>
                            <th>Report</th>
                            <th class="w-20">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        @else
        <p class="text-center mt-5">Belum ada laporan apapun.</p>
        @endif
    </div>

    @foreach($tags as $tag)
    <div class="tab-pane fade {{ request('slug') === $tag->slug ? 'show active' : '' }}" id="v-pills-{{ $tag->slug }}"
        role="tabpanel">
        @if($tag->reports->count())
        <div class="card border-0">
            <div class="card-body p-0">
                <table class="display report-table" data-slug="{{ $tag->slug }}">
                    <thead>
                        <tr>
                            <th>Report</th>
                            <th class="w-20">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        @else
        <p class="text-center mt-5">Belum ada laporan pada tag ini.</p>
        @endif
    </div>
    @endforeach
</div>

<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script>
    window.isLoggedIn = @json(auth()->check());
</script>
<script>
    $(document).on('click', '.nav-link[data-slug]', function () {
        const slug = $(this).data('slug');
        const url = new URL(window.location);

        $('#searchbox').val('');
        $('#listPencarian').html('');
        $('.header_search_form_panel').hide();

        if (!slug) {
            url.searchParams.delete('slug');
        } else {
            url.searchParams.set('slug', slug);
        }

        history.pushState(null, '', url.toString());
        $('#search_post_type').val(slug).trigger('change');

        Livewire.dispatch('slugChanged', { slug: slug === 'allposts' ? null : slug });

        setTimeout(() => {
            initializeReportTable(slug);
        }, 200);
    });

    $('#searchbox').on('focus', function () {
    let search = $(this).val().trim();
    if (search.length >= 2) {
    $('.header_search_form_panel').show();
    }
    });

    function initializeReportTable(slug) {
        const targetTable = $(`.report-table[data-slug="${slug}"]`);

        if (!$.fn.dataTable.isDataTable(targetTable)) {
            targetTable.DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                lengthChange: false,
                ajax: {
                    url: '{{ route('datatable.reports') }}',
                    data: { slug: slug === 'allposts' ? '' : slug }
                },
                columns: [
                    { data: 'user_image', name: 'user_image', orderable: false, searchable: false },
                    { data: 'info', name: 'info' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                columnDefs: [
                    { width: '10%', targets: 0 },
                    { width: '60%', targets: 1 },
                    { width: '30%', targets: 2 }
                ],
                headerCallback: function(thead) {
                    $(thead).hide();
                }
            });
        }
    }

    $('#search_post_type').on('change', function () {
        const slug = $(this).val();
        const url = new URL(window.location);

        if (!slug) {
            url.searchParams.delete('slug');
        } else {
            url.searchParams.set('slug', slug);
        }

        history.pushState(null, '', url.toString());
        Livewire.dispatch('slugChanged', { slug: slug });
    });

    const activeTab = $('.tab-pane.show.active .report-table').data('slug');
    if (activeTab) {
        initializeReportTable(activeTab);
    }

    $(document).ready(function () {
        $('#searchbox').on('keyup', function () {
            let search = $(this).val().trim();
            let slug = $('#search_post_type').val();

            if (search.length < 2) {
                $('#listPencarian').html('');
                $('.header_search_form_panel').hide();
                return;
            }

            $.ajax({
                url: '/search-posts',
                method: 'GET',
                data: {
                    search: search,
                    slug: slug
                },
                success: function (data) {
                    let html = '';

                    if (data.length === 0) {
                        html = '<li class="px-2 py-1">Tidak ada hasil ditemukan.</li>';
                    } else {
                        data.forEach(function (report) {
                            let tagsHtml = '';

                            if (report.tags.length > 0) {
                                report.tags.forEach(function (tag) {
                                    tagsHtml += `<small class="badge me-0 rounded-0" style="background-color:${tag.color}">${tag.alias}</small>`;
                                });
                            } else {
                                tagsHtml = `<small class="badge me-0 rounded-0" style="background-color:#ccc">Tanpa Tag</small>`;
                            }

                            let fileLinks = '';
                            if (window.isLoggedIn) {
                                fileLinks = `
                                    <a href="javascript:void(0)" onclick="handleFileAccess('/report/view-pdf/${report.id}', 'pdf')" 
                                        title="Preview .pdf file"
                                        class="text-danger text-decoration-none">
                                        <i class="bi bi-file-earmark-pdf fs-5 my-1"></i>
                                    </a> | 
                                    ${report.file ? `
                                    <a href="javascript:void(0)" onclick="handleFileAccess('/report/download-word/${report.id}', 'docx')" 
                                        title="Download .docx file" 
                                        class="text-success text-decoration-none">
                                        <i class="bi bi-file-earmark-word fs-5 my-1"></i>
                                    </a>` : ''}
                                `;
                            } else {
                                fileLinks = `<span class="text-muted small">-</span>`;
                            }

                            html += `
                            <li class="py-2 border-bottom d-flex justify-content-between align-items-center">
                                <div class="tagTitle d-flex align-items-center">
                                    ${tagsHtml}
                                    <span class="text-decoration-none">
                                        ${report.highlighted_title}
                                    </span>
                                </div>
                                <div class="pdfDocx d-flex">
                                    ${fileLinks}
                                </div>
                            </li>
                            `;
                        });
                    }

                    $('#listPencarian').html(html);
                    $('.header_search_form_panel').show();
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        });
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#searchbox, .header_search_form_panel').length) {
            $('.header_search_form_panel').hide();
        }
    });
</script>
<script>
    $('.header_search_form_panel').addClass('d-none');
    $('.header_search_form_panel').removeClass('d-none');
</script>

<!-- Add modal component -->
<div class="modal fade" id="fileAccessModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Privacy & Policy Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="fileAccessForm">
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="privacyCheck" required>
                            <label class="form-check-label" for="privacyCheck">
                                I have read and agree to the <a href="/privacy-policy" target="_blank">Privacy
                                    Policy</a>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"
                            data-callback="onRecaptchaLoad">
                        </div>
                    </div>
                    <input type="hidden" id="fileUrl">
                    <input type="hidden" id="fileType">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAccess">Continue</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Add reCAPTCHA script -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<!-- Add file access script -->
<script src="{{ asset('js/file-access.js') }}"></script>
@endpush

@endsection