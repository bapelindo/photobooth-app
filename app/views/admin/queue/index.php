<div class="page-header">
    <h1>Queue Management</h1>
    <div class="page-actions">
        <button class="btn btn-outline" onclick="refreshQueues()">
            <i data-feather="refresh-cw"></i> Refresh
        </button>
        <button class="btn btn-primary" onclick="clearCompletedJobs()">
            <i data-feather="trash-2"></i> Clear Completed
        </button>
    </div>
</div>

<!-- Queue Overview -->
<div class="dashboard-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="card-icon email-icon">
            <i data-feather="mail"></i>
        </div>
        <div class="card-content">
            <h3 class="card-title">Email Queue</h3>
            <p class="card-value"><?= $email_stats->pending ?? 0 ?> pending</p>
            <small class="card-subtitle">
                <?= $email_stats->completed ?? 0 ?> completed •
                <?= $email_stats->processing ?? 0 ?> processing •
                <?= $email_stats->failed ?? 0 ?> failed
            </small>
        </div>
    </div>

    <div class="stat-card">
        <div class="card-icon print-icon">
            <i data-feather="printer"></i>
        </div>
        <div class="card-content">
            <h3 class="card-title">Print Queue</h3>
            <p class="card-value"><?= $print_stats->pending ?? 0 ?> pending</p>
            <small class="card-subtitle">
                <?= $print_stats->completed ?? 0 ?> completed •
                <?= $print_stats->processing ?? 0 ?> processing •
                <?= $print_stats->failed ?? 0 ?> failed
            </small>
        </div>
    </div>

    <div class="stat-card">
        <div class="card-icon system-icon">
            <i data-feather="activity"></i>
        </div>
        <div class="card-content">
            <h3 class="card-title">Workers Status</h3>
            <p class="card-value" id="worker-status">
                <span class="status-dot active"></span> Active
            </p>
            <small class="card-subtitle" id="last-updated">Check worker processes</small>
        </div>
    </div>
</div>

<!-- Email Queue -->
<div class="section-container card">
    <div class="section-header">
        <h2 class="section-title">Email Queue</h2>
        <div class="section-actions">
            <select id="email-filter" onchange="filterEmailJobs()">
                <option value="all">All Status</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
            </select>
        </div>
    </div>

    <div class="table-container">
        <table id="email-queue-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Retries</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($email_jobs)): ?>
                    <tr>
                        <td colspan="8" class="text-center">No email jobs found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($email_jobs as $job): ?>
                        <tr data-status="<?= $job->status ?>">
                            <td>#<?= $job->id ?></td>
                            <td><?= htmlspecialchars($job->email) ?></td>
                            <td class="job-subject" title="<?= htmlspecialchars($job->subject) ?>">
                                <?= htmlspecialchars(strlen($job->subject) > 50 ? substr($job->subject, 0, 50) . '...' : $job->subject) ?>
                            </td>
                            <td>
                                <span class="priority-badge priority-<?= $job->priority ?>">
                                    <?= $job->priority ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-<?= $job->status ?>">
                                    <?= ucfirst($job->status) ?>
                                </span>
                            </td>
                            <td>
                                <?= $job->retries ?>/3
                                <?php if ($job->retries > 0 && $job->error_message): ?>
                                    <i data-feather="alert-circle" class="error-icon"
                                        title="<?= htmlspecialchars($job->error_message) ?>"></i>
                                <?php endif; ?>
                            </td>
                            <td><?= date('M j, H:i', strtotime($job->created_at)) ?></td>
                            <td>
                                <?php if ($job->status === 'failed'): ?>
                                    <button class="btn-icon" onclick="retryEmailJob(<?= $job->id ?>)" title="Retry">
                                        <i data-feather="rotate-cw"></i>
                                    </button>
                                <?php endif; ?>
                                <button class="btn-icon btn-danger" onclick="deleteEmailJob(<?= $job->id ?>)" title="Delete">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Print Queue -->
<div class="section-container card">
    <div class="section-header">
        <h2 class="section-title">Print Queue</h2>
        <div class="section-actions">
            <select id="print-filter" onchange="filterPrintJobs()">
                <option value="all">All Status</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
            </select>
        </div>
    </div>

    <div class="table-container">
        <table id="print-queue-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photostrip</th>
                    <th>File Path</th>
                    <th>Copies</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Retries</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($print_jobs)): ?>
                    <tr>
                        <td colspan="9" class="text-center">No print jobs found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($print_jobs as $job): ?>
                        <tr data-status="<?= $job->status ?>">
                            <td>#<?= $job->id ?></td>
                            <td>#<?= $job->photostrip_id ?></td>
                            <td class="file-path" title="<?= htmlspecialchars($job->file_path) ?>">
                                <?= htmlspecialchars(basename($job->file_path)) ?>
                            </td>
                            <td><?= $job->copies ?>x</td>
                            <td>
                                <span class="priority-badge priority-<?= $job->priority ?>">
                                    <?= $job->priority ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-<?= $job->status ?>">
                                    <?= ucfirst($job->status) ?>
                                </span>
                            </td>
                            <td>
                                <?= $job->retries ?>/3
                                <?php if ($job->retries > 0 && $job->error_message): ?>
                                    <i data-feather="alert-circle" class="error-icon"
                                        title="<?= htmlspecialchars($job->error_message) ?>"></i>
                                <?php endif; ?>
                            </td>
                            <td><?= date('M j, H:i', strtotime($job->created_at)) ?></td>
                            <td>
                                <?php if ($job->status === 'failed'): ?>
                                    <button class="btn-icon" onclick="retryPrintJob(<?= $job->id ?>)" title="Retry">
                                        <i data-feather="rotate-cw"></i>
                                    </button>
                                <?php endif; ?>
                                <button class="btn-icon btn-danger" onclick="deletePrintJob(<?= $job->id ?>)" title="Delete">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Dashboard Stat Cards */
    .dashboard-grid {
        display: grid;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: linear-gradient(135deg, var(--card-bg) 0%, var(--card-secondary) 100%);
        border-radius: 1rem;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        padding: 2rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-primary);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--border-light);
    }

    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-icon .feather {
        width: 28px;
        height: 28px;
    }

    .email-icon {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.2), rgba(14, 165, 233, 0.1));
        border: 1px solid rgba(14, 165, 233, 0.3);
    }

    .email-icon i {
        color: #38bdf8;
    }

    .print-icon {
        background: linear-gradient(135deg, rgba(124, 58, 237, 0.2), rgba(124, 58, 237, 0.1));
        border: 1px solid rgba(124, 58, 237, 0.3);
    }

    .print-icon i {
        color: #a855f7;
    }

    .system-icon {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.1));
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .system-icon i {
        color: var(--success-color);
    }

    .card-title {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin: 0 0 8px 0;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .card-value {
        font-size: 1.875rem;
        font-weight: 800;
        color: var(--text-color);
        margin: 0;
        line-height: 1.2;
    }

    .card-subtitle {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin-top: 4px;
    }

    .status-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }

    .status-dot.active {
        background: var(--success-color);
    }

    /* Section Containers */
    .section-container {
        margin-top: 2.5rem;
        background: var(--card-bg);
        background-image: var(--gradient-card);
        border-radius: 1rem;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: var(--transition);
    }

    .section-container:hover {
        box-shadow: var(--shadow-lg);
        border-color: var(--border-light);
    }

    .section-header {
        padding: 2rem;
        border-bottom: 1px solid var(--border-color);
        background: var(--card-secondary);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-title {
        font-size: 1.375rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-color);
        background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .page-actions,
    .section-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .section-actions select {
        background: var(--bg-tertiary);
        color: var(--text-color);
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        transition: var(--transition);
    }

    .section-actions select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Table Styling */
    .table-container {
        overflow-x: auto;
        background: var(--card-bg);
    }

    .table-container table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .table-container th {
        background: var(--card-secondary);
        color: var(--text-color);
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--border-color);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table-container td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-color);
    }

    .table-container tr:hover {
        background-color: var(--card-hover);
        transition: var(--transition);
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.4rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .status-pending {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1));
        color: var(--warning-color);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-processing {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(59, 130, 246, 0.1));
        color: var(--primary-color);
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .status-completed {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.1));
        color: var(--success-color);
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .status-failed {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.1));
        color: var(--error-color);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    /* Priority Badges */
    .priority-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.6rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
    }

    .priority-1 {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.1));
        color: var(--error-color);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .priority-3 {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1));
        color: var(--warning-color);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .priority-5 {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.1));
        color: var(--success-color);
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    /* Action Buttons */
    .btn-icon {
        padding: 0.5rem;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
        cursor: pointer;
        border-radius: 0.5rem;
        margin: 0 0.25rem;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-icon:hover {
        background: var(--card-hover);
        border-color: var(--border-light);
        transform: translateY(-1px);
        box-shadow: var(--shadow);
    }

    .btn-icon.btn-danger:hover {
        background: var(--error-color);
        border-color: var(--error-light);
        color: white;
    }

    .btn-icon .feather {
        width: 16px;
        height: 16px;
    }

    /* Utility Classes */
    .error-icon {
        color: var(--error-color);
        width: 16px;
        height: 16px;
        margin-left: 0.25rem;
    }

    .job-subject,
    .file-path {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .text-center {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-muted);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .section-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .page-actions,
        .section-actions {
            flex-direction: column;
            gap: 0.5rem;
        }

        .table-container {
            font-size: 0.875rem;
        }

        .table-container th,
        .table-container td {
            padding: 0.75rem 0.5rem;
        }

        .job-subject,
        .file-path {
            max-width: 150px;
        }
    }
</style>

<script>
    // Auto-refresh every 10 seconds
    let refreshInterval = setInterval(refreshQueues, 10000);

    function refreshQueues() {
        location.reload();
    }

    function filterEmailJobs() {
        const filter = document.getElementById('email-filter').value;
        const rows = document.querySelectorAll('#email-queue-table tbody tr[data-status]');

        rows.forEach(row => {
            if (filter === 'all' || row.dataset.status === filter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function filterPrintJobs() {
        const filter = document.getElementById('print-filter').value;
        const rows = document.querySelectorAll('#print-queue-table tbody tr[data-status]');

        rows.forEach(row => {
            if (filter === 'all' || row.dataset.status === filter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function retryEmailJob(id) {
        if (confirm('Retry this email job?')) {
            fetch(`/admin/queue/retry/email/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage(data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showMessage(data.message, 'error');
                    }
                })
                .catch(error => {
                    showMessage('Error retrying job', 'error');
                });
        }
    }

    function retryPrintJob(id) {
        if (confirm('Retry this print job?')) {
            fetch(`/admin/queue/retry/print/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage(data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showMessage(data.message, 'error');
                    }
                })
                .catch(error => {
                    showMessage('Error retrying job', 'error');
                });
        }
    }

    function deleteEmailJob(id) {
        if (confirm('Delete this email job? This action cannot be undone.')) {
            fetch(`/admin/queue/delete/email/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage(data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showMessage(data.message, 'error');
                    }
                })
                .catch(error => {
                    showMessage('Error deleting job', 'error');
                });
        }
    }

    function deletePrintJob(id) {
        if (confirm('Delete this print job? This action cannot be undone.')) {
            fetch(`/admin/queue/delete/print/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage(data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showMessage(data.message, 'error');
                    }
                })
                .catch(error => {
                    showMessage('Error deleting job', 'error');
                });
        }
    }

    function clearCompletedJobs() {
        if (confirm('Clear all completed jobs? This action cannot be undone.')) {
            // This would require a separate endpoint
            showMessage('Clear completed jobs feature not implemented yet', 'info');
        }
    }

    // showMessage function is now handled globally by admin layout

    // Initialize feather icons
    feather.replace();
</script>