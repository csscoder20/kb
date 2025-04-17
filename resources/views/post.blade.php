@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 44px !important;
    }

    .select2-container .select2-selection--single {
        min-height: 44px !important;
    }
</style>
@section('content')
<div class="typing-container">
    <div class="row g-0">
        <form id="reportForm" action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <div class="alert alert-warning d-flex align-items-center gap-2" role="alert">
                                <i class="bi bi-info-circle fs-1"></i>
                                <div>
                                    <strong>Format Penamaan file MoP</strong><br>
                                    <span>Contoh: <strong class="text-primary">Upgrade Panorama and Palo Alto 850 from
                                            Version
                                            10.1.11H5 TO 10.1.14H9 </strong></span>
                                </div>
                            </div>
                            <label for="title" class="form-label">MoP Title</label><br>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="tags" class="form-label">MoP Technology</label>
                            <select id="tags" class="form-select" name="tags[]" multiple required>
                                @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" data-color="{{ $tag->color }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="customer" class="form-label">MoP Customer</label>
                            <select id="customer" name="customer" class="form-select" required></select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">MoP Description (MoP Background &
                                Objectives)</label>
                            <span></span>
                            <textarea id="description" name="description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <span for="docxDropzone" class="form-label">File</span>
                            <div id="docxDropzone" class="dropzone"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer gap-3">
                <button type="button" class="btn btn-secondary rounded-2 btnCancel"
                    data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-2">Create Report</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@auth
<script>
    $(document).ready(function () {
        $('#description').summernote({
            height: 250,
            placeholder: 'Tulis deskripsi MoP di sini...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['codeview']]
            ]
        });
    });

    const tagsSelect = document.querySelector('select[name="tags[]"]');
    let choices = new Choices(tagsSelect, {
        removeItemButton: true,
        maxItemCount: 2,
        placeholderValue: 'Select an Option',
        searchPlaceholderValue: 'Search Tag...',
        itemSelectText: '',
        maxItemText: (maxItemCount) => `Only ${maxItemCount} can be selected.`
    });

    function updateSelectedTagColors() {
        setTimeout(() => {
            const selectedItems = document.querySelectorAll('.choices__list--multiple .choices__item');

            selectedItems.forEach(item => {
                const value = item.getAttribute('data-value');
                const originalOption = [...tagsSelect.options].find(opt => opt.value === value);
                if (originalOption) {
                    const color = originalOption.dataset.color;
                    item.style.setProperty('background-color', color, 'important');
                    item.style.setProperty('border-color', color, 'important');
                    item.style.setProperty('color', '#fff', 'important');
                }
            });
        }, 10);
    }

    tagsSelect.addEventListener('addItem', updateSelectedTagColors);
    tagsSelect.addEventListener('removeItem', updateSelectedTagColors);

    document.addEventListener('DOMContentLoaded', () => {
        updateSelectedTagColors();
    });

    // Dropzone handling
    Dropzone.autoDiscover = false;

    document.addEventListener("DOMContentLoaded", function () {
        const dropzoneElement = document.querySelector("#docxDropzone");

        if (dropzoneElement) {
            let fileToUpload = null;

            const dropzone = new Dropzone("#docxDropzone", {
                url: "{{ route('reports.store') }}",
                autoProcessQueue: false,
                paramName: "file",
                maxFiles: 1,
                uploadMultiple: false,
                acceptedFiles: ".docx",
                addRemoveLinks: true,
                dictDefaultMessage: "Drag & Drop your file or Browse.",
                dictInvalidFileType: "Only .docx accepted.",
                dictMaxFilesExceeded: "Max 1 file accepted.",

                init: function () {
                    const dz = this;

                    dz.on("addedfile", function (file) {
                        fileToUpload = file;
                    });

                    document.querySelector("#reportForm").addEventListener("submit", function (e) {
                        e.preventDefault();

                        if (!fileToUpload) {
                            Swal.fire({
                                title: '<p style="text-align:center;">Select File</p>',
                                html: '<p style="text-align:center;">Please choose a file to upload.</p>',
                                icon: 'warning'
                            });
                            return;
                        }

                        const form = e.target;
                        const formData = new FormData(form);
                        formData.append("file", fileToUpload);

                        Swal.fire({
                            title: '<p style="text-align:center;">Sending report...</p>',
                            html: '<p style="text-align:center;">Please wait, processing.</p>',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        fetch(form.action, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        })
                            .then(async res => {
                                if (!res.ok) {
                                    const errorData = await res.json();
                                    throw errorData;
                                }
                                return res.json();
                            })
                            .then(data => {
                                Swal.fire({
                                    title: '<p style="text-align:center;">Report Created!</p>',
                                    html: `<p style="text-align:center;">${data.message || "Your report has successfully been created."}</p>`,
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                dz.removeAllFiles();
                                form.reset();
                                $('#description').summernote('code', '');

                                choices.removeActiveItems();
                                choices.clearInput();
                                updateSelectedTagColors();
                                // Reset field customer iki
                                $('#customer').val(null).trigger('change');
                            })
                            .catch(error => {
                                console.error("Error when sending:", error);
                                let message = "Error when sending file.";
                                if (error?.errors) {
                                    message = Object.values(error.errors).flat().join("\n");
                                }

                                Swal.fire({
                                    title: '<p style="text-align:center;">Failed Sending.</p>',
                                    html: `<p style="text-align:center;">${message}</p>`,
                                    icon: 'error'
                                });
                            });
                    });
                }
            });
        }
    });
</script>

<script>
    $(document).ready(function() {
    $('#customer').select2({
        placeholder: 'Select or type a customer',
        tags: true,
        ajax: {
            url: '/customers/select',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    term: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.results
                };
            },
            cache: true
        },
        createTag: function (params) {
            let term = $.trim(params.term);
            if (term === '') {
                return null;
            }
            return {
                id: term,
                text: term,
                newTag: true
            };
        }
    });
});
</script>
@endauth
@endsection