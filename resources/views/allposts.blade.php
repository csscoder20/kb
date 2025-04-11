@extends('layouts.app')
<link href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet" />
<style>
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
        width: 73% !important;
    }

    tbody>tr>td:nth-child(3) {
        width: 20% !important;
        text-align: right;
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

    th.text-center.dt-orderable-asc.dt-orderable-desc {
        width: 20%;
    }

    table#DataTables_Table_0 span.badge.me-1 {
        padding: 5px;
        font-size: 10px;
    }

    table#DataTables_Table_0 strong {
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
</style>
@section('content')
<nav class="overflow-auto">
    <div class="nav nav-tabs flex-nowrap" id="nav-tab" role="tablist">
        <button class="nav-link text-secondary {{ is_null(request('slug')) ? 'active' : '' }}" id="v-pills-all-tab"
            data-bs-toggle="pill" data-bs-target="#v-pills-all" type="button" role="tab" aria-controls="v-pills-all"
            aria-selected="{{ is_null(request('slug')) ? 'true' : 'false' }}" data-slug="allposts">
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
    <div class="tab-pane fade {{ is_null(request('slug')) ? 'show active' : '' }}" id="v-pills-all" role="tabpanel">
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script>
    $(document).ready(function () {
$('.nav-link[data-slug]').on('click', function () {
const slug = $(this).data('slug');
const url = new URL(window.location);

if (slug === 'allposts') {
url.searchParams.delete('slug');
} else {
url.searchParams.set('slug', slug);
}

history.pushState(null, '', url.toString());

// Emit event ke Livewire
Livewire.dispatch('slugChanged', { slug: slug === 'allposts' ? null : slug });
});
        $('.report-table').each(function () {
            const table = $(this);
            const slug = table.data('slug') === '' ? 'allposts' : table.data('slug');

            table.DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            ajax: {
            url: '{{ route('datatable.reports') }}',
            data: { slug: slug }
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
            headerCallback: function(thead, data, start, end, display) {
            $(thead).hide();
            }
            });
        });
    });
</script>

@endsection