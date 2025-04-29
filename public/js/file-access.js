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

async function handleFileAccess(url, fileType) {
    try {
        const reportId = url.split('/').pop();
        const action = fileType === 'pdf' ? 'view_pdf' : 'download_word';
        
        // Show loading or disable button
        const button = event.target.closest('a');
        const originalText = button.innerHTML;
        button.innerHTML = 'Loading...';
        button.style.pointerEvents = 'none';

        // Log access via AJAX
        const response = await fetch('/log-file-access', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                report_id: reportId,
                action: action
            })
        });

        const responseData = await response.json();
        
        if (!response.ok) {
            throw new Error(responseData.message || 'Failed to log access');
        }

        // Proceed with file access
        if (action === 'view_pdf') {
            window.open(`/reports/view-pdf/${reportId}`, '_blank');
        } else {
            window.location.href = `/reports/download-word/${reportId}`;
        }

    } catch (error) {
        console.error('Error details:', error);
        alert('An error occurred while accessing the file');
    } finally {
        // Reset button state
        if (button) {
            button.innerHTML = originalText;
            button.style.pointerEvents = 'auto';
        }
    }
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

async function logFileAccess(reportId, actionType) {
    try {
        const response = await fetch('/log-file-access', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                report_id: reportId,
                action: actionType
            })
        });

        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Failed to log access');
        }

        console.log('Access logged successfully:', data);
        return data;
    } catch (error) {
        console.error('Error logging access:', error);
        throw error;
    }
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
                const fileUrl = document.getElementById('fileUrl').value;
                const fileType = document.getElementById('fileType').value;
                const reportId = fileUrl.split('/').pop();
                
                // Log the access first
                // await logFileAccess(
                //     reportId, 
                //     fileType === 'pdf' ? 'preview' : 'download'
                // );
                await logFileAccess(reportId, fileType === 'pdf' ? 'view_pdf' : 'download_word');

                // Continue with reCAPTCHA verification
                const recaptchaResponse = grecaptcha.getResponse();
                
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
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred during the process');
            } finally {
                setLoadingState(false);
            }
        });
    }
});






