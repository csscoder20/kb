@extends('layouts.app')
<!-- Tambahkan reCAPTCHA script di bagian head -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
    <div class="row">
        @guest
        <div class="alert alert-warning " role="alert">
            <strong>Notice:</strong> You cannot upload, preview, download, or find any reports until you are logged in.
        </div>
        @endguest
    </div>

    <div class="row g-0">
        <form id="reportForm" action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <div class="alert alert-warning d-flex align-items-center gap-2" role="alert">
                                <i class="bi bi-info-circle fs-1"></i>
                                <div>
                                    <strong>MoP naming format should follow the full title of the MoP file</strong><br>
                                    <span>Example: <strong class="text-primary">Upgrade BIG-IP F5 from version
                                            17.1.1.2 to version 17.1.2.1 - Indocement Tunggal Prakarsa</strong></span>
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
                            <textarea id="description" name="description" class="form-control" rows="5"
                                required></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="file" class="form-label">File .docx</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".docx" required>
                            <div class="form-text">Only .docx files are allowed. Maximum file size: 10MB</div>
                        </div>
                    </div>
                </div>
                <!-- Tambahkan reCAPTCHA sebelum tombol submit -->
                <div class="col-lg-12 mb-3">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                    @error('g-recaptcha-response')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
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

        // File validation
        const fileInput = document.getElementById('file');
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes

        fileInput.addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                // Check file type
                if (!file.name.toLowerCase().endsWith('.docx')) {
                    Swal.fire({
                        title: '<p style="text-align:center;">Invalid File Type</p>',
                        html: '<p style="text-align:center;">Please select a .docx file.</p>',
                        icon: 'error'
                    });
                    this.value = '';
                    return;
                }

                // Check file size
                if (file.size > maxSize) {
                    Swal.fire({
                        title: '<p style="text-align:center;">File Too Large</p>',
                        html: '<p style="text-align:center;">File size must be less than 10MB.</p>',
                        icon: 'error'
                    });
                    this.value = '';
                    return;
                }
            }
        });

        // Form submission
        const form = document.getElementById('reportForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validasi reCAPTCHA
            const recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse) {
                Swal.fire({
                    title: '<p style="text-align:center;">Verification Required</p>',
                    html: '<p style="text-align:center;">Please complete the reCAPTCHA verification.</p>',
                    icon: 'error'
                });
                return;
            }

            const formData = new FormData(this);
            
            Swal.fire({
                title: '<p style="text-align:center;">Uploading report...</p>',
                html: `
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                            role="progressbar" style="width: 0%;" 
                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    <p style="text-align:center; margin-top: 10px;" id="uploadStatus">
                        Preparing to upload...
                    </p>
                `,
                allowOutsideClick: false,
                showConfirmButton: false
            });

            const xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

            let startTime = new Date().getTime();

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 95);
                    const uploadedMB = (e.loaded / (1024 * 1024)).toFixed(2);
                    const totalMB = (e.total / (1024 * 1024)).toFixed(2);
                    
                    const currentTime = new Date().getTime();
                    const elapsedTime = (currentTime - startTime) / 1000;
                    const uploadSpeed = (e.loaded / elapsedTime / (1024 * 1024)).toFixed(2);

                    const progressBar = Swal.getHtmlContainer().querySelector('.progress-bar');
                    const statusText = Swal.getHtmlContainer().querySelector('#uploadStatus');
                    
                    if (progressBar && statusText) {
                        progressBar.style.width = percentComplete + '%';
                        progressBar.textContent = percentComplete + '%';
                        
                        statusText.innerHTML = `
                            Uploaded: ${uploadedMB} MB of ${totalMB} MB<br>
                            Speed: ${uploadSpeed} MB/s
                        `;
                    }
                }
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    Swal.fire({
                        title: '<p style="text-align:center;">Report Created!</p>',
                        html: `<p style="text-align:center;">${data.message || "Your report has successfully been created."}</p>`,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        form.reset();
                        choices.removeActiveItems();
                        choices.clearInput();
                        updateSelectedTagColors();
                        $('#customer').val(null).trigger('change');
                        // Reset reCAPTCHA
                        grecaptcha.reset();
                    });
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
                        title: '<p style="text-align:center;">Failed Sending</p>',
                        html: `<p style="text-align:center;">${message}</p>`,
                        icon: 'error'
                    });
                }
            };

            xhr.onerror = function() {
                Swal.fire({
                    title: '<p style="text-align:center;">Failed Sending</p>',
                    html: '<p style="text-align:center;">Network error occurred.</p>',
                    icon: 'error'
                });
            };

            xhr.send(formData);
        });
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
@endsection