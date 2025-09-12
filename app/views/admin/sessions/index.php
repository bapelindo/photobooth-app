<div class="page-header">
    <h1>Photo Sessions</h1>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="admin-table sessions-table">
            <thead>
                <tr>
                    <th>Session Details</th>
                    <th>Package Info</th>
                    <th>Status</th>
                    <th>Session Stats</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sessions)): ?>
                <tr>
                    <td colspan="5" class="empty-state">
                        <div class="empty-message">
                            <i data-feather="camera-off" class="empty-icon"></i>
                            <p>No photo sessions found</p>
                            <small>Sessions will appear here after users take photos</small>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($sessions as $session): ?>
                    <tr>
                        <td>
                            <div class="session-details">
                                <div class="session-id">
                                    <strong>#<?= htmlspecialchars($session->id) ?></strong>
                                </div>
                                <div class="session-date">
                                    <i data-feather="calendar" class="date-icon"></i>
                                    <?= date('M j, Y H:i', strtotime($session->created_at)) ?>
                                </div>
                                <div class="transaction-info">
                                    <i data-feather="shopping-bag" class="trans-icon"></i>
                                    Order: #<?= htmlspecialchars($session->order_id ?? 'N/A') ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="package-info">
                                <div class="package-name">
                                    <?= htmlspecialchars($session->package_name ?? 'N/A') ?>
                                </div>
                                <div class="package-price">
                                    Rp <?= number_format($session->amount ?? $session->price ?? 0, 0, ',', '.') ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-<?= $session->session_status === 'completed' ? 'success' : ($session->session_status === 'active' ? 'warning' : 'secondary') ?>">
                                <i data-feather="<?= $session->session_status === 'completed' ? 'check-circle' : ($session->session_status === 'active' ? 'clock' : 'circle') ?>" class="status-icon"></i>
                                <?= ucfirst(htmlspecialchars($session->session_status)) ?>
                            </span>
                        </td>
                        <td>
                            <div class="photo-stats">
                                <div class="stat-item">
                                    <i data-feather="save" class="stat-icon"></i>
                                    <span class="stat-value"><?= htmlspecialchars(max($session->photos_saved ?? 0, $session->photos_count ?? 0)) ?></span>
                                    <span class="stat-label">Saved</span>
                                </div>
                                <div class="stat-item">
                                    <i data-feather="camera" class="stat-icon"></i>
                                    <span class="stat-value"><?= htmlspecialchars(max($session->photos_taken ?? 0, $session->total_photos_captured ?? 0)) ?></span>
                                    <span class="stat-label">Captured</span>
                                </div>
                                <div class="stat-item">
                                    <i data-feather="clock" class="stat-icon"></i>
                                    <span class="stat-value"><?= htmlspecialchars($session->session_duration_seconds ?? 0) ?></span>
                                    <span class="stat-label">Seconds</span>
                                </div>
                            </div>
                        </td>
                        <td class="action-links">
                            <div class="action-buttons">
                                <a href="<?= URLROOT; ?>/admin/sessions/view/<?= $session->id ?>" class="btn btn-secondary btn-sm" title="View Session">
                                    <i data-feather="eye"></i>
                                    <span class="btn-text">View</span>
                                </a>
                                <form action="<?= URLROOT; ?>/admin/sessions/delete/<?= $session->id ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this session?');" style="display:inline;">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete Session">
                                        <i data-feather="trash-2"></i>
                                        <span class="btn-text">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .table-responsive {
        overflow-x: auto;
        margin: -1px;
        min-height: 400px;
    }
    
    .admin-table {
        width: 100%;
        min-width: 1200px;
    }
    
    .admin-table th {
        padding: 1rem 1.5rem;
        font-weight: 600;
        text-align: left;
        border-bottom: 2px solid var(--border-color);
        background: var(--card-secondary);
        color: var(--text-color);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        position: sticky;
        top: 0;
        z-index: 10;
        white-space: nowrap;
    }
    
    .admin-table th:first-child { width: 25%; }
    .admin-table th:nth-child(2) { width: 20%; }
    .admin-table th:nth-child(3) { width: 15%; }
    .admin-table th:nth-child(4) { width: 20%; }
    .admin-table th:nth-child(5) { width: 20%; }
    
    .admin-table td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        vertical-align: top;
    }
    
    .admin-table tr:hover {
        background-color: var(--card-hover);
        transition: var(--transition);
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }
    
    .empty-message {
        color: var(--text-muted);
    }
    
    .empty-icon {
        width: 48px;
        height: 48px;
        margin-bottom: 1rem;
        color: var(--text-dim);
    }
    
    .empty-message p {
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
        color: var(--text-secondary);
    }
    
    .empty-message small {
        font-size: 0.9rem;
        color: var(--text-muted);
    }
    
    .session-details {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        min-width: 240px;
    }
    
    .session-id {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-light);
        margin-bottom: 0.25rem;
    }
    
    .session-date, .transaction-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-muted);
        white-space: nowrap;
    }
    
    .date-icon, .trans-icon {
        width: 14px;
        height: 14px;
    }
    
    .package-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        min-width: 180px;
    }
    
    .package-name {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-color);
        line-height: 1.3;
    }
    
    .package-price {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--success-color);
        white-space: nowrap;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    
    .status-icon {
        width: 14px;
        height: 14px;
    }
    
    .status-success {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.1));
        color: var(--success-color);
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    
    .status-warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1));
        color: var(--warning-color);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    
    .status-secondary {
        background: linear-gradient(135deg, rgba(100, 116, 139, 0.2), rgba(100, 116, 139, 0.1));
        color: var(--text-muted);
        border: 1px solid rgba(100, 116, 139, 0.3);
    }
    
    .photo-stats {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        min-width: 160px;
    }
    
    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }
    
    .stat-icon {
        width: 16px;
        height: 16px;
        color: var(--primary-color);
    }
    
    .stat-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-color);
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: lowercase;
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: stretch;
        min-width: 120px;
    }
    
    .action-buttons .btn-sm {
        padding: 0.6rem 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        border-radius: 0.5rem;
        transition: var(--transition);
        white-space: nowrap;
        text-align: center;
    }
    
    .action-buttons .btn-sm:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow);
    }
    
    /* Responsive Design */
    @media (max-width: 1400px) {
        .admin-table {
            min-width: 1100px;
        }
    }
    
    @media (max-width: 1200px) {
        .btn-text {
            display: none;
        }
        
        .admin-table {
            min-width: 1000px;
        }
        
        .action-buttons .btn-sm {
            padding: 0.5rem;
        }
        
        .session-details {
            min-width: 200px;
        }
        
        .package-info {
            min-width: 150px;
        }
        
        .photo-stats {
            min-width: 120px;
        }
        
        .action-buttons {
            min-width: 100px;
        }
    }
    
    @media (max-width: 1024px) {
        .admin-table {
            min-width: 900px;
        }
        
        .admin-table th,
        .admin-table td {
            padding: 0.75rem 1rem;
        }
        
        .session-details {
            min-width: 180px;
            gap: 0.5rem;
        }
        
        .package-info {
            min-width: 130px;
        }
        
        .photo-stats {
            min-width: 100px;
        }
        
        .action-buttons {
            min-width: 80px;
        }
    }
    
    @media (max-width: 768px) {
        .admin-table {
            min-width: 800px;
            font-size: 0.875rem;
        }
        
        .admin-table th,
        .admin-table td {
            padding: 0.5rem 0.75rem;
        }
        
        .session-details {
            gap: 0.4rem;
            min-width: 160px;
        }
        
        .session-id {
            font-size: 1rem;
        }
        
        .session-date, .transaction-info {
            font-size: 0.8rem;
        }
        
        .package-name {
            font-size: 0.9rem;
        }
        
        .package-price {
            font-size: 0.85rem;
        }
        
        .photo-stats {
            gap: 0.4rem;
            min-width: 90px;
        }
        
        .stat-item {
            gap: 0.3rem;
        }
        
        .stat-value {
            font-size: 0.95rem;
        }
        
        .stat-label {
            font-size: 0.75rem;
        }
        
        .action-buttons {
            min-width: 70px;
            gap: 0.3rem;
        }
        
        .action-buttons .btn-sm {
            padding: 0.4rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .status-badge {
            padding: 0.3rem 0.5rem;
            font-size: 0.7rem;
        }
    }
    
    @media (max-width: 480px) {
        .table-responsive {
            margin: 0 -1rem;
        }
        
        .admin-table {
            min-width: 700px;
            font-size: 0.8rem;
        }
        
        .admin-table th,
        .admin-table td {
            padding: 0.4rem 0.5rem;
        }
        
        .session-details {
            min-width: 140px;
            gap: 0.25rem;
        }
        
        .session-id {
            font-size: 0.9rem;
        }
        
        .session-date, .transaction-info {
            font-size: 0.75rem;
        }
        
        .package-info {
            min-width: 110px;
            gap: 0.25rem;
        }
        
        .package-name {
            font-size: 0.8rem;
        }
        
        .package-price {
            font-size: 0.8rem;
        }
        
        .photo-stats {
            min-width: 80px;
            gap: 0.25rem;
        }
        
        .stat-item {
            gap: 0.25rem;
        }
        
        .stat-value {
            font-size: 0.85rem;
        }
        
        .stat-label {
            font-size: 0.7rem;
        }
        
        .action-buttons {
            min-width: 60px;
            gap: 0.25rem;
        }
        
        .action-buttons .btn-sm {
            padding: 0.3rem 0.4rem;
            font-size: 0.7rem;
        }
        
        .status-badge {
            padding: 0.25rem 0.4rem;
            font-size: 0.65rem;
        }
    }
</style>