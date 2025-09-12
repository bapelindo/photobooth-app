<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <h1>Manage Packages</h1>
    <a href="<?= URLROOT; ?>/admin/packages/create" class="btn btn-primary">
        <i data-feather="plus"></i> Create New Package
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Package Details</th>
                    <th>Pricing</th>
                    <th>Limits</th>
                    <th>Settings</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($packages as $package): ?>
                <tr>
                    <td>
                        <div class="package-name">
                            <strong><?= htmlspecialchars($package->name) ?></strong>
                        </div>
                        <div class="package-description">
                            <?= htmlspecialchars($package->description ?? 'No description') ?>
                        </div>
                    </td>
                    <td>
                        <div class="price-display">
                            <span class="price">Rp <?= number_format($package->price, 0, ',', '.') ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="limits-info">
                            <div class="limit-item">
                                <i data-feather="printer" class="limit-icon"></i>
                                <span><?= htmlspecialchars($package->photo_limit ?? 2) ?> prints</span>
                            </div>
                            <div class="limit-item">
                                <i data-feather="image" class="limit-icon"></i>
                                <span><?= htmlspecialchars($package->photo_slots ?? 4) ?> photos</span>
                            </div>
                            <div class="limit-item">
                                <i data-feather="layers" class="limit-icon"></i>
                                <span><?= htmlspecialchars($package->frame_limit ?? 1) ?> frames</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="settings-info">
                            <div class="setting-item">
                                <i data-feather="clock" class="setting-icon"></i>
                                <span><?= floor(($package->session_duration ?? 300) / 60) ?> min</span>
                            </div>
                            <div class="setting-item">
                                <i data-feather="save" class="setting-icon"></i>
                                <span><?= htmlspecialchars($package->max_save_photos ?? 20) ?> max</span>
                            </div>
                        </div>
                    </td>
                    <td class="action-links">
                        <div class="action-buttons">
                            <a href="<?= URLROOT; ?>/admin/packages/edit/<?= $package->id ?>" class="btn btn-secondary btn-sm" title="Edit Package">
                                <i data-feather="edit-2"></i>
                                <span class="btn-text">Edit</span>
                            </a>
                            <form action="<?= URLROOT; ?>/admin/packages/delete/<?= $package->id ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?');" style="display:inline;">
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Package">
                                    <i data-feather="trash-2"></i>
                                    <span class="btn-text">Delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
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
    
    .package-name {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 0.25rem;
    }
    
    .package-description {
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.4;
        max-width: 200px;
    }
    
    .price-display {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--success-color);
    }
    
    .limits-info, .settings-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .limit-item, .setting-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }
    
    .limit-icon, .setting-icon {
        width: 14px;
        height: 14px;
        color: var(--text-muted);
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    
    .action-buttons .btn-sm {
        padding: 0.5rem 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        border-radius: 0.5rem;
        transition: var(--transition);
        white-space: nowrap;
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
    }
    
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch !important;
        }
        
        .admin-table {
            min-width: 700px;
        }
        
        .package-description {
            max-width: 150px;
        }
        
        .limits-info, .settings-info {
            gap: 0.25rem;
        }
        
        .limit-item, .setting-item {
            font-size: 0.8rem;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .action-buttons .btn-sm {
            width: 100%;
            justify-content: center;
        }
    }
</style>