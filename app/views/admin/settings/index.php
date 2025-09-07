<div class="page-header">
    <h1>Settings</h1>
</div>

<form action="<?= URLROOT; ?>/admin/settings/update" method="POST">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div class="card" style="padding: 2rem;">
            <h2 style="margin-top: 0;">General Settings</h2>
            
            <div class="form-group">
                <label for="app_name">Application Name</label>
                <input type="text" name="app_name" id="app_name" class="form-control" 
                       value="<?= htmlspecialchars($settings['app_name'] ?? 'Photobooth App') ?>">
            </div>
            
            <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" name="company_name" id="company_name" class="form-control" 
                       value="<?= htmlspecialchars($settings['company_name'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="contact_email">Contact Email</label>
                <input type="email" name="contact_email" id="contact_email" class="form-control" 
                       value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="timezone">Timezone</label>
                <select name="timezone" id="timezone" class="form-control">
                    <option value="Asia/Jakarta" <?= ($settings['timezone'] ?? 'Asia/Jakarta') === 'Asia/Jakarta' ? 'selected' : '' ?>>Asia/Jakarta</option>
                    <option value="UTC" <?= ($settings['timezone'] ?? '') === 'UTC' ? 'selected' : '' ?>>UTC</option>
                </select>
            </div>
        </div>

        <div class="card" style="padding: 2rem;">
            <h2 style="margin-top: 0;">Photobooth Settings</h2>
            
            <div class="form-group">
                <label for="default_session_duration">Default Session Duration (seconds)</label>
                <input type="number" name="default_session_duration" id="default_session_duration" 
                       class="form-control" min="60" max="1800" step="30"
                       value="<?= htmlspecialchars($settings['default_session_duration'] ?? DEFAULT_SESSION_DURATION) ?>">
            </div>
            
            <div class="form-group">
                <label for="default_max_save_photos">Default Max Save Photos</label>
                <input type="number" name="default_max_save_photos" id="default_max_save_photos" 
                       class="form-control" min="5" max="100"
                       value="<?= htmlspecialchars($settings['default_max_save_photos'] ?? DEFAULT_MAX_SAVE_PHOTOS) ?>">
            </div>
            
            <div class="form-group">
                <label for="default_frame_limit">Default Frame Limit</label>
                <input type="number" name="default_frame_limit" id="default_frame_limit" 
                       class="form-control" min="1" max="5"
                       value="<?= htmlspecialchars($settings['default_frame_limit'] ?? DEFAULT_FRAME_LIMIT) ?>">
            </div>
            
            <div class="form-group">
                <label for="auto_print">Auto Print After Session</label>
                <select name="auto_print" id="auto_print" class="form-control">
                    <option value="1" <?= ($settings['auto_print'] ?? '0') === '1' ? 'selected' : '' ?>>Yes</option>
                    <option value="0" <?= ($settings['auto_print'] ?? '0') === '0' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
        <div class="card" style="padding: 2rem;">
            <h2 style="margin-top: 0;">Email Settings</h2>
            
            <div class="form-group">
                <label for="smtp_host">SMTP Host</label>
                <input type="text" name="smtp_host" id="smtp_host" class="form-control" 
                       value="<?= htmlspecialchars($settings['smtp_host'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="smtp_port">SMTP Port</label>
                <input type="number" name="smtp_port" id="smtp_port" class="form-control" 
                       value="<?= htmlspecialchars($settings['smtp_port'] ?? '587') ?>">
            </div>
            
            <div class="form-group">
                <label for="smtp_username">SMTP Username</label>
                <input type="text" name="smtp_username" id="smtp_username" class="form-control" 
                       value="<?= htmlspecialchars($settings['smtp_username'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="smtp_password">SMTP Password</label>
                <input type="password" name="smtp_password" id="smtp_password" class="form-control" 
                       placeholder="Leave empty to keep existing password">
            </div>
        </div>

        <div class="card" style="padding: 2rem;">
            <h2 style="margin-top: 0;">Payment Gateway Settings</h2>
            
            <div class="form-group">
                <label for="midtrans_server_key">Midtrans Server Key</label>
                <input type="password" name="midtrans_server_key" id="midtrans_server_key" class="form-control" 
                       placeholder="Leave empty to keep existing key">
            </div>
            
            <div class="form-group">
                <label for="midtrans_client_key">Midtrans Client Key</label>
                <input type="text" name="midtrans_client_key" id="midtrans_client_key" class="form-control" 
                       value="<?= htmlspecialchars($settings['midtrans_client_key'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="midtrans_environment">Midtrans Environment</label>
                <select name="midtrans_environment" id="midtrans_environment" class="form-control">
                    <option value="sandbox" <?= ($settings['midtrans_environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' ?>>Sandbox</option>
                    <option value="production" <?= ($settings['midtrans_environment'] ?? 'sandbox') === 'production' ? 'selected' : '' ?>>Production</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card" style="padding: 2rem; margin-top: 2rem;">
        <h2 style="margin-top: 0;">Printer Settings</h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div class="form-group">
                <label for="printer_name">Default Printer Name</label>
                <input type="text" name="printer_name" id="printer_name" class="form-control" 
                       value="<?= htmlspecialchars($settings['printer_name'] ?? '') ?>" 
                       placeholder="e.g. Canon SELPHY CP1300">
            </div>
            
            <div class="form-group">
                <label for="print_quality">Print Quality</label>
                <select name="print_quality" id="print_quality" class="form-control">
                    <option value="draft" <?= ($settings['print_quality'] ?? 'normal') === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="normal" <?= ($settings['print_quality'] ?? 'normal') === 'normal' ? 'selected' : '' ?>>Normal</option>
                    <option value="high" <?= ($settings['print_quality'] ?? 'normal') === 'high' ? 'selected' : '' ?>>High</option>
                </select>
            </div>
        </div>
    </div>

    <div style="margin-top: 2rem; text-align: right;">
        <button type="submit" class="btn btn-primary">
            <i data-feather="save"></i> Save Settings
        </button>
    </div>
</form>

<script>
// Add some basic form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = ['app_name', 'default_session_duration', 'default_max_save_photos', 'default_frame_limit'];
    let isValid = true;
    
    requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element.value.trim()) {
            element.style.borderColor = '#dc3545';
            isValid = false;
        } else {
            element.style.borderColor = '';
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields.');
    }
});
</script>