function handleFileAccess(url, type) {
    // Reset form
    document.getElementById('fileAccessForm').reset();
    if (typeof grecaptcha !== 'undefined') {
        grecaptcha.reset();
    }
    
    // Set hidden inputs
    document.getElementById('fileUrl').value = url;
    document.getElementById('fileType').value = type;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('fileAccessModal'));
    modal.show();
}

document.getElementById('confirmAccess').addEventListener('click', function() {
    const form = document.getElementById('fileAccessForm');
    const privacyCheck = document.getElementById('privacyCheck');
    const recaptchaResponse = grecaptcha.getResponse();
    
    if (!privacyCheck.checked) {
        alert('Please agree to the Privacy Policy');
        return;
    }
    
    if (!recaptchaResponse) {
        alert('Please complete the reCAPTCHA');
        return;
    }
    
    // Verify recaptcha server-side
    fetch('/verify-recaptcha', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            recaptcha: recaptchaResponse
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const fileUrl = document.getElementById('fileUrl').value;
            const fileType = document.getElementById('fileType').value;
            
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('fileAccessModal')).hide();
            
            // Access file
            if (fileType === 'pdf') {
                window.open(fileUrl, '_blank');
            } else {
                window.location.href = fileUrl;
            }
        } else {
            alert('reCAPTCHA verification failed');
        }
    });
});