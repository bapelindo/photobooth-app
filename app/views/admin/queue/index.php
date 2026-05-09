<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="list" style="color: var(--primary);"></i>
            Queue Management
        </h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">Monitor and manage background email and print jobs.</p>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: center;">
        <button class="btn btn-secondary" onclick="refreshQueues()">
            <i data-feather="refresh-cw"></i> Refresh
        </button>
        <button class="btn btn-primary" onclick="clearCompletedJobs()">
            <i data-feather="trash-2"></i> Clear Completed
        </button>
    </div>
</div>

<!-- Overview Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Email Queue -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-body" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                <i data-feather="mail"></i>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Email Queue</p>
                <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin: 0;"><?= $email_stats->pending ?? 0 ?></h3>
                    <span style="font-size: 0.875rem; color: var(--text-muted);">pending</span>
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                    <?= $email_stats->completed ?? 0 ?> done &bull; <?= $email_stats->failed ?? 0 ?> fail
                </div>
            </div>
        </div>
    </div>

    <!-- Print Queue -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-body" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background-color: #f3e8ff; color: #7e22ce; display: flex; align-items: center; justify-content: center;">
                <i data-feather="printer"></i>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Print Queue</p>
                <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin: 0;"><?= $print_stats->pending ?? 0 ?></h3>
                    <span style="font-size: 0.875rem; color: var(--text-muted);">pending</span>
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                    <?= $print_stats->completed ?? 0 ?> done &bull; <?= $print_stats->failed ?? 0 ?> fail
                </div>
            </div>
        </div>
    </div>

    <!-- Workers -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-body" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--success-bg); color: var(--success); display: flex; align-items: center; justify-content: center;">
                <i data-feather="activity"></i>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Workers Status</p>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.25rem;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--success);"></span>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--success); margin: 0;">Active</h3>
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Monitoring active</div>
            </div>
        </div>
    </div>
</div>

<!-- Email Queue Table -->
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="mail" style="width: 18px; color: var(--primary);"></i> Email Jobs</h3>
        <select id="email-filter" class="form-control" style="width: auto; padding: 0.25rem 0.5rem;" onchange="filterEmailJobs()">
            <option value="all">All Status</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="completed">Completed</option>
            <option value="failed">Failed</option>
        </select>
    </div>
    <div class="table-responsive">
        <table class="table" id="email-queue-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Retries</th>
                    <th>Created</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($email_jobs)): ?>
                    <tr><td colspan="8" style="text-align: center; padding: 2rem; color: var(--text-muted);">No email jobs found</td></tr>
                <?php else: ?>
                    <?php foreach ($email_jobs as $job): ?>
                        <tr data-status="<?= $job->status ?>">
                            <td style="font-weight: 600;">#<?= $job->id ?></td>
                            <td><?= htmlspecialchars($job->email) ?></td>
                            <td title="<?= htmlspecialchars($job->subject) ?>" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars($job->subject) ?>
                            </td>
                            <td><span class="badge badge-secondary"><?= $job->priority ?></span></td>
                            <td>
                                <?php 
                                    $s = $job->status;
                                    $b = 'badge-secondary';
                                    if($s==='completed') $b = 'badge-success';
                                    elseif($s==='failed') $b = 'badge-danger';
                                    elseif($s==='processing') $b = 'badge-primary';
                                    elseif($s==='pending') $b = 'badge-warning';
                                ?>
                                <span class="badge <?= $b ?>"><?= ucfirst($s) ?></span>
                            </td>
                            <td>
                                <?= $job->retries ?>/3
                                <?php if ($job->retries > 0 && $job->error_message): ?>
                                    <i data-feather="alert-circle" style="width: 14px; color: var(--danger); vertical-align: middle; margin-left: 0.25rem;" title="<?= htmlspecialchars($job->error_message) ?>"></i>
                                <?php endif; ?>
                            </td>
                            <td style="font-size: 0.875rem; color: var(--text-muted);"><?= date('M j, H:i', strtotime($job->created_at)) ?></td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <?php if ($job->status === 'failed'): ?>
                                        <button class="btn btn-secondary btn-sm" onclick="retryEmailJob(<?= $job->id ?>)" title="Retry">
                                            <i data-feather="rotate-cw"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn btn-danger btn-sm" onclick="deleteEmailJob(<?= $job->id ?>)" title="Delete">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Print Queue Table -->
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="printer" style="width: 18px; color: var(--primary);"></i> Print Jobs</h3>
        <select id="print-filter" class="form-control" style="width: auto; padding: 0.25rem 0.5rem;" onchange="filterPrintJobs()">
            <option value="all">All Status</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="completed">Completed</option>
            <option value="failed">Failed</option>
        </select>
    </div>
    <div class="table-responsive">
        <table class="table" id="print-queue-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photostrip ID</th>
                    <th>File Path</th>
                    <th>Copies</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Retries</th>
                    <th>Created</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($print_jobs)): ?>
                    <tr><td colspan="9" style="text-align: center; padding: 2rem; color: var(--text-muted);">No print jobs found</td></tr>
                <?php else: ?>
                    <?php foreach ($print_jobs as $job): ?>
                        <tr data-status="<?= $job->status ?>">
                            <td style="font-weight: 600;">#<?= $job->id ?></td>
                            <td>#<?= $job->photostrip_id ?></td>
                            <td title="<?= htmlspecialchars($job->file_path) ?>" style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars(basename($job->file_path)) ?>
                            </td>
                            <td style="font-weight: 500;"><?= $job->copies ?>x</td>
                            <td><span class="badge badge-secondary"><?= $job->priority ?></span></td>
                            <td>
                                <?php 
                                    $s = $job->status;
                                    $b = 'badge-secondary';
                                    if($s==='completed') $b = 'badge-success';
                                    elseif($s==='failed') $b = 'badge-danger';
                                    elseif($s==='processing') $b = 'badge-primary';
                                    elseif($s==='pending') $b = 'badge-warning';
                                ?>
                                <span class="badge <?= $b ?>"><?= ucfirst($s) ?></span>
                            </td>
                            <td>
                                <?= $job->retries ?>/3
                                <?php if ($job->retries > 0 && $job->error_message): ?>
                                    <i data-feather="alert-circle" style="width: 14px; color: var(--danger); vertical-align: middle; margin-left: 0.25rem;" title="<?= htmlspecialchars($job->error_message) ?>"></i>
                                <?php endif; ?>
                            </td>
                            <td style="font-size: 0.875rem; color: var(--text-muted);"><?= date('M j, H:i', strtotime($job->created_at)) ?></td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <?php if ($job->status === 'failed'): ?>
                                        <button class="btn btn-secondary btn-sm" onclick="retryPrintJob(<?= $job->id ?>)" title="Retry">
                                            <i data-feather="rotate-cw"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn btn-danger btn-sm" onclick="deletePrintJob(<?= $job->id ?>)" title="Delete">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    let refreshInterval = setInterval(refreshQueues, 10000);

    function refreshQueues() { location.reload(); }

    function filterEmailJobs() {
        const filter = document.getElementById('email-filter').value;
        document.querySelectorAll('#email-queue-table tbody tr[data-status]').forEach(row => {
            row.style.display = (filter === 'all' || row.dataset.status === filter) ? '' : 'none';
        });
    }

    function filterPrintJobs() {
        const filter = document.getElementById('print-filter').value;
        document.querySelectorAll('#print-queue-table tbody tr[data-status]').forEach(row => {
            row.style.display = (filter === 'all' || row.dataset.status === filter) ? '' : 'none';
        });
    }

    function ajaxAction(url, confirmMsg, successMsg) {
        if (confirm(confirmMsg)) {
            fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json' } })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if(typeof showToast === 'function') showToast(successMsg || data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        if(typeof showToast === 'function') showToast(data.message, 'error');
                    }
                }).catch(err => { if(typeof showToast === 'function') showToast('Error performing action', 'error'); });
        }
    }

    function retryEmailJob(id) { ajaxAction(`/admin/queue/retry/email/${id}`, 'Retry this email job?'); }
    function retryPrintJob(id) { ajaxAction(`/admin/queue/retry/print/${id}`, 'Retry this print job?'); }
    function deleteEmailJob(id) { ajaxAction(`/admin/queue/delete/email/${id}`, 'Delete this email job? This cannot be undone.'); }
    function deletePrintJob(id) { ajaxAction(`/admin/queue/delete/print/${id}`, 'Delete this print job? This cannot be undone.'); }

    function clearCompletedJobs() {
        if (confirm('Clear all completed jobs? This cannot be undone.')) {
            if(typeof showToast === 'function') showToast('Clear completed jobs feature not implemented yet', 'info');
        }
    }
</script>