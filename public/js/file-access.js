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

function handleFileAccess(url, type) {
    // Reset form
    document.getElementById('fileAccessForm').reset();
    if (typeof grecaptcha !== 'undefined') {
        grecaptcha.reset();
    }
    
    // Reset alerts
    document.getElementById('validationAlert').classList.add('d-none');
    document.getElementById('privacyAlert').classList.add('d-none');
    document.getElementById('recaptchaAlert').classList.add('d-none');
    
    // Reset loading state
    setLoadingState(false);
    
    // Set hidden inputs
    document.getElementById('fileUrl').value = url;
    document.getElementById('fileType').value = type;
    
    // Set modal title and button text based on type
    const modalTitle = document.querySelector('#fileAccessModal .modal-title');
    const confirmButton = document.getElementById('confirmAccess');
    const buttonText = confirmButton.querySelector('.button-text');
    
    if (type === 'pdf') {
        modalTitle.textContent = 'Preview Confirmation';
        buttonText.textContent = 'Preview';
        confirmButton.dataset.originalText = 'Preview';
    } else if (type === 'docx') {
        modalTitle.textContent = 'Download Confirmation';
        buttonText.textContent = 'Download';
        confirmButton.dataset.originalText = 'Download';
    }
    
    // Disable continue button initially
    confirmButton.disabled = true;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('fileAccessModal'));
    modal.show();
}

function checkPrivacy() {
    const privacyCheck = document.getElementById('privacyCheck').checked;
    const confirmButton = document.getElementById('confirmAccess');
    
    confirmButton.disabled = !privacyCheck;
}

function validateForm() {
    const privacyCheck = document.getElementById('privacyCheck').checked;
    const recaptchaResponse = grecaptcha.getResponse();
    const validationAlert = document.getElementById('validationAlert');
    const privacyAlert = document.getElementById('privacyAlert');
    const recaptchaAlert = document.getElementById('recaptchaAlert');
    
    let hasError = false;
    
    // Reset alerts
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
    // Add listener for privacy checkbox
    document.getElementById('privacyCheck').addEventListener('change', checkPrivacy);
    
    // Add listener for modal hidden event
    document.getElementById('fileAccessModal').addEventListener('hidden.bs.modal', function () {
        setLoadingState(false);
    });
    
    // Add listener for confirm button
    document.getElementById('confirmAccess').addEventListener('click', async function() {
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
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('fileAccessModal'));
                modal.hide();
                
                // Access file based on type
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
});



