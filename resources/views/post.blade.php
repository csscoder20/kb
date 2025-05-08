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

    .terms-content {
        font-size: 14px;
        line-height: 1.5;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }

    .terms-content ol>li {
        margin-bottom: 1rem;
    }

    .terms-content ul {
        list-style-type: disc;
        /* padding-left: 20px; */
    }

    .terms-content::-webkit-scrollbar {
        width: 8px;
    }

    .terms-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .terms-content::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .terms-content::-webkit-scrollbar-thumb:hover {
        background: #555;
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
                                <strong>Important! </strong>
                                <span class="d-flex justify-content-center align-items-center gap-1">Click
                                    <i class="bi bi-info-circle fs-4"></i> to see
                                    guidelines of
                                    each field.</span>
                            </div>
                            <span for="title" class="form-label">Title
                                <i class="bi bi-info-circle info-trigger" data-info-id="title_guidelines"
                                    data-bs-toggle="modal" data-bs-target="#infoModal"></i>
                            </span>
                            <input type="text" class="form-control required-field mt-1" name="title" id="title"
                                required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <span for="tags" class="form-label mb-2">Technology
                                <i class="bi bi-info-circle info-trigger" data-info-id="technology_selection_guidelines"
                                    data-bs-toggle="modal" data-bs-target="#infoModal"></i>
                            </span>
                            <select id="tags" class="form-select required-field mt-1" name="tags[]" multiple required>
                                @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" data-color="{{ $tag->color }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <span for="customer" class="form-label">Customer
                                <i class="bi bi-info-circle info-trigger" data-info-id="customer_selection_guidelines"
                                    data-bs-toggle="modal" data-bs-target="#infoModal"></i>
                            </span>
                            <select id="customer" class="form-select required-field mt-1" name="customer"
                                required></select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <span for="file" class="form-label">Description (MoP Background &
                                Objectives)
                                <i class="bi bi-info-circle info-trigger" data-info-id="mop_description_guidelines"
                                    data-bs-toggle="modal" data-bs-target="#infoModal"></i>
                            </span>
                            <textarea id="description" name="description" class="form-control required-field mt-1"
                                rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <span for="file" class="form-label">File .docx
                                <i class="bi bi-info-circle info-trigger" data-info-id="file_mop_guidelines"
                                    data-bs-toggle="modal" data-bs-target="#infoModal"></i>
                            </span>
                            <input type="file" class="form-control required-field mt-1" id="file" name="file"
                                accept=".docx" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer gap-3">
                <button type="button" class="btn btn-secondary rounded-2 btnReset d-none">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
                <button type="button" id="submitBtn" class="btn btn-primary rounded-2" data-bs-toggle="modal"
                    data-bs-target="#uploadPolicyModal">Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal template -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="terms-content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan modal untuk kebijakan privasi upload -->
<div class="modal fade" id="uploadPolicyModal" tabindex="-1" aria-labelledby="uploadPolicyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadPolicyModalLabel">Upload Policy Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="uploadTermsContent" class="terms-content" style="max-height: 300px; overflow-y: auto;">
                    <!-- Content will be loaded dynamically -->
                </div>

                <div class="form-check mb-3 mt-3">
                    <input class="form-check-input" type="checkbox" id="uploadPrivacyCheck">
                    <label class="form-check-label" for="uploadPrivacyCheck">
                        I understand and agree to the terms of uploading this report
                    </label>
                </div>

                <div class="g-recaptcha mb-3" data-sitekey="{{ config('services.recaptcha.site_key') }}"
                    data-callback="recaptchaCallback">
                </div>
                <div id="uploadRecaptchaAlert" class="alert alert-warning d-none">
                    Please complete the reCAPTCHA verification
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="confirmUpload" disabled>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="button-text">Upload</span>
                </button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/file-access.js') }}"></script>

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
                // Buat alert element
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <strong>Verification Required!</strong> Please complete the reCAPTCHA verification.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                
                // Tambahkan alert ke awal form
                form.insertBefore(alertDiv, form.firstChild);
                
                // Auto dismiss setelah 3 detik
                setTimeout(() => {
                    alertDiv.remove();
                }, 3000);
                return;
            }

            const formData = new FormData(this);
            
            // Buat progress bar element
            const progressDiv = document.createElement('div');
            progressDiv.className = 'alert alert-info';
            progressDiv.innerHTML = `
                <h5>Uploading report...</h5>
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                        role="progressbar" style="width: 0%;" 
                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
                <p class="mt-2 mb-0" id="uploadStatus">Preparing to upload...</p>
            `;
            
            // Tambahkan progress bar ke awal form
            form.insertBefore(progressDiv, form.firstChild);

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

                    const progressBar = progressDiv.querySelector('.progress-bar');
                    const statusText = progressDiv.querySelector('#uploadStatus');
                    
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
                // Hapus progress bar
                progressDiv.remove();

                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    
                    // Tampilkan alert sukses
                    const successAlert = document.createElement('div');
                    successAlert.className = 'alert alert-success';
                    successAlert.innerHTML = `
                        <strong>Success!</strong> ${data.message || "Your report has successfully been created."}
                    `;
                    form.insertBefore(successAlert, form.firstChild);

                    // Reset form
                    form.reset();
                    choices.removeActiveItems();
                    choices.clearInput();
                    updateSelectedTagColors();
                    $('#customer').val(null).trigger('change');
                    grecaptcha.reset();

                    // Auto dismiss alert setelah 3 detik
                    setTimeout(() => {
                        successAlert.remove();
                    }, 3000);
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

                    // Tampilkan alert error
                    const errorAlert = document.createElement('div');
                    errorAlert.className = 'alert alert-danger';
                    errorAlert.innerHTML = `
                        <strong>Failed Sending!</strong> ${message}
                    `;
                    form.insertBefore(errorAlert, form.firstChild);

                    // Auto dismiss alert setelah 5 detik
                    setTimeout(() => {
                        errorAlert.remove();
                    }, 5000);
                }
            };

            xhr.onerror = function() {
                // Hapus progress bar
                progressDiv.remove();

                // Tampilkan alert network error
                const networkAlert = document.createElement('div');
                networkAlert.className = 'alert alert-danger';
                networkAlert.innerHTML = `
                    <strong>Failed Sending!</strong> Network error occurred.
                `;
                form.insertBefore(networkAlert, form.firstChild);

                // Auto dismiss alert setelah 5 detik
                setTimeout(() => {
                    networkAlert.remove();
                }, 5000);
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('reportForm');
        const submitBtn = document.getElementById('submitBtn');
        const resetBtn = document.querySelector('.btnReset');

        // Pastikan tombol reset tersembunyi saat awal
        resetBtn.classList.add('d-none');

        function validateForm() {
            const title = form.querySelector('#title').value.trim();
            const description = form.querySelector('#description').value.trim();
            const file = form.querySelector('#file').value;
            const customer = $('#customer').val();
            const technologies = choices.getValue();

            const isValid = 
                title !== '' && 
                description !== '' && 
                file !== '' && 
                customer !== null && 
                customer !== '' && 
                technologies.length > 0;

            submitBtn.disabled = !isValid;
            
            // Cek apakah ada isian di form
            const hasContent = 
                title !== '' || 
                description !== '' || 
                file !== '' || 
                (customer !== null && customer !== '') || 
                (technologies && technologies.length > 0);
                          
            // Toggle visibility tombol reset
            if (hasContent) {
                resetBtn.classList.remove('d-none');
            } else {
                resetBtn.classList.add('d-none');
            }
        }

        // Event listener untuk tombol reset
        resetBtn.addEventListener('click', function() {
            form.reset();
            choices.removeActiveItems();
            choices.clearInput();
            updateSelectedTagColors();
            $('#customer').val(null).trigger('change');
            submitBtn.disabled = true;
            resetBtn.classList.add('d-none');
        });

        // Panggil validateForm() segera setelah DOM loaded
        validateForm();

        // Event listeners untuk semua field
        form.querySelector('#title').addEventListener('input', validateForm);
        form.querySelector('#description').addEventListener('input', validateForm);
        form.querySelector('#file').addEventListener('change', validateForm);
        $('#customer').on('change', validateForm);
        tagsSelect.addEventListener('addItem', validateForm);
        tagsSelect.addEventListener('removeItem', validateForm);
    });
</script>
@endsection



<!-- Di bagian bawah file, sebelum closing body tag -->
<script src="{{ asset('js/info-modal.js') }}"></script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('reportForm');
        const submitBtn = document.getElementById('submitBtn');
        const uploadPrivacyCheck = document.getElementById('uploadPrivacyCheck');
        const confirmUploadBtn = document.getElementById('confirmUpload');

        // Fungsi untuk validasi form utama
        function validateForm() {
            const title = form.querySelector('#title').value.trim();
            const description = form.querySelector('#description').value.trim();
            const file = form.querySelector('#file').value;
            const customer = $('#customer').val();
            const technologies = choices.getValue();

            const isValid = title !== '' && 
                           description !== '' && 
                           file !== '' && 
                           customer !== null && 
                           customer !== '' && 
                           technologies.length > 0;

            submitBtn.disabled = !isValid;
            return isValid;
        }

        // Event listener untuk semua field yang required
        form.querySelectorAll('.required-field').forEach(field => {
            field.addEventListener('change', validateForm);
            field.addEventListener('input', validateForm);
        });

        // Event listener untuk checkbox privasi
        uploadPrivacyCheck.addEventListener('change', function() {
            const recaptchaResponse = grecaptcha.getResponse();
            confirmUploadBtn.disabled = !(this.checked && recaptchaResponse);
        });

        // reCAPTCHA callback
        window.recaptchaCallback = function() {
            const recaptchaResponse = grecaptcha.getResponse();
            const isChecked = uploadPrivacyCheck.checked;
            
            confirmUploadBtn.disabled = !(isChecked && recaptchaResponse);
            
            if (!recaptchaResponse) {
                document.getElementById('uploadRecaptchaAlert').classList.remove('d-none');
            } else {
                document.getElementById('uploadRecaptchaAlert').classList.add('d-none');
            }
        };

        // Handle form submission via modal button
        confirmUploadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse) {
                document.getElementById('uploadRecaptchaAlert').classList.remove('d-none');
                return;
            }

            // Show loading state
            this.disabled = true;
            this.querySelector('.spinner-border').classList.remove('d-none');
            this.querySelector('.button-text').textContent = 'Uploading...';

            // Submit form
            const formData = new FormData(form);
            formData.append('g-recaptcha-response', recaptchaResponse);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json' // Tambahkan header ini
                }
            })
            .then(async response => {
                // Handle response berdasarkan status
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Upload failed');
                    }
                    return data;
                } else {
                    throw new Error('Invalid response format from server');
                }
            })
            .then(data => {
                // Modal bootstrap instance
                const modal = bootstrap.Modal.getInstance(document.getElementById('uploadPolicyModal'));
                modal.hide();

                // Reset form dan komponen lainnya
                form.reset();
                choices.removeActiveItems();
                choices.clearInput();
                updateSelectedTagColors();
                $('#customer').val(null).trigger('change');
                grecaptcha.reset();

                // Tampilkan pesan sukses
                Swal.fire({
                    title: 'Success!',
                    text: data.message || 'Report has been uploaded successfully',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                // Hapus .then(() => { window.location.href = '/allposts'; });
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Tampilkan pesan error yang sesuai
                let errorMessage = 'Something went wrong during upload';
                
                if (error.message) {
                    errorMessage = error.message;
                }
                
                if (error.errors) {
                    errorMessage = Object.values(error.errors).flat().join("\n");
                }

                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error'
                });
            })
            .finally(() => {
                // Reset loading state
                this.disabled = false;
                this.querySelector('.spinner-border').classList.add('d-none');
                this.querySelector('.button-text').textContent = 'Upload';
            });
        });

        // Reset modal saat ditutup
        document.getElementById('uploadPolicyModal').addEventListener('hidden.bs.modal', function() {
            uploadPrivacyCheck.checked = false;
            confirmUploadBtn.disabled = true;
            grecaptcha.reset();
            document.getElementById('uploadRecaptchaAlert').classList.add('d-none');
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load upload terms when upload button is clicked
        document.getElementById('submitBtn').addEventListener('click', function() {
            fetch('/api/terms/upload')
                .then(response => response.json())
                .then(terms => {
                    const termsContent = document.getElementById('uploadTermsContent');
                    termsContent.innerHTML = displayTermsContent(terms);
                })
                .catch(error => {
                    console.error('Error loading upload terms:', error);
                });
        });
    });

    function displayTermsContent(terms) {
        let html = `<p class="fw-bold mb-3">${terms.title}</p>`;
        
        terms.content.forEach((section, index) => {
            html += `
                <div class="mb-3">
                    <strong>${index + 1}. ${section.title}</strong>
                    <ul class="mt-1">
                        ${section.items.map(item => `<li>${item.item}</li>`).join('')}
                    </ul>
                </div>
            `;
        });

        html += `
            <p class="mt-3">
                <strong>Note:</strong> Violation of these terms may result in immediate access revocation and potential disciplinary measures.
            </p>
        `;

        return html;
    }
</script>