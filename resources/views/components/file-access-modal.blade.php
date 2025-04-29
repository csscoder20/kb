{{-- <style>
    .terms-content {
        font-size: 14px;
        line-height: 1.5;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }
</style>
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="termsContent" class="terms-content" style="max-height: 300px; overflow-y: auto;">
                    <!-- Terms content will be loaded here -->
                </div>
                <div class="mt-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="agreeTerms">
                        <label class="form-check-label" for="agreeTerms">
                            I have read and agree to the terms and conditions
                        </label>
                    </div>
                    <div class="g-recaptcha mt-3" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmAccess" disabled>Preview</button>
            </div>
        </div>
    </div>
</div>

<script>
    function displayTermsContent(terms) {
        let html = `<p class="fw-bold mb-3">${terms.title}</p>`;
        
        terms.content.forEach((section, index) => {
        html += `<div class="mb-3">
            <strong>${index + 1}. ${section.title}</strong>
            <ul class="mt-2">`;
        
                section.items.forEach(item => {
                const text = typeof item === 'object' ? item.item : item;
                html += `<li>${text}</li>`;
                });
        
                html += `</ul>
        </div>`;
        });
        
        return html;
    }

function handleFileAccess(url, type) {
    fetch(`/api/terms/${type}`)
        .then(response => response.json())
        .then(terms => {
            const modal = new bootstrap.Modal(document.getElementById('termsModal'));
            document.querySelector('#termsModal .modal-title').textContent = terms.title;
            document.querySelector('#termsContent').innerHTML = displayTermsContent(terms);
            
            // Reset checkbox and button state
            document.getElementById('agreeTerms').checked = false;
            document.getElementById('confirmAccess').disabled = true;
            
            // Update button text based on type
            document.getElementById('confirmAccess').textContent = type === 'pdf' ? 'Preview' : 'Download';
            
            modal.show();
            
            // Store URL for later use
            document.getElementById('confirmAccess').dataset.url = url;
        });
}

// Event listeners
document.getElementById('agreeTerms').addEventListener('change', function() {
    document.getElementById('confirmAccess').disabled = !this.checked;
});

document.getElementById('confirmAccess').addEventListener('click', function() {
    const url = this.dataset.url;
    const recaptchaResponse = grecaptcha.getResponse();
    
    if (!recaptchaResponse) {
        alert('Please complete the reCAPTCHA verification');
        return;
    }
    
    // Proceed with file access
    window.open(url, '_blank');
    
    // Close modal and reset recaptcha
    bootstrap.Modal.getInstance(document.getElementById('termsModal')).hide();
    grecaptcha.reset();
});
</script> --}}

<!-- Modal HTML -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="termsContent" class="terms-content" style="max-height: 300px; overflow-y: auto;">
                    <!-- Terms content will be loaded here -->
                </div>
                <div class="mt-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="agreeTerms">
                        <label class="form-check-label" for="agreeTerms">
                            I have read and agree to the terms and conditions
                        </label>
                    </div>
                    <div class="g-recaptcha mt-3" data-sitekey="{{ config('services.recaptcha.site_key') }}"
                        data-callback="onRecaptchaSuccess"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmAccess" disabled>Preview</button>
            </div>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
    .terms-content {
        font-size: 14px;
        line-height: 1.5;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }
</style>

<!-- Scripts -->
<script>
    // Fungsi untuk menampilkan isi Terms
    function displayTermsContent(terms) {
        let html = `<p class="fw-bold mb-3">${terms.title}</p>`;
        terms.content.forEach((section, index) => {
            html += `<div class="mb-3">
                        <strong>${index + 1}. ${section.title}</strong>
                        <ul class="mt-2">`;
            section.items.forEach(item => {
                const text = typeof item === 'object' ? item.item : item;
                html += `<li>${text}</li>`;
            });
            html += `</ul></div>`;
        });
        return html;
    }

    // Tampilkan modal dan set data
    function handleFileAccess(url, type) {
        fetch(`/api/terms/${type}`)
            .then(response => response.json())
            .then(terms => {
                const modal = new bootstrap.Modal(document.getElementById('termsModal'));
                document.querySelector('#termsModal .modal-title').textContent = terms.title;
                document.querySelector('#termsContent').innerHTML = displayTermsContent(terms);

                // Reset semua state
                document.getElementById('agreeTerms').checked = false;
                document.getElementById('confirmAccess').disabled = true;
                grecaptcha.reset();

                // Update button text dan dataset
                document.getElementById('confirmAccess').textContent = type === 'pdf' ? 'Preview' : 'Download';
                document.getElementById('confirmAccess').dataset.url = url;

                modal.show();
            });
    }

    // Fungsi update tombol aktif/tidak
    function updateAccessButtonState() {
        const checkboxChecked = document.getElementById('agreeTerms').checked;
        const recaptchaCompleted = grecaptcha.getResponse().length > 0;
        document.getElementById('confirmAccess').disabled = !(checkboxChecked && recaptchaCompleted);
    }

    // Callback dari reCAPTCHA
    function onRecaptchaSuccess() {
        updateAccessButtonState();
    }

    // Event ketika checkbox berubah
    document.getElementById('agreeTerms').addEventListener('change', updateAccessButtonState);

    // Event saat tombol diklik
    // document.getElementById('confirmAccess').addEventListener('click', function () {
    //     const url = this.dataset.url;
    //     const recaptchaResponse = grecaptcha.getResponse();

    //     if (!recaptchaResponse) {
    //         alert('Please complete the reCAPTCHA verification');
    //         return;
    //     }

    //     // Akses file
    //     window.open(url, '_blank');

    //     // Tutup modal dan reset semua
    //     bootstrap.Modal.getInstance(document.getElementById('termsModal')).hide();
    //     grecaptcha.reset();
    // });

    document.getElementById('confirmAccess').addEventListener('click', async function () {
        const url = this.dataset.url;
        const type = url.includes('pdf') ? 'view_pdf' : 'download_word';
        const reportId = url.split('/').pop();
        const recaptchaResponse = grecaptcha.getResponse();

        if (!recaptchaResponse) {
            alert('Please complete the reCAPTCHA verification');
            return;
        }

        try {
            // Log aktivitas terlebih dahulu
            await fetch('/log-file-access', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    report_id: reportId,
                    action: type
                })
            });

            // Akses file
            window.open(url, '_blank');
        } catch (err) {
            alert('Gagal mencatat aktivitas pengguna.');
            console.error(err);
        }

        // Tutup modal dan reset recaptcha
        bootstrap.Modal.getInstance(document.getElementById('termsModal')).hide();
        grecaptcha.reset();
    });


    // Reset saat modal ditutup
    document.getElementById('termsModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('agreeTerms').checked = false;
        document.getElementById('confirmAccess').disabled = true;
        grecaptcha.reset();
    });
</script>