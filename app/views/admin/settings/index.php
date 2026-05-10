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
                    <label for="live_view_websocket_url" class="form-label">Live View WebSocket URL</label>
                    <input type="text" name="live_view_websocket_url" id="live_view_websocket_url" class="form-control" value="<?= htmlspecialchars($settings['live_view_websocket_url'] ?? 'ws://localhost:8765') ?>">
                </div>
                
                <div class="form-group">
                    <label for="enable_session_refresh_back" class="form-label">Enable Session Refresh Back</label>
                    <select name="enable_session_refresh_back" id="enable_session_refresh_back" class="form-control">
                        <option value="1" <?= ($settings['enable_session_refresh_back'] ?? '1') === '1' ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= ($settings['enable_session_refresh_back'] ?? '1') === '0' ? 'selected' : '' ?>>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="enable_payment_bypass" class="form-label">Enable Payment Bypass (Skip Payment)</label>
                    <select name="enable_payment_bypass" id="enable_payment_bypass" class="form-control">
                        <option value="1" <?= ($settings['enable_payment_bypass'] ?? '0') === '1' ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= ($settings['enable_payment_bypass'] ?? '0') === '0' ? 'selected' : '' ?>>No</option>
                    </select>
                </div>
                
                <div class="form-group" style="grid-column: 1 / -1; border-top: 1px solid var(--border-color); padding-top: 1rem; margin-top: 0.5rem;">
                    <label class="form-label"><strong>Queue Processing System</strong></label>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Select how background tasks (email & print) are processed. Use Webhook for Cloud Run.</p>
                </div>

                <div class="form-group">
                    <label for="queue_process_mode" class="form-label">Processing Mode</label>
                    <select name="queue_process_mode" id="queue_process_mode" class="form-control">
                        <option value="worker" <?= ($settings['queue_process_mode'] ?? 'worker') === 'worker' ? 'selected' : '' ?>>Background Worker (Localhost)</option>
                        <option value="webhook" <?= ($settings['queue_process_mode'] ?? 'worker') === 'webhook' ? 'selected' : '' ?>>Webhook (Cloud Run)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="webhook_url" class="form-label">Webhook Base URL</label>
                    <input type="text" name="webhook_url" id="webhook_url" class="form-control" placeholder="https://photobooth.bapel.my.id" value="<?= htmlspecialchars($settings['webhook_url'] ?? '') ?>">
                </div>
                
                <div class="form-group" style="grid-column: 1 / -1; border-top: 1px solid var(--border-color); padding-top: 1rem; margin-top: 0.5rem;">
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
                    <label for="max_photo_file_size" class="form-label">Max Photo File Size (Bytes)</label>
                    <input type="number" name="max_photo_file_size" id="max_photo_file_size" class="form-control" min="102400" value="<?= htmlspecialchars($settings['max_photo_file_size'] ?? 10485760) ?>">
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
                    <label for="smtp_secure" class="form-label">SMTP Secure</label>
                    <select name="smtp_secure" id="smtp_secure" class="form-control">
                        <option value="tls" <?= ($settings['smtp_secure'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS</option>
                        <option value="ssl" <?= ($settings['smtp_secure'] ?? 'tls') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                        <option value="" <?= ($settings['smtp_secure'] ?? 'tls') === '' ? 'selected' : '' ?>>None</option>
                    </select>
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
                    <label for="print_method" class="form-label">Print Method</label>
                    <select name="print_method" id="print_method" class="form-control">
                        <option value="gdi" <?= ($settings['print_method'] ?? 'gdi') === 'gdi' ? 'selected' : '' ?>>GDI (Windows Printer Driver)</option>
                        <option value="raw" <?= ($settings['print_method'] ?? 'gdi') === 'raw' ? 'selected' : '' ?>>RAW (Raw BMP Data)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="print_queue_interval" class="form-label">Print Queue Interval (sec)</label>
                    <input type="number" name="print_queue_interval" id="print_queue_interval" class="form-control" min="1" value="<?= htmlspecialchars($settings['print_queue_interval'] ?? 5) ?>">
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

        <!-- AI Settings -->
        <div class="card" style="grid-column: 1 / -1;">
            <div class="card-header">
                <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="cpu" style="width: 18px; color: var(--primary);"></i> AI Settings</h3>
            </div>
            <div class="card-body" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                
                <div class="form-group">
                    <label for="ai_enhance_enabled" class="form-label">Enable AI Enhance Step</label>
                    <select name="ai_enhance_enabled" id="ai_enhance_enabled" class="form-control">
                        <option value="1" <?= ($settings['ai_enhance_enabled'] ?? '1') === '1' ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= ($settings['ai_enhance_enabled'] ?? '1') === '0' ? 'selected' : '' ?>>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ai_provider" class="form-label">AI Provider</label>
                    <select name="ai_provider" id="ai_provider" class="form-control">
                        <option value="GEMINI" <?= ($settings['ai_provider'] ?? 'GEMINI') === 'GEMINI' ? 'selected' : '' ?>>Google Gemini AI</option>
                        <option value="REPLICATE" <?= ($settings['ai_provider'] ?? 'GEMINI') === 'REPLICATE' ? 'selected' : '' ?>>Replicate</option>
                    </select>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="ai_enhance_default_prompt" class="form-label">AI Enhance Default Prompt</label>
                    <textarea name="ai_enhance_default_prompt" id="ai_enhance_default_prompt" class="form-control" rows="3"><?= htmlspecialchars($settings['ai_enhance_default_prompt'] ?? 'Enhance this photobooth photo: make it vibrant, well-lit, and professional looking.') ?></textarea>
                </div>

                <!-- Google Cloud Settings -->
                <div class="form-group">
                    <label for="google_cloud_project_id" class="form-label">Google Cloud Project ID</label>
                    <input type="text" name="google_cloud_project_id" id="google_cloud_project_id" class="form-control" value="<?= htmlspecialchars($settings['google_cloud_project_id'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="google_cloud_location" class="form-label">Google Cloud Location</label>
                    <input type="text" name="google_cloud_location" id="google_cloud_location" class="form-control" value="<?= htmlspecialchars($settings['google_cloud_location'] ?? 'us-central1') ?>">
                </div>

                <div class="form-group">
                    <label for="gemini_model" class="form-label">Gemini Model</label>
                    <input type="text" name="gemini_model" id="gemini_model" class="form-control" value="<?= htmlspecialchars($settings['gemini_model'] ?? 'gemini-1.5-pro-preview-0409') ?>">
                </div>

                <!-- Replicate Settings -->
                <div class="form-group">
                    <label for="replicate_api_token" class="form-label">Replicate API Token</label>
                    <input type="password" name="replicate_api_token" id="replicate_api_token" class="form-control" placeholder="Leave empty to keep existing token">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label for="replicate_model" class="form-label">Replicate Model</label>
                    <input type="text" name="replicate_model" id="replicate_model" class="form-control" value="<?= htmlspecialchars($settings['replicate_model'] ?? 'tencent/arc2face') ?>">
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