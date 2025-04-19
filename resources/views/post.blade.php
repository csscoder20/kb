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
                                    <strong>Format Penamaan MoP sesuai judul file MoP</strong><br>
                                    <span>Contoh: <strong class="text-primary">Upgrade Panorama and Palo Alto 850 from
                                            Version
                                            10.1.11H5 TO 10.1.14H9 </strong></span>
                                </div>
                            </div>
                            <label for="title" class="form-label">Title</label><br>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="tags" class="form-label">Technology</label>
                            <select id="tags" class="form-select" name="tags[]" multiple required>
                                @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" data-color="{{ $tag->color }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="customer" class="form-label">Customer</label>
                            <select id="customer" name="customer" class="form-select" required></select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (MoP Background &
                                Objectives)</label>
                            <span></span>
                            <textarea id="description" name="description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <span for="docxDropzone" class="form-label">File .docx</span>
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

                        // Tampilkan ukuran file yang akan diupload
                        const fileSize = fileToUpload.size;
                        const fileSizeMB = (fileSize / (1024 * 1024)).toFixed(2);

                        Swal.fire({
                            title: '<p style="text-align:center;">Uploading report...</p>',
                            html: `
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                        role="progressbar" style="width: 0%;" 
                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                                <p style="text-align:center; margin-top: 10px;" id="uploadStatus">
                                    Preparing to upload ${fileSizeMB} MB...
                                </p>
                            `,
                            allowOutsideClick: false,
                            showConfirmButton: false
                        });

                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', form.action, true);
                        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

                        // Catat waktu mulai upload
                        let startTime;
                        let uploadStarted = false;

                        xhr.upload.onloadstart = function() {
                            startTime = new Date().getTime();
                            uploadStarted = true;
                        };

                        xhr.upload.onprogress = function(e) {
                            if (e.lengthComputable) {
                                // Hitung persentase yang sebenarnya berdasarkan bytes yang terupload
                                const actualPercentComplete = Math.round((e.loaded / e.total) * 95); // Max 95% untuk upload
                                
                                const currentTime = new Date().getTime();
                                const elapsedTime = (currentTime - startTime) / 1000;
                                const uploadedMB = (e.loaded / (1024 * 1024)).toFixed(2);
                                const totalMB = (e.total / (1024 * 1024)).toFixed(2);
                                
                                // Hitung kecepatan upload
                                const uploadSpeed = (e.loaded / elapsedTime / (1024 * 1024)).toFixed(2);
                                
                                // Hitung waktu tersisa berdasarkan data aktual
                                const remainingBytes = e.total - e.loaded;
                                const remainingTime = remainingBytes / (e.loaded / elapsedTime);
                                const remainingMinutes = Math.floor(remainingTime / 60);
                                const remainingSeconds = Math.floor(remainingTime % 60);

                                const progressBar = Swal.getHtmlContainer().querySelector('.progress-bar');
                                const statusText = Swal.getHtmlContainer().querySelector('#uploadStatus');
                                
                                if (progressBar && statusText) {
                                    // Update progress bar sesuai persentase aktual
                                    progressBar.style.width = actualPercentComplete + '%';
                                    progressBar.textContent = actualPercentComplete + '%';
                                    progressBar.setAttribute('aria-valuenow', actualPercentComplete);

                                    // Update status text dengan informasi yang lebih akurat
                                    statusText.innerHTML = `
                                        Uploaded: ${uploadedMB} MB of ${totalMB} MB<br>
                                        Speed: ${uploadSpeed} MB/s<br>
                                        ${remainingMinutes > 0 ? `${remainingMinutes}m ` : ''}${remainingSeconds}s remaining
                                    `;
                                }
                            }
                        };

                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                const data = JSON.parse(xhr.responseText);
                                
                                const progressBar = Swal.getHtmlContainer().querySelector('.progress-bar');
                                const statusText = Swal.getHtmlContainer().querySelector('#uploadStatus');
                                
                                if (progressBar && statusText) {
                                    // Set ke 100% dan langsung tampilkan pesan sukses
                                    progressBar.style.width = '100%';
                                    progressBar.textContent = '100%';
                                    
                                    // Langsung tutup dialog progress dan tampilkan pesan sukses
                                    Swal.fire({
                                        title: '<p style="text-align:center;">Report Created!</p>',
                                        html: `<p style="text-align:center;">${data.message || "Your report has successfully been created."}</p>`,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });

                                    // Reset form dan komponen lainnya
                                    dz.removeAllFiles();
                                    form.reset();
                                    $('#description').summernote('code', '');
                                    choices.removeActiveItems();
                                    choices.clearInput();
                                    updateSelectedTagColors();
                                    $('#customer').val(null).trigger('change');
                                }
                            } else {
                                let message = "Error when sending file.";
                                try {
                                    const error = JSON.parse(xhr.responseText);
                                    if (error?.errors) {
                                        message = Object.values(error.errors).flat().join("\n");
                                    }
                                } catch (e) {
                                    console.error("Error parsing response:", e);
                                }

                                Swal.fire({
                                    title: '<p style="text-align:center;">Failed Sending.</p>',
                                    html: `<p style="text-align:center;">${message}</p>`,
                                    icon: 'error'
                                });
                            }
                        };

                        xhr.onerror = function() {
                            Swal.fire({
                                title: '<p style="text-align:center;">Failed Sending.</p>',
                                html: '<p style="text-align:center;">Network error occurred.</p>',
                                icon: 'error'
                            });
                        };

                        xhr.send(formData);
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