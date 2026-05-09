<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="settings" style="color: var(--primary);"></i>
            Settings
        </h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">Configure application, photobooth, email, and payment settings.</p>
    </div>
</div>

<form action="<?= URLROOT; ?>/admin/settings/update" method="POST">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem; align-items: start;">
        
        <!-- General Settings -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="monitor" style="width: 18px; color: var(--primary);"></i> General Settings</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="app_name" class="form-label">Application Name</label>
                    <input type="text" name="app_name" id="app_name" class="form-control" value="<?= htmlspecialchars($settings['app_name'] ?? 'Photobooth App') ?>">
                </div>
                
                <div class="form-group">
                    <label for="company_name" class="form-label">Company Name</label>
                    <input type="text" name="company_name" id="company_name" class="form-control" value="<?= htmlspecialchars($settings['company_name'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="contact_email" class="form-label">Contact Email</label>
                    <input type="email" name="contact_email" id="contact_email" class="form-control" value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="timezone" class="form-label">Timezone</label>
                    <select name="timezone" id="timezone" class="form-control">
                        <option value="Asia/Jakarta" <?= ($settings['timezone'] ?? 'Asia/Jakarta') === 'Asia/Jakarta' ? 'selected' : '' ?>>Asia/Jakarta</option>
                        <option value="UTC" <?= ($settings['timezone'] ?? '') === 'UTC' ? 'selected' : '' ?>>UTC</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Photobooth Settings -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="camera" style="width: 18px; color: var(--primary);"></i> Photobooth Settings</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="default_session_duration" class="form-label">Default Session Duration (sec)</label>
                    <input type="number" name="default_session_duration" id="default_session_duration" class="form-control" min="60" max="1800" step="30" value="<?= htmlspecialchars($settings['default_session_duration'] ?? DEFAULT_SESSION_DURATION) ?>">
                </div>
                
                <div class="form-group">
                    <label for="default_max_save_photos" class="form-label">Default Max Save Photos</label>
                    <input type="number" name="default_max_save_photos" id="default_max_save_photos" class="form-control" min="5" max="100" value="<?= htmlspecialchars($settings['default_max_save_photos'] ?? DEFAULT_MAX_SAVE_PHOTOS) ?>">
                </div>
                
                <div class="form-group">
                    <label for="default_frame_limit" class="form-label">Default Frame Limit</label>
                    <input type="number" name="default_frame_limit" id="default_frame_limit" class="form-control" min="1" max="5" value="<?= htmlspecialchars($settings['default_frame_limit'] ?? DEFAULT_FRAME_LIMIT) ?>">
                </div>
                
                <div class="form-group">
                    <label for="auto_print" class="form-label">Auto Print After Session</label>
                    <select name="auto_print" id="auto_print" class="form-control">
                        <option value="1" <?= ($settings['auto_print'] ?? '0') === '1' ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= ($settings['auto_print'] ?? '0') === '0' ? 'selected' : '' ?>>No</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="mail" style="width: 18px; color: var(--primary);"></i> Email Settings</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="smtp_host" class="form-label">SMTP Host</label>
                    <input type="text" name="smtp_host" id="smtp_host" class="form-control" value="<?= htmlspecialchars($settings['smtp_host'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="smtp_port" class="form-label">SMTP Port</label>
                    <input type="number" name="smtp_port" id="smtp_port" class="form-control" value="<?= htmlspecialchars($settings['smtp_port'] ?? '587') ?>">
                </div>
                
                <div class="form-group">
                    <label for="smtp_username" class="form-label">SMTP Username</label>
                    <input type="text" name="smtp_username" id="smtp_username" class="form-control" value="<?= htmlspecialchars($settings['smtp_username'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="smtp_password" class="form-label">SMTP Password</label>
                    <input type="password" name="smtp_password" id="smtp_password" class="form-control" placeholder="Leave empty to keep existing password">
                </div>
            </div>
        </div>

        <!-- Payment Gateway Settings -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="credit-card" style="width: 18px; color: var(--primary);"></i> Payment Gateway Settings</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="midtrans_server_key" class="form-label">Midtrans Server Key</label>
                    <input type="password" name="midtrans_server_key" id="midtrans_server_key" class="form-control" placeholder="Leave empty to keep existing key">
                </div>
                
                <div class="form-group">
                    <label for="midtrans_client_key" class="form-label">Midtrans Client Key</label>
                    <input type="text" name="midtrans_client_key" id="midtrans_client_key" class="form-control" value="<?= htmlspecialchars($settings['midtrans_client_key'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="midtrans_environment" class="form-label">Midtrans Environment</label>
                    <select name="midtrans_environment" id="midtrans_environment" class="form-control">
                        <option value="sandbox" <?= ($settings['midtrans_environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' ?>>Sandbox</option>
                        <option value="production" <?= ($settings['midtrans_environment'] ?? 'sandbox') === 'production' ? 'selected' : '' ?>>Production</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Database Settings -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="database" style="width: 18px; color: var(--primary);"></i> Database Settings</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="db_host" class="form-label">Database Host</label>
                    <input type="text" name="db_host" id="db_host" class="form-control" value="<?= htmlspecialchars($settings['db_host'] ?? 'localhost') ?>">
                </div>
                
                <div class="form-group">
                    <label for="db_user" class="form-label">Database User</label>
                    <input type="text" name="db_user" id="db_user" class="form-control" value="<?= htmlspecialchars($settings['db_user'] ?? 'root') ?>">
                </div>
                
                <div class="form-group">
                    <label for="db_password" class="form-label">Database Password</label>
                    <input type="password" name="db_password" id="db_password" class="form-control" placeholder="Leave empty to keep existing password">
                </div>
                
                <div class="form-group">
                    <label for="db_name" class="form-label">Database Name</label>
                    <input type="text" name="db_name" id="db_name" class="form-control" value="<?= htmlspecialchars($settings['db_name'] ?? 'photobooth_db') ?>">
                </div>
            </div>
        </div>
        
        <!-- Printer Settings -->
        <div class="card" style="grid-column: 1 / -1;">
            <div class="card-header">
                <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="printer" style="width: 18px; color: var(--primary);"></i> Printer Settings</h3>
            </div>
            <div class="card-body" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                <div class="form-group">
                    <label for="printer_name" class="form-label">Default Printer Name</label>
                    <input type="text" name="printer_name" id="printer_name" class="form-control" value="<?= htmlspecialchars($settings['printer_name'] ?? '') ?>" placeholder="e.g. Canon SELPHY CP1300">
                </div>
                
                <div class="form-group">
                    <label for="print_quality" class="form-label">Print Quality</label>
                    <select name="print_quality" id="print_quality" class="form-control">
                        <option value="draft" <?= ($settings['print_quality'] ?? 'normal') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="normal" <?= ($settings['print_quality'] ?? 'normal') === 'normal' ? 'selected' : '' ?>>Normal</option>
                        <option value="high" <?= ($settings['print_quality'] ?? 'normal') === 'high' ? 'selected' : '' ?>>High</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 1rem; text-align: right; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
        <button type="submit" class="btn btn-primary" style="padding-left: 2rem; padding-right: 2rem;">
            <i data-feather="save"></i> Save Settings
        </button>
    </div>
</form>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = ['app_name', 'default_session_duration', 'default_max_save_photos', 'default_frame_limit'];
    let isValid = true;
    
    requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element.value.trim()) {
            element.style.borderColor = '#dc2626';
            isValid = false;
        } else {
            element.style.borderColor = '';
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        if(typeof showToast === 'function') showToast('Please fill in all required fields.', 'error');
        else alert('Please fill in all required fields.');
    }
});
</script>