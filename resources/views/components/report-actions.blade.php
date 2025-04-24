<div class="report-actions">
    <a href="javascript:void(0)" onclick="handleFileAccess('/report/view-pdf/{{ $report->id }}', 'pdf')"
        class="btn btn-sm btn-outline-danger me-1" title="Preview PDF">
        <i class="bi bi-file-earmark-pdf"></i>
    </a>

    @if($report->file)
    <a href="javascript:void(0)" onclick="handleFileAccess('/report/download-word/{{ $report->id }}', 'docx')"
        class="btn btn-sm btn-outline-success" title="Download Word">
        <i class="bi bi-file-earmark-word"></i>
    </a>
    @endif
</div>