@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

@section('content')
<div class="typing-container">
    <div class="row g-0">
        <form id="reportForm" action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="mb-3">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Penting!</strong> Format Penamaan file MoP sama dengan judul lengkap MoP. (Judul
                        MoP - Nama Customer)
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <label for="title" class="form-label">Title</label><br>
                    <input type="text" class="form-control" name="title" id="title" required>
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
                    <textarea id="description" name="description" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <span for="docxDropzone" class="form-label">File</span>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
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
</script>

<script>
    const tagsSelect = document.querySelector('select[name="tags[]"]');
    
        const choices = new Choices(tagsSelect, {
            removeItemButton: true,
            maxItemCount: 2,
            placeholderValue: 'Select an Option',
            searchPlaceholderValue: 'Seach Tag...',
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
</script>

<script>
    Dropzone.autoDiscover = false;

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

            dz.on("addedfile", function(file) {
                fileToUpload = file;
            });

            document.querySelector("#reportForm").addEventListener("submit", function(e) {
                e.preventDefault();

                if (!fileToUpload) {
                    Swal.fire({
                        title: 'Select File',
                        text: 'Please choose file to upload.'
                    });
                    return;
                }

                const form = e.target;
                const formData = new FormData(form);

                formData.append("file", fileToUpload);

                Swal.fire({
                    title: 'Sending report...',
                    html: 'Please wait, processing.',
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
                        title: 'Report Created!',
                        text: data.message || "Your report has successfully created.",
                        timer: 2000,
                        showConfirmButton: false
                    });

                    dz.removeAllFiles();
                    form.reset();
                    // Reset Summernote
                    $('#description').summernote('reset'); // atau kosongkan jika tidak berhasil:
                    $('#description').summernote('code', '');
                    
                    // Reset Choices.js
                    choices.clearStore(); // atau reset dengan cara lain:
                    choices.removeActiveItems();
                    choices.setChoices([], 'value', 'label', true);

                })
                .catch(error => {
                    console.error("Error when sending:", error);

                    let message = "Error when sending file.";

                    if (error?.errors) {
                        message = Object.values(error.errors).flat().join("\n");
                    }

                    Swal.fire({
                        title: 'Failed Sending.',
                        text: message
                    });
                });
            });
        }
    });
</script>
@endsection