function setLoadingState(isLoading) {
    const button = document.getElementById('confirmAccess');
    const spinner = button.querySelector('.spinner-border');
    const buttonText = button.querySelector('.button-text');
    
    if (isLoading) {
        button.disabled = true;
        spinner.classList.remove('d-none');
        buttonText.textContent = 'Processing...';
    } else {
        button.disabled = false;
        spinner.classList.add('d-none');
        buttonText.textContent = button.dataset.originalText || 'Continue';
    }
}

async function handleFileAccess(url, type) {
    document.getElementById('fileAccessForm').reset();
    if (typeof grecaptcha !== 'undefined') {
        grecaptcha.reset();
    }
    
    document.getElementById('validationAlert').classList.add('d-none');
    document.getElementById('privacyAlert').classList.add('d-none');
    document.getElementById('recaptchaAlert').classList.add('d-none');
    
    setLoadingState(false);
    
    document.getElementById('fileUrl').value = url;
    document.getElementById('fileType').value = type;
    
    const modalTitle = document.querySelector('#fileAccessModal .modal-title');
    const confirmButton = document.getElementById('confirmAccess');
    const buttonText = confirmButton.querySelector('.button-text');
    const bodyPolicy = document.getElementById('bodyPolicy');
    
    try {
        // Fetch terms from server
        const response = await fetch(`/terms/${type}`);
        const terms = await response.json();
        
        modalTitle.textContent = terms.title;
        buttonText.textContent = type === 'pdf' ? 'Preview' : 'Download';
        
        // Generate terms HTML
        let termsHtml = `<p class="fw-bold mb-2">${terms.title}</p>
            <div class="terms-content">
                <ol class="ps-3 mb-0">`;
        
        terms.content.forEach(section => {
            termsHtml += `
                <li class="mb-2">
                    <strong>${section.title}</strong>
                    <ul class="mt-1">`;
            
            section.items.forEach(item => {
                termsHtml += `<li>${item}</li>`;
            });
            
            termsHtml += `
                    </ul>
                </li>`;
        });
        
        termsHtml += `
                </ol>
            </div>`;
            
        bodyPolicy.innerHTML = termsHtml;
        confirmButton.dataset.originalText = type === 'pdf' ? 'Preview' : 'Download';
        
    } catch (error) {
        console.error('Error fetching terms:', error);
        bodyPolicy.innerHTML = '<div class="alert alert-danger">Error loading terms and conditions</div>';
    }
    
    confirmButton.disabled = true;
    
    const modal = new bootstrap.Modal(document.getElementById('fileAccessModal'));
    modal.show();
}

function updateConfirmButtonState() {
    const privacyCheck = document.getElementById('privacyCheck').checked;
    const recaptchaResponse = grecaptcha.getResponse();
    const confirmButton = document.getElementById('confirmAccess');
    
    confirmButton.disabled = !(privacyCheck && recaptchaResponse.length > 0);
}

function validateForm() {
    const privacyCheck = document.getElementById('privacyCheck').checked;
    const recaptchaResponse = grecaptcha.getResponse();
    const validationAlert = document.getElementById('validationAlert');
    const privacyAlert = document.getElementById('privacyAlert');
    const recaptchaAlert = document.getElementById('recaptchaAlert');
    
    let hasError = false;
    
    validationAlert.classList.add('d-none');
    privacyAlert.classList.add('d-none');
    recaptchaAlert.classList.add('d-none');
    
    if (!privacyCheck) {
        privacyAlert.classList.remove('d-none');
        hasError = true;
    }
    
    if (!recaptchaResponse) {
        recaptchaAlert.classList.remove('d-none');
        hasError = true;
    }
    
    if (hasError) {
        validationAlert.classList.remove('d-none');
        return false;
    }
    
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    const privacyCheck = document.getElementById('privacyCheck');
    const fileAccessModal = document.getElementById('fileAccessModal');
    const confirmAccess = document.getElementById('confirmAccess');

    if (privacyCheck && fileAccessModal && confirmAccess) {
        privacyCheck.addEventListener('change', updateConfirmButtonState);
        
        window.recaptchaCallback = function() {
            updateConfirmButtonState();
        };

        fileAccessModal.addEventListener('hidden.bs.modal', function () {
            setLoadingState(false);
        });

        confirmAccess.addEventListener('click', async function() {
            if (!validateForm()) {
                return;
            }

            setLoadingState(true);

            try {
                const recaptchaResponse = grecaptcha.getResponse();
                const fileUrl = document.getElementById('fileUrl').value;
                const fileType = document.getElementById('fileType').value;

                const response = await fetch('/verify-recaptcha', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        recaptcha: recaptchaResponse
                    })
                });

                const data = await response.json();

                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(fileAccessModal);
                    modal.hide();
                    
                    if (fileType === 'pdf') {
                        window.open(fileUrl, '_blank');
                    } else {
                        window.location.href = fileUrl;
                    }
                } else {
                    alert('reCAPTCHA verification failed');
                    setLoadingState(false);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred during verification');
                setLoadingState(false);
            }
        });
    }
});

