<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="grid" style="color: var(--primary);"></i>
            Dashboard Overview
        </h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">Welcome back, here's what's happening today.</p>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: center;">
        <button class="btn btn-secondary" onclick="refreshDashboard()">
            <i data-feather="refresh-cw"></i> Refresh
        </button>
        <div style="position: relative;" id="quick-actions-dropdown">
            <button class="btn btn-primary" onclick="toggleQuickActions()">
                <i data-feather="zap"></i> Quick Actions <i data-feather="chevron-down"></i>
            </button>
            <div id="quick-actions-menu" style="display: none; position: absolute; right: 0; top: 100%; margin-top: 0.5rem; background: white; border: 1px solid var(--border-color); border-radius: var(--radius-md); box-shadow: var(--shadow-lg); min-width: 200px; z-index: 50; overflow: hidden;">
                <a href="#" onclick="exportData('all'); return false;" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: var(--text-main); text-decoration: none; font-size: 0.875rem; transition: background 0.2s;">
                    <i data-feather="download" style="width: 16px;"></i> Export Data
                </a>
                <a href="#" onclick="clearCache(); return false;" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: var(--text-main); text-decoration: none; font-size: 0.875rem; transition: background 0.2s;">
                    <i data-feather="trash-2" style="width: 16px;"></i> Clear Cache
                </a>
                <a href="#" onclick="showSystemInfo(); return false;" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: var(--text-main); text-decoration: none; font-size: 0.875rem; transition: background 0.2s;">
                    <i data-feather="info" style="width: 16px;"></i> System Info
                </a>
                <a href="<?= URLROOT ?>/admin/download-logs" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: var(--text-main); text-decoration: none; font-size: 0.875rem; transition: background 0.2s; border-top: 1px solid var(--border-color);">
                    <i data-feather="file-text" style="width: 16px;"></i> Download Logs
                </a>
            </div>
        </div>
    </div>
</div>

<?php if (isset($error_message)): ?>
<div style="background-color: var(--danger-bg); border: 1px solid var(--danger); color: var(--danger-text); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
    <i data-feather="alert-triangle"></i>
    <span style="font-weight: 500; font-size: 0.875rem;"><?= htmlspecialchars($error_message) ?></span>
</div>
<?php endif; ?>

<!-- Top Metrics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Revenue Today -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-body" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                <i data-feather="dollar-sign"></i>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Revenue Today</p>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin: 0;">Rp <?= number_format($summary->revenue_today ?? 0, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <!-- Transactions Today -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-body" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--warning-bg); color: var(--warning); display: flex; align-items: center; justify-content: center;">
                <i data-feather="shopping-cart"></i>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Transactions Today</p>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin: 0;"><?= $summary->transactions_today ?? 0 ?></h3>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-body" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--success-bg); color: var(--success); display: flex; align-items: center; justify-content: center;">
                <i data-feather="trending-up"></i>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Total Revenue</p>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin: 0;">Rp <?= number_format($summary->total_revenue ?? 0, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <!-- Total Transactions -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-body" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background-color: #f3e8ff; color: #7e22ce; display: flex; align-items: center; justify-content: center;">
                <i data-feather="check-circle"></i>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Total Transactions</p>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin: 0;"><?= $summary->total_transactions ?? 0 ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Operations & System -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">System Operations</h3>
        <a href="<?= URLROOT ?>/admin/queue" class="btn btn-secondary btn-sm">Manage Queues</a>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
            
            <div style="border-right: 1px solid var(--border-color); padding-right: 1.5rem;" class="op-stat">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.75rem;">
                    <i data-feather="mail" style="width: 16px;"></i> Email Queue
                </div>
                <h4 style="font-size: 1.25rem; margin: 0 0 0.25rem 0;" id="eq-pending"><?= $email_queue_stats->pending ?? 0 ?> pending</h4>
                <div style="font-size: 0.75rem; color: var(--text-muted);" id="eq-sub">
                    <?= $email_queue_stats->completed ?? 0 ?> completed &bull; <?= $email_queue_stats->failed ?? 0 ?> failed
                </div>
            </div>

            <div style="border-right: 1px solid var(--border-color); padding-right: 1.5rem;" class="op-stat">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.75rem;">
                    <i data-feather="printer" style="width: 16px;"></i> Print Queue
                </div>
                <h4 style="font-size: 1.25rem; margin: 0 0 0.25rem 0;" id="pq-pending"><?= $print_queue_stats->pending ?? 0 ?> pending</h4>
                <div style="font-size: 0.75rem; color: var(--text-muted);" id="pq-sub">
                    <?= $print_queue_stats->completed ?? 0 ?> completed &bull; <?= $print_queue_stats->failed ?? 0 ?> failed
                </div>
            </div>

            <div style="border-right: 1px solid var(--border-color); padding-right: 1.5rem;" class="op-stat">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.75rem;">
                    <i data-feather="camera" style="width: 16px;"></i> Sessions Today
                </div>
                <h4 style="font-size: 1.25rem; margin: 0 0 0.25rem 0;"><?= $session_stats->sessions_today ?? 0 ?> sessions</h4>
                <div style="font-size: 0.75rem; color: var(--text-muted);">
                    <?= $session_stats->completed_sessions ?? 0 ?> completed &bull; Avg <?= $session_stats->avg_photos_per_session ?? 0 ?> photos
                </div>
            </div>

            <div class="op-stat" style="padding-right: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.75rem;">
                    <i data-feather="activity" style="width: 16px;"></i> Health
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;" id="sys-status">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--success);"></span>
                    <h4 style="font-size: 1.25rem; margin: 0; color: var(--success);">Active</h4>
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted);" id="sys-updated">Updated just now</div>
            </div>

        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .op-stat { border-right: none !important; padding-right: 0 !important; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem; }
        .op-stat:last-child { border-bottom: none; padding-bottom: 0; }
    }
</style>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; align-items: start;">
    <!-- Popular Packages -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Popular Packages</h3>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Package</th>
                        <th style="text-align: right;">Transactions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($popular_packages)): ?>
                        <?php foreach ($popular_packages as $package): ?>
                        <tr>
                            <td style="font-weight: 500;"><?= htmlspecialchars($package->name ?? 'Unknown') ?></td>
                            <td style="text-align: right;">
                                <span class="badge badge-primary"><?= $package->transaction_count ?? 0 ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                No package data available
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daily Sessions -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">7-Day Session Overview</h3>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Sessions</th>
                        <th style="text-align: right;">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daily_session_stats)): ?>
                        <?php foreach ($daily_session_stats as $stat): ?>
                        <tr>
                            <td><?= date('M j', strtotime($stat->date)) ?></td>
                            <td><span class="badge badge-secondary" style="background: var(--bg-body); border: 1px solid var(--border-color);"><?= $stat->sessions ?></span></td>
                            <td style="text-align: right; font-weight: 600;">Rp <?= number_format($stat->revenue ?? 0, 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                No session data available
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- System Info Modal -->
<div id="system-info-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(2px); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); width: 90%; max-width: 600px; max-height: 85vh; display: flex; flex-direction: column;">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.25rem;">System Information</h3>
            <button onclick="document.getElementById('system-info-modal').style.display='none'" style="background: none; border: none; cursor: pointer; color: var(--text-muted);"><i data-feather="x"></i></button>
        </div>
        <div style="padding: 1.5rem; overflow-y: auto; font-family: monospace; font-size: 0.875rem; background: var(--bg-body); color: var(--text-main);" id="sys-info-content">
            Loading...
        </div>
    </div>
</div>

<script>
    function toggleQuickActions() {
        const menu = document.getElementById('quick-actions-menu');
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    }

    // Close dropdown on outside click
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('quick-actions-dropdown');
        if (dropdown && !dropdown.contains(e.target)) {
            document.getElementById('quick-actions-menu').style.display = 'none';
        }
    });

    // Add hover effect to dropdown items manually since we used inline styles for simplicity
    document.querySelectorAll('#quick-actions-menu a').forEach(a => {
        a.addEventListener('mouseenter', () => a.style.backgroundColor = 'var(--bg-surface-hover)');
        a.addEventListener('mouseleave', () => a.style.backgroundColor = 'transparent');
    });

    function refreshDashboard() {
        location.reload();
    }

    function exportData(type) {
        document.getElementById('quick-actions-menu').style.display = 'none';
        window.open(`<?= URLROOT ?>/admin/export-data/${type}`, '_blank');
    }

    function clearCache() {
        document.getElementById('quick-actions-menu').style.display = 'none';
        if(confirm('Are you sure you want to clear the system cache?')) {
            fetch('<?= URLROOT ?>/admin/clear-cache', { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                if(data.success) showToast('Cache cleared successfully', 'success');
                else showToast(data.message || 'Failed to clear cache', 'error');
            })
            .catch(err => showToast('Error clearing cache', 'error'));
        }
    }

    function showSystemInfo() {
        document.getElementById('quick-actions-menu').style.display = 'none';
        const modal = document.getElementById('system-info-modal');
        const content = document.getElementById('sys-info-content');
        modal.style.display = 'flex';
        content.textContent = 'Loading system info...';

        fetch('<?= URLROOT ?>/admin/system-info')
        .then(res => res.json())
        .then(data => {
            if(data.error) { content.textContent = 'Error: ' + data.error; return; }
            content.innerHTML = `
                <div style="margin-bottom: 1rem;"><strong style="color: var(--primary);">PHP Version:</strong> ${data.php_version}</div>
                <div style="margin-bottom: 1rem;"><strong style="color: var(--primary);">Server:</strong> ${data.server_software}</div>
                <div style="margin-bottom: 1rem;"><strong style="color: var(--primary);">Limits:</strong>
                    <ul style="list-style: none; padding: 0; margin: 0.5rem 0 0 1rem;">
                        <li>Memory: ${data.memory_limit}</li>
                        <li>Max Execution: ${data.max_execution_time}s</li>
                        <li>Upload Max: ${data.upload_max_filesize}</li>
                        <li>Post Max: ${data.post_max_size}</li>
                    </ul>
                </div>
                <div style="margin-bottom: 1rem;"><strong style="color: var(--primary);">Disk Space:</strong>
                    <ul style="list-style: none; padding: 0; margin: 0.5rem 0 0 1rem;">
                        <li>Free: ${data.disk_free_space}</li>
                        <li>Total: ${data.disk_total_space}</li>
                    </ul>
                </div>
                <div><strong style="color: var(--primary);">Extensions:</strong>
                    <ul style="list-style: none; padding: 0; margin: 0.5rem 0 0 1rem;">
                        <li>GD: ${data.extensions.gd ? '<span style="color:var(--success);">Yes</span>' : '<span style="color:var(--danger);">No</span>'}</li>
                        <li>cURL: ${data.extensions.curl ? '<span style="color:var(--success);">Yes</span>' : '<span style="color:var(--danger);">No</span>'}</li>
                        <li>PDO: ${data.extensions.pdo ? '<span style="color:var(--success);">Yes</span>' : '<span style="color:var(--danger);">No</span>'}</li>
                        <li>ZIP: ${data.extensions.zip ? '<span style="color:var(--success);">Yes</span>' : '<span style="color:var(--danger);">No</span>'}</li>
                    </ul>
                </div>
            `;
        })
        .catch(err => { content.textContent = 'Failed to load system info'; });
    }

    // Auto-refresh queue stats
    function refreshQueueStats() {
        fetch('<?= URLROOT ?>/admin/queue-stats')
        .then(res => res.json())
        .then(data => {
            document.getElementById('eq-pending').textContent = `${data.email_stats.pending || 0} pending`;
            document.getElementById('eq-sub').innerHTML = `${data.email_stats.completed || 0} completed &bull; ${data.email_stats.failed || 0} failed`;

            document.getElementById('pq-pending').textContent = `${data.print_stats.pending || 0} pending`;
            document.getElementById('pq-sub').innerHTML = `${data.print_stats.completed || 0} completed &bull; ${data.print_stats.failed || 0} failed`;

            const updated = document.getElementById('sys-updated');
            if(updated) updated.textContent = 'Updated just now';

            const sysStatus = document.getElementById('sys-status');
            const totalPending = (data.email_stats.pending || 0) + (data.print_stats.pending || 0);
            const totalFailed = (data.email_stats.failed || 0) + (data.print_stats.failed || 0);

            if (totalFailed > 5) {
                sysStatus.innerHTML = `<span style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--danger);"></span><h4 style="font-size: 1.25rem; margin: 0; color: var(--danger);">Issues</h4>`;
            } else if (totalPending > 10) {
                sysStatus.innerHTML = `<span style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--warning);"></span><h4 style="font-size: 1.25rem; margin: 0; color: var(--warning);">Busy</h4>`;
            } else {
                sysStatus.innerHTML = `<span style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--success);"></span><h4 style="font-size: 1.25rem; margin: 0; color: var(--success);">Active</h4>`;
            }
        }).catch(err => console.error('Queue refresh failed'));
    }

    setInterval(refreshQueueStats, 30000);
</script>