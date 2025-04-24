<div class="modal fade" id="fileAccessModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Download Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
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
                <button type="button" class="btn btn-primary" id="confirmAccess">Continue</button>
            </div>
        </div>
    </div>
</div>