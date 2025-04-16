@extends('layouts.app')
<link href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet" />
<style>
    .d-none {
        display: none !important;
    }

    table#DataTables_Table_1 span.badge.me-1 {
        font-size: 10px;
    }

    table th,
    table td {
        border: none !important;
        box-shadow: none !important;
        /* padding-left: 0 !important; */
    }

    button.dt-paging-button:hover,
    div.dt-container .dt-paging .dt-paging-button:hover {
        background: #ecf0f6 !important;
        border: 0 !important;
        border-radius: 0 !important;
    }

    .dt-layout-cell.dt-layout-end .dt-paging button {
        margin: 0 !important;
        border: 0 !important;
    }

    tbody>tr>td:nth-child(1) {
        width: 7% !important;
    }

    tbody>tr>td:nth-child(2) {
        width: 80% !important;
    }

    tbody>tr>td:nth-child(3) {
        width: 13% !important;
        text-align: right;
        vertical-align: top;
    }

    .report-table tbody tr:hover {
        background-color: #f2f2f2;
        border-radius: 10px !important;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
    }

    th {
        padding-left: 0 !important;
    }

    table.dataTable>tbody>tr {
        background-color: transparent;
        border-bottom: 1px solid #ebebeb;
    }

    th.text-center.dt-orderable-asc.dt-orderable-desc {
        width: 20%;
    }

    div#nav-tabContent table .tagsDiv span {
        font-size: 8px !important;
        padding: 5px !important;
    }

    div#nav-tabContent table strong {
        color: #111 !important;
        font-weight: normal;
    }

    thead {
        display: none !important;
    }

    .dt-layout-cell.dt-layout-end .dt-paging {
        border: 1px solid #667c99;
    }

    button.dt-paging-button.current {
        background: #f0f8ff !important;
        border: 0 !important;
    }

    .dt-layout-cell.dt-layout-start {
        font-size: 12px;
    }

    ul#listPencarian a {
        margin-left: 5px;
    }
</style>
@section('content')
<nav class="overflow-auto">
    @guest
    <div class="alert alert-warning " role="alert">
        <strong>Notice:</strong> You cannot upload, preview, or download reports until you are logged in.
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
        <p>Belum ada laporan apapun.</p>
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
        <p>Belum ada laporan pada tag ini.</p>
        @endif
    </div>
    @endforeach
</div>

{{-- <div class="modal fade" id="reportModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="reportForm" action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Create MoP Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <select id="tags" class="form-select" name="tags[]" multiple required>
                            @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" data-color="{{ $tag->color }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">File</label>
                        <div id="docxDropzone" class="dropzone"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill btnCancel"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill">Create Report</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}

<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script> --}}

{{-- <script>
    const handleClick = (e) => {
    e.preventDefault();
    const modalElement = document.getElementById('reportModal');
    const modal = new Modal(modalElement, {
    backdrop: false,
    keyboard: true,
    focus: true
    });
    modal.show();
    };
</script> --}}
<script>
    window.isLoggedIn = @json(auth()->check());
</script>
<script>
    $(document).on('click', '.nav-link[data-slug]', function () {
        const slug = $(this).data('slug');
        const url = new URL(window.location);

        // Reset pencarian saat tab diganti
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
    $('.header_search_form_panel').show(); // tampilkan kembali hasil pencarian sebelumnya
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

                            // Cek login
                            let fileLinks = '';
                            if (window.isLoggedIn) {
                                fileLinks = `
                                    <a href="/report/view-pdf/${report.id}" target="_blank" title="Preview .pdf file"
                                        class="text-danger text-decoration-none">
                                        <i class="bi bi-file-earmark-pdf fs-5 my-1"></i>
                                    </a> | 
                                    ${report.file ? `
                                    <a href="/report/download-word/${report.id}" title="Download .docx file" class="text-success text-decoration-none">
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
    $('.header_search_form_panel').addClass('d-none'); // hide
    $('.header_search_form_panel').removeClass('d-none'); // show
</script>
@endsection