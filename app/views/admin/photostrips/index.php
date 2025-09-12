<div class="page-header">
    <h1>Photostrips</h1>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="admin-table photostrips-table">
            <thead>
                <tr>
                    <th>Photostrip Info</th>
                    <th>Frame Details</th>
                    <th>Status Overview</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($photostrips)): ?>
                <tr>
                    <td colspan="5" class="empty-state">
                        <div class="empty-message">
                            <i data-feather="image" class="empty-icon"></i>
                            <p>No photostrips found</p>
                            <small>Photostrips will appear here after photo sessions are completed</small>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($photostrips as $photostrip): ?>
                    <tr>
                        <td>
                            <div class="photostrip-info">
                                <div class="photostrip-id">
                                    <strong>#<?= htmlspecialchars($photostrip->id) ?></strong>
                                </div>
                                <div class="session-ref">
                                    <i data-feather="camera" class="session-icon"></i>
                                    Session #<?= htmlspecialchars($photostrip->session_id) ?>
                                </div>
                                <?php if (!empty($photostrip->order_id)): ?>
                                <div class="order-ref">
                                    <i data-feather="shopping-bag" class="order-icon"></i>
                                    Order #<?= htmlspecialchars($photostrip->order_id) ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="frame-details">
                                <div class="frame-name">
                                    <?= htmlspecialchars($photostrip->frame_name ?? 'N/A') ?>
                                </div>
                                <?php if (!empty($photostrip->package_name)): ?>
                                <div class="package-name">
                                    <i data-feather="package" class="package-icon"></i>
                                    <?= htmlspecialchars($photostrip->package_name) ?>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($photostrip->final_image_path)): ?>
                                <div class="has-image">
                                    <i data-feather="check-circle" class="check-icon"></i>
                                    Image Ready
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="status-overview">
                                <div class="status-item">
                                    <span class="status-label">Print:</span>
                                    <span class="status-badge status-<?= ($photostrip->is_printed ?? false) ? 'success' : 'pending' ?>">
                                        <i data-feather="<?= ($photostrip->is_printed ?? false) ? 'check-circle' : 'clock' ?>" class="status-icon"></i>
                                        <?= ($photostrip->is_printed ?? false) ? 'Printed' : 'Pending' ?>
                                    </span>
                                </div>
                                <?php if (isset($photostrip->email_sent)): ?>
                                <div class="status-item">
                                    <span class="status-label">Email:</span>
                                    <span class="status-badge status-<?= $photostrip->email_sent ? 'success' : 'pending' ?>">
                                        <i data-feather="<?= $photostrip->email_sent ? 'mail' : 'mail-x' ?>" class="status-icon"></i>
                                        <?= $photostrip->email_sent ? 'Sent' : 'Not sent' ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="date-info">
                                <div class="created-date">
                                    <?= date('M j, Y', strtotime($photostrip->created_at)) ?>
                                </div>
                                <div class="created-time">
                                    <?= date('H:i', strtotime($photostrip->created_at)) ?>
                                </div>
                            </div>
                        </td>
                        <td class="action-links">
                            <div class="action-buttons">
                                <?php if (!empty($photostrip->final_image_path)): ?>
                                <a href="<?= URLROOT . $photostrip->final_image_path ?>" class="btn btn-success btn-sm" target="_blank" title="View Image">
                                    <i data-feather="external-link"></i>
                                    <span class="btn-text">View</span>
                                </a>
                                <?php endif; ?>
                                <?php if (isset($photostrip->id)): ?>
                                <a href="<?= URLROOT; ?>/admin/photostrips/view/<?= $photostrip->id ?>" class="btn btn-secondary btn-sm" title="View Details">
                                    <i data-feather="eye"></i>
                                    <span class="btn-text">Details</span>
                                </a>
                                <?php endif; ?>
                                <form action="<?= URLROOT; ?>/admin/gallery/delete/<?= $photostrip->id ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this photostrip?');" style="display:inline;">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete Photostrip">
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
        min-width: 900px;
    }
    
    .admin-table th {
        padding: 1rem;
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
    }
    
    .admin-table td {
        padding: 1rem;
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
    
    .photostrip-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .photostrip-id {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-light);
    }
    
    .session-ref, .order-ref {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    
    .session-icon, .order-icon {
        width: 14px;
        height: 14px;
    }
    
    .frame-details {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .frame-name {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-color);
    }
    
    .package-name, .has-image {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    
    .package-icon, .check-icon {
        width: 14px;
        height: 14px;
    }
    
    .has-image {
        color: var(--success-color);
        font-weight: 500;
    }
    
    .status-overview {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .status-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .status-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        width: fit-content;
    }
    
    .status-icon {
        width: 12px;
        height: 12px;
    }
    
    .status-success {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.1));
        color: var(--success-color);
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    
    .status-pending {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1));
        color: var(--warning-color);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    
    .date-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        text-align: center;
    }
    
    .created-date {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-color);
    }
    
    .created-time {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: stretch;
    }
    
    .action-buttons .btn-sm {
        padding: 0.5rem 0.75rem;
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
    @media (max-width: 1200px) {
        .btn-text {
            display: none;
        }
        
        .action-buttons .btn-sm {
            padding: 0.5rem;
        }
        
        .admin-table {
            min-width: 800px;
        }
        
        .action-buttons {
            flex-direction: row;
        }
    }
    
    @media (max-width: 768px) {
        .admin-table {
            min-width: 700px;
        }
        
        .photostrip-info {
            gap: 0.25rem;
        }
        
        .session-ref, .order-ref, .package-name, .has-image {
            font-size: 0.8rem;
        }
        
        .status-overview {
            gap: 0.5rem;
        }
        
        .status-badge {
            padding: 0.3rem 0.5rem;
            font-size: 0.7rem;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .action-buttons .btn-sm {
            width: 100%;
            font-size: 0.75rem;
        }
    }
</style>