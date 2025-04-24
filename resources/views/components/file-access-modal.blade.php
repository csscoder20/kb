<div class="modal fade" id="fileAccessModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Alert container - hidden by default -->
                <div id="validationAlert" class="alert alert-danger d-none">
                    <ul class="mb-0">
                        <li id="privacyAlert" class="d-none">Please agree to the Privacy Policy</li>
                        <li id="recaptchaAlert" class="d-none">Please complete the reCAPTCHA verification</li>
                    </ul>
                </div>

                <form id="fileAccessForm">
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="privacyCheck" required>
                            <label class="form-check-label" for="privacyCheck">
                                I have read and agree to the <a href="/privacy-policy" target="_blank">Privacy
                                    Policy</a>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                    </div>
                    <input type="hidden" id="fileUrl">
                    <input type="hidden" id="fileType">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAccess">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="button-text">Continue</span>
                </button>
            </div>
        </div>
    </div>
</div>