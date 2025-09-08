<div class="page-header">
    <h1>Email Queue Management</h1>
    <div class="page-actions">
        <button onclick="processQueue()" class="btn btn-primary" id="process-btn">
            ⚡ Process Queue
        </button>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
    <!-- Queue Statistics -->
    <div class="card" style="padding: 2rem;">
        <h2 style="margin-top: 0;">Queue Statistics</h2>
        <div class="stats-grid">
            <?php 
            $statsMap = [
                'pending' => ['label' => 'Pending', 'color' => 'warning'],
                'processing' => ['label' => 'Processing', 'color' => 'info'],
                'sent' => ['label' => 'Sent', 'color' => 'success'],
                'failed' => ['label' => 'Failed', 'color' => 'danger']
            ];
            
            $statsCounts = [];
            foreach ($stats as $stat) {
                $statsCounts[$stat->status] = $stat->count;
            }
            ?>
            
            <?php foreach ($statsMap as $status => $config): ?>
                <div class="stat-card stat-<?= $config['color'] ?>">
                    <div class="stat-number"><?= $statsCounts[$status] ?? 0 ?></div>
                    <div class="stat-label"><?= $config['label'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Worker Status -->
    <div class="card" style="padding: 2rem;">
        <h2 style="margin-top: 0;">Background Worker</h2>
        <div class="worker-info">
            <p><strong>Status:</strong> <span id="worker-status" class="badge badge-warning">Unknown</span></p>
            <p><strong>Last Process:</strong> <span id="last-process">-</span></p>
            
            <div style="margin-top: 1rem;">
                <h4>Start Worker (Windows):</h4>
                <code style="background: #f0f0f0; padding: 10px; display: block; border-radius: 5px;">
                    cd <?= dirname(APPROOT) ?><br>
                    php scripts\email_worker.php
                </code>
                
                <h4 style="margin-top: 1rem;">Or run manually:</h4>
                <button onclick="processQueue()" class="btn btn-secondary">Process Now</button>
            </div>
        </div>
    </div>
</div>

<!-- Pending Emails List -->
<div class="card" style="padding: 2rem;">
    <h2 style="margin-top: 0;">Pending Emails (Latest 20)</h2>
    
    <?php if (!empty($pending_emails)): ?>
        <div class="table-container">
            <table class="data-table">
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
                    <?php foreach ($pending_emails as $email): ?>
                        <tr>
                            <td><?= $email->id ?></td>
                            <td>
                                <?= htmlspecialchars($email->recipient_email) ?>
                                <?php if ($email->recipient_name): ?>
                                    <br><small><?= htmlspecialchars($email->recipient_name) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars(substr($email->subject, 0, 50)) ?>...</td>
                            <td>
                                <?php 
                                $attachments = json_decode($email->attachments ?: '[]', true);
                                echo count($attachments) . ' files';
                                ?>
                            </td>
                            <td><?= $email->attempts ?>/<?= $email->max_attempts ?></td>
                            <td><?= date('M j, H:i', strtotime($email->created_at)) ?></td>
                            <td>
                                <span class="badge badge-<?= $email->status === 'failed' ? 'danger' : 'warning' ?>">
                                    <?= ucfirst($email->status) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 2rem; color: #666;">
            <p>🎉 No pending emails in queue!</p>
        </div>
    <?php endif; ?>
</div>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
    }
    
    .stat-card {
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        color: white;
    }
    
    .stat-warning { background: #ff9800; }
    .stat-info { background: #2196f3; }
    .stat-success { background: #4caf50; }
    .stat-danger { background: #f44336; }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .worker-info code {
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    
    .data-table th,
    .data-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    
    .data-table th {
        background-color: #f5f5f5;
        font-weight: 600;
    }
    
    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 0.375rem;
        text-transform: uppercase;
    }
    
    .badge-warning { background-color: #fef3c7; color: #d97706; }
    .badge-danger { background-color: #fecaca; color: #dc2626; }
    .badge-success { background-color: #dcfce7; color: #15803d; }
</style>

<script>
function processQueue() {
    const btn = document.getElementById('process-btn');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '⏳ Processing...';
    btn.disabled = true;
    
    fetch('<?= URLROOT ?>/admin/email-queue/process', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Success: ${data.message}`);
            // Reload page to show updated stats
            window.location.reload();
        } else {
            alert(`Error: ${data.message}`);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to process queue');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// Auto-refresh stats every 30 seconds
setInterval(() => {
    fetch('<?= URLROOT ?>/admin/email-queue')
        .then(response => response.text())
        .then(html => {
            // Extract just the stats numbers and update them
            // This is a simple implementation - in production you'd want a proper API
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newStats = doc.querySelectorAll('.stat-number');
            const currentStats = document.querySelectorAll('.stat-number');
            
            newStats.forEach((newStat, index) => {
                if (currentStats[index]) {
                    currentStats[index].textContent = newStat.textContent;
                }
            });
            
            document.getElementById('last-process').textContent = new Date().toLocaleTimeString();
        })
        .catch(error => console.log('Auto-refresh failed:', error));
}, 30000);
</script>