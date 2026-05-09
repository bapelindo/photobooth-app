<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="mail" style="color: var(--primary);"></i>
            Email Queue Management
        </h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">Monitor and manually process the background email queue.</p>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: center;">
        <button onclick="processQueue()" class="btn btn-primary" id="process-btn">
            <i data-feather="zap"></i> Process Queue
        </button>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
    <!-- Queue Statistics -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="bar-chart-2" style="width: 18px; color: var(--primary);"></i> Queue Statistics</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <?php 
                $statsMap = [
                    'pending' => ['label' => 'Pending', 'bg' => 'var(--warning-bg)', 'color' => 'var(--warning)'],
                    'processing' => ['label' => 'Processing', 'bg' => 'var(--primary-light)', 'color' => 'var(--primary)'],
                    'sent' => ['label' => 'Sent', 'bg' => 'var(--success-bg)', 'color' => 'var(--success)'],
                    'failed' => ['label' => 'Failed', 'bg' => 'var(--danger-bg)', 'color' => 'var(--danger)']
                ];
                
                $statsCounts = [];
                if(isset($stats)) {
                    foreach ($stats as $stat) {
                        $statsCounts[$stat->status] = $stat->count;
                    }
                }
                ?>
                
                <?php foreach ($statsMap as $status => $config): ?>
                    <div style="background-color: <?= $config['bg'] ?>; border-radius: var(--radius-md); padding: 1.25rem; text-align: center; border: 1px solid <?= str_replace('-bg)', ')', str_replace('var(--', 'rgba(', $config['bg'])) ?>;">
                        <div class="stat-number" style="font-size: 1.75rem; font-weight: 700; color: <?= $config['color'] ?>; margin-bottom: 0.25rem;"><?= $statsCounts[$status] ?? 0 ?></div>
                        <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: <?= $config['color'] ?>; opacity: 0.8;"><?= $config['label'] ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Worker Status -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="server" style="width: 18px; color: var(--primary);"></i> Background Worker</h3>
        </div>
        <div class="card-body" style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                    <span style="color: var(--text-muted); font-weight: 500;">Status</span>
                    <span id="worker-status" class="badge badge-warning">Unknown</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                    <span style="color: var(--text-muted); font-weight: 500;">Last Process</span>
                    <span id="last-process" style="font-weight: 600; color: var(--text-main);">-</span>
                </div>
            </div>
            
            <div style="background-color: var(--bg-surface-hover); border-radius: var(--radius-md); padding: 1rem; border: 1px solid var(--border-color);">
                <h4 style="margin: 0 0 0.5rem 0; font-size: 0.875rem; color: var(--text-main);">Start Worker (Windows CLI)</h4>
                <code style="display: block; background-color: var(--bg-body); padding: 0.75rem; border-radius: 4px; border: 1px solid #e2e8f0; font-family: monospace; font-size: 0.75rem; color: var(--text-main);">cd <?= dirname(APPROOT) ?><br>php scripts\email_worker.php</code>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Or run one batch manually</span>
                    <button onclick="processQueue()" class="btn btn-secondary btn-sm">Process Now</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Emails List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="list" style="width: 18px; color: var(--primary);"></i> Pending Emails (Latest 20)</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Recipient</th>
                    <th>Subject</th>
                    <th>Attachments</th>
                    <th>Attempts</th>
                    <th>Created</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pending_emails)): ?>
                    <?php foreach ($pending_emails as $email): ?>
                        <tr>
                            <td style="font-weight: 600;">#<?= $email->id ?></td>
                            <td>
                                <div style="font-weight: 500; color: var(--text-main);"><?= htmlspecialchars($email->recipient_email) ?></div>
                                <?php if ($email->recipient_name): ?>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($email->recipient_name) ?></div>
                                <?php endif; ?>
                            </td>
                            <td title="<?= htmlspecialchars($email->subject) ?>" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text-muted);">
                                <?= htmlspecialchars($email->subject) ?>
                            </td>
                            <td style="color: var(--text-muted);">
                                <?php 
                                $attachments = json_decode($email->attachments ?: '[]', true);
                                echo count($attachments) . ' files';
                                ?>
                            </td>
                            <td style="font-weight: 500;"><?= $email->attempts ?>/<?= $email->max_attempts ?></td>
                            <td style="font-size: 0.875rem; color: var(--text-muted);"><?= date('M j, H:i', strtotime($email->created_at)) ?></td>
                            <td>
                                <?php $sClass = $email->status === 'failed' ? 'badge-danger' : 'badge-warning'; ?>
                                <span class="badge <?= $sClass ?>">
                                    <?= ucfirst($email->status) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem;">
                                <i data-feather="check-circle" style="width: 32px; height: 32px; color: var(--success); opacity: 0.8;"></i>
                                <span style="font-size: 1.125rem; font-weight: 500; color: var(--text-main);">All caught up!</span>
                                <span style="font-size: 0.875rem;">No pending emails in the queue.</span>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function processQueue() {
    const btn = document.getElementById('process-btn');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i data-feather="loader" class="spin"></i> Processing...';
    if(typeof feather !== 'undefined') feather.replace();
    btn.disabled = true;
    
    fetch('<?= URLROOT ?>/admin/email-queue/process', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if(typeof showToast === 'function') showToast(`Success: ${data.message}`, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            if(typeof showToast === 'function') showToast(`Error: ${data.message}`, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if(typeof showToast === 'function') showToast('Failed to process queue', 'error');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        if(typeof feather !== 'undefined') feather.replace();
        btn.disabled = false;
    });
}

// Auto-refresh stats every 30 seconds
setInterval(() => {
    fetch('<?= URLROOT ?>/admin/email-queue')
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newStats = doc.querySelectorAll('.stat-number');
            const currentStats = document.querySelectorAll('.stat-number');
            
            newStats.forEach((newStat, index) => {
                if (currentStats[index]) currentStats[index].textContent = newStat.textContent;
            });
            
            const lastProcessEl = document.getElementById('last-process');
            if(lastProcessEl) lastProcessEl.textContent = new Date().toLocaleTimeString();
        })
        .catch(error => console.log('Auto-refresh failed:', error));
}, 30000);
</script>
<style>
@keyframes spin { 100% { transform: rotate(360deg); } }
.spin { animation: spin 1s linear infinite; }
</style>