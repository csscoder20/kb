<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>MoP-GPT | Your MoP Report Partner</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/head_diamond_compnet.svg') }}">
    <link rel="icon" href="{{ asset('assets/img/head_diamond_compnet.svg') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('assets/img/head_diamond_compnet.svg') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/head_diamond_compnet.svg') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />

    <link rel="stylesheet" href="{{ asset('theme/new/css/light.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/style.css') }}">
    @stack('styles')
</head>

<body>
    <div class="wrapper">
        @include('partials.sidebar')
        <div class="main">
            @include('partials.header')
            @yield('content')
            @include('partials.footer')
        </div>
    </div>

    <div class="modal fade" id="reportModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script src="{{ asset('custom/js/script.js') }}"></script>
    <script src="{{ asset('theme/js/app.js') }}"></script>
    </script>
    @stack('scripts')
    <script>
        window.authUser = @json(auth()->user());
    </script>

    <script>
        function scrollToBottom() {
            const chatContainer = document.querySelector('.chat-container');
            chatContainer.scrollTop = chatContainer.scrollHeight;
            }
    
        scrollToBottom();
    </script>

    <script>
        document.getElementById('logout-btn').addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to log out?',
                text: "You will log out from this session.",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Logout',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        });
    </script>

    <script>
        const updateModalTheme = () => {
        const elements = document.querySelectorAll("#reportModal .modal-content, .btn, .btn-close, svg, .form-control");
        if (document.body.classList.contains("light-mode")) {
        modal.classList.remove("bg-dark", "text-white");
        modal.classList.add("bg-white", "text-dark");
        } else {
        modal.classList.remove("bg-white", "text-dark");
        modal.classList.add("bg-dark", "text-white");
        }
        };
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

                    const modal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
                    modal.hide();
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

    <script>
        document.querySelector('[data-bs-dismiss="modal"].btnCancel').addEventListener('click', function () {
            // Reset form
            document.getElementById('reportForm').reset();
    
            // Clear Dropzone
            if (dropzone) {
                dropzone.removeAllFiles(true);
            }
    
            // Reset Choices.js (karena form.reset() tidak mengupdate tampilan Choices)
            choices.clearStore();
            choices.setChoices(
                [...tagsSelect.options].map(opt => ({
                    value: opt.value,
                    label: opt.text,
                    selected: false,
                    customProperties: {
                        color: opt.dataset.color
                    }
                })),
                'value',
                'label',
                false
            );
    
            // Update warna tag yang dipilih (kosongkan)
            updateSelectedTagColors();
        });
    </script>

</body>

</html>