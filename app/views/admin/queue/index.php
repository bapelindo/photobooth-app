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
        <div class="card-icon" style="background-color: #E0F2FE;">
            <i data-feather="mail" style="color: #0284C7;"></i>
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
        <div class="card-icon" style="background-color: #F3E8FF;">
            <i data-feather="printer" style="color: #7C3AED;"></i>
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
        <div class="card-icon" style="background-color: #DCFCE7;">
            <i data-feather="activity" style="color: #16A34A;"></i>
        </div>
        <div class="card-content">
            <h3 class="card-title">Workers Status</h3>
            <p class="card-value" id="worker-status">
                <span class="status-dot" style="background: #16A34A;"></span> Active
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
                                    <i data-feather="alert-circle" class="error-icon" title="<?= htmlspecialchars($job->error_message) ?>"></i>
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
                                    <i data-feather="alert-circle" class="error-icon" title="<?= htmlspecialchars($job->error_message) ?>"></i>
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
.page-actions {
    display: flex;
    gap: 0.5rem;
}

.section-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-pending { background: #FEF3C7; color: #D97706; }
.status-processing { background: #DBEAFE; color: #2563EB; }
.status-completed { background: #D1FAE5; color: #059669; }
.status-failed { background: #FEE2E2; color: #DC2626; }

.priority-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
    background: #F3F4F6;
    color: #374151;
}

.priority-1 { background: #FEE2E2; color: #DC2626; }
.priority-3 { background: #FEF3C7; color: #D97706; }
.priority-5 { background: #D1FAE5; color: #059669; }

.btn-icon {
    padding: 0.25rem;
    background: none;
    border: none;
    cursor: pointer;
    border-radius: 0.25rem;
    margin: 0 0.125rem;
}

.btn-icon:hover {
    background: #F3F4F6;
}

.btn-icon.btn-danger:hover {
    background: #FEE2E2;
    color: #DC2626;
}

.error-icon {
    color: #DC2626;
    width: 16px;
    height: 16px;
    margin-left: 0.25rem;
}

.job-subject, .file-path {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.text-center {
    text-align: center;
    padding: 2rem;
    color: #6B7280;
}

.status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 6px;
}

.card-subtitle {
    color: #6B7280;
    font-size: 0.875rem;
    margin-top: 4px;
}

.table-container {
    overflow-x: auto;
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
        // Add retry functionality
        console.log('Retry email job:', id);
    }
}

function retryPrintJob(id) {
    if (confirm('Retry this print job?')) {
        // Add retry functionality
        console.log('Retry print job:', id);
    }
}

function deleteEmailJob(id) {
    if (confirm('Delete this email job?')) {
        // Add delete functionality
        console.log('Delete email job:', id);
    }
}

function deletePrintJob(id) {
    if (confirm('Delete this print job?')) {
        // Add delete functionality
        console.log('Delete print job:', id);
    }
}

function clearCompletedJobs() {
    if (confirm('Clear all completed jobs? This action cannot be undone.')) {
        // Add clear completed functionality
        console.log('Clear completed jobs');
    }
}

// Initialize feather icons
feather.replace();
</script>