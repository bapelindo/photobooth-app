<div class="page-header">
    <h1>
        <i data-feather="home"></i>
        Dashboard
    </h1>
    <div class="page-actions">
        <button class="btn btn-secondary" onclick="refreshDashboard()">
            <i data-feather="refresh-cw"></i>
            Refresh
        </button>
        <div class="dropdown">
            <button class="btn btn-primary" onclick="toggleDropdown('adminDropdown')">
                <i data-feather="settings"></i>
                Quick Actions
                <i data-feather="chevron-down"></i>
            </button>
            <div class="dropdown-content" id="adminDropdown">
                <a href="#" onclick="exportData('all')">
                    <i data-feather="download"></i>
                    Export All Data
                </a>
                <a href="#" onclick="clearCache()">
                    <i data-feather="trash-2"></i>
                    Clear Cache
                </a>
                <a href="#" onclick="showSystemInfo()">
                    <i data-feather="info"></i>
                    System Info
                </a>
                <a href="<?= URLROOT ?>/admin/download-logs">
                    <i data-feather="file-text"></i>
                    Download Logs
                </a>
            </div>
        </div>
    </div>
</div>

<?php if (isset($error_message)): ?>
<div class="alert alert-error">
    <i data-feather="alert-triangle"></i>
    <?= htmlspecialchars($error_message) ?>
</div>
<?php endif; ?>

<div class="dashboard-grid">
    <div class="stat-card">
        <div class="card-icon" style="background-color: #E0E7FF;">
            <i data-feather="dollar-sign" style="color: #4F46E5;"></i>
        </div>
        <div class="card-content">
            <h3 class="card-title">Revenue Today</h3>
            <p class="card-value">Rp <?= number_format($summary->revenue_today ?? 0, 0, ',', '.') ?></p>
        </div>
    </div>
     <div class="stat-card">
        <div class="card-icon" style="background-color: #FEF3C7;">
            <i data-feather="shopping-cart" style="color: #D97706;"></i>
        </div>
        <div class="card-content">
            <h3 class="card-title">Transactions Today</h3>
            <p class="card-value"><?= $summary->transactions_today ?? 0 ?></p>
        </div>
    </div>
     <div class="stat-card">
        <div class="card-icon" style="background-color: #D1FAE5;">
            <i data-feather="bar-chart-2" style="color: #059669;"></i>
        </div>
        <div class="card-content">
            <h3 class="card-title">Total Revenue</h3>
            <p class="card-value">Rp <?= number_format($summary->total_revenue ?? 0, 0, ',', '.') ?></p>
        </div>
    </div>
     <div class="stat-card">
        <div class="card-icon" style="background-color: #FEE2E2;">
            <i data-feather="check-circle" style="color: #DC2626;"></i>
        </div>
        <div class="card-content">
            <h3 class="card-title">Total Transactions</h3>
            <p class="card-value"><?= $summary->total_transactions ?? 0 ?></p>
        </div>
    </div>
</div>

<!-- Queue Statistics -->
<div class="section-container card" style="margin-top: 2rem;">
    <div class="section-header">
        <h2 class="section-title">Queue System Status</h2>
        <a href="<?= URLROOT ?>/admin/queue" class="btn btn-primary">Manage Queues</a>
    </div>
    
    <div class="dashboard-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
        <!-- Email Queue Stats -->
        <div class="stat-card">
            <div class="card-icon" style="background-color: #E0F2FE;">
                <i data-feather="mail" style="color: #0284C7;"></i>
            </div>
            <div class="card-content">
                <h3 class="card-title">Email Queue</h3>
                <p class="card-value"><?= $email_queue_stats->pending ?? 0 ?> pending</p>
                <small class="card-subtitle">
                    <?= $email_queue_stats->completed ?? 0 ?> completed • 
                    <?= $email_queue_stats->failed ?? 0 ?> failed
                </small>
            </div>
        </div>
        
        <!-- Print Queue Stats -->
        <div class="stat-card">
            <div class="card-icon" style="background-color: #F3E8FF;">
                <i data-feather="printer" style="color: #7C3AED;"></i>
            </div>
            <div class="card-content">
                <h3 class="card-title">Print Queue</h3>
                <p class="card-value"><?= $print_queue_stats->pending ?? 0 ?> pending</p>
                <small class="card-subtitle">
                    <?= $print_queue_stats->completed ?? 0 ?> completed • 
                    <?= $print_queue_stats->failed ?? 0 ?> failed
                </small>
            </div>
        </div>
        
        <!-- Photo Sessions Today -->
        <div class="stat-card">
            <div class="card-icon" style="background-color: #FEF7CD;">
                <i data-feather="camera" style="color: #CA8A04;"></i>
            </div>
            <div class="card-content">
                <h3 class="card-title">Sessions Today</h3>
                <p class="card-value"><?= $session_stats->sessions_today ?? 0 ?></p>
                <small class="card-subtitle">
                    <?= $session_stats->completed_sessions ?? 0 ?> completed • 
                    <?= $session_stats->avg_photos_per_session ?? 0 ?> avg photos
                </small>
            </div>
        </div>
        
        <!-- System Status -->
        <div class="stat-card">
            <div class="card-icon" style="background-color: #DCFCE7;">
                <i data-feather="activity" style="color: #16A34A;"></i>
            </div>
            <div class="card-content">
                <h3 class="card-title">System Status</h3>
                <p class="card-value" id="system-status">
                    <span class="status-dot" style="background: #16A34A;"></span> Active
                </p>
                <small class="card-subtitle" id="last-updated">Updated just now</small>
            </div>
        </div>
    </div>
</div>

<div class="section-container card">
    <div class="section-header">
        <h2 class="section-title">Most Popular Packages</h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>Package Name</th>
                <th>Total Transactions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($popular_packages)): ?>
                <?php foreach ($popular_packages as $package): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($package->name ?? 'Unknown') ?></strong></td>
                    <td><span class="badge"><?= $package->transaction_count ?? 0 ?></span></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                        <i data-feather="inbox"></i><br>
                        No popular packages data available
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
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

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}
</style>

<script>
// Auto-refresh queue statistics every 30 seconds
function refreshQueueStats() {
    fetch('<?= URLROOT ?>/admin/queue-stats')
        .then(response => response.json())
        .then(data => {
            // Update email queue stats
            const emailCards = document.querySelectorAll('.stat-card');
            let emailCard = null;
            
            // Find email card by checking for mail icon
            emailCards.forEach(card => {
                if (card.querySelector('[data-feather="mail"]')) {
                    emailCard = card;
                }
            });
            if (emailCard) {
                const emailValue = emailCard.querySelector('.card-value');
                const emailSubtitle = emailCard.querySelector('.card-subtitle');
                
                emailValue.textContent = `${data.email_stats.pending || 0} pending`;
                emailSubtitle.textContent = `${data.email_stats.completed || 0} completed • ${data.email_stats.failed || 0} failed`;
            }
            
            // Update print queue stats
            let printCard = null;
            emailCards.forEach(card => {
                if (card.querySelector('[data-feather="printer"]')) {
                    printCard = card;
                }
            });
            if (printCard) {
                const printValue = printCard.querySelector('.card-value');
                const printSubtitle = printCard.querySelector('.card-subtitle');
                
                printValue.textContent = `${data.print_stats.pending || 0} pending`;
                printSubtitle.textContent = `${data.print_stats.completed || 0} completed • ${data.print_stats.failed || 0} failed`;
            }
            
            // Update last updated time
            const lastUpdated = document.getElementById('last-updated');
            if (lastUpdated) {
                lastUpdated.textContent = 'Updated just now';
            }
            
            // Update system status based on queue health
            const systemStatus = document.getElementById('system-status');
            const statusDot = systemStatus?.querySelector('.status-dot');
            
            const totalPending = (data.email_stats.pending || 0) + (data.print_stats.pending || 0);
            const totalFailed = (data.email_stats.failed || 0) + (data.print_stats.failed || 0);
            
            if (totalFailed > 5) {
                statusDot.style.background = '#EF4444'; // Red
                systemStatus.innerHTML = '<span class="status-dot" style="background: #EF4444;"></span> Issues';
            } else if (totalPending > 10) {
                statusDot.style.background = '#F59E0B'; // Yellow
                systemStatus.innerHTML = '<span class="status-dot" style="background: #F59E0B;"></span> Busy';
            } else {
                statusDot.style.background = '#16A34A'; // Green
                systemStatus.innerHTML = '<span class="status-dot" style="background: #16A34A;"></span> Active';
            }
        })
        .catch(error => {
            console.error('Failed to refresh queue stats:', error);
            const lastUpdated = document.getElementById('last-updated');
            if (lastUpdated) {
                lastUpdated.textContent = 'Update failed';
                lastUpdated.style.color = '#EF4444';
            }
        });
}

// Start auto-refresh
setInterval(refreshQueueStats, 30000); // Every 30 seconds

// Initial call to update immediately
refreshQueueStats();

// Advanced dashboard functions
function refreshDashboard() {
    location.reload();
}

function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    dropdown.classList.toggle('show');
    
    // Close other dropdowns
    const dropdowns = document.querySelectorAll('.dropdown-content');
    dropdowns.forEach(dd => {
        if (dd.id !== id) {
            dd.classList.remove('show');
        }
    });
}

function exportData(type) {
    const url = `<?= URLROOT ?>/admin/export-data/${type}`;
    window.open(url, '_blank');
    toggleDropdown('adminDropdown');
}

function clearCache() {
    if (confirm('Are you sure you want to clear the cache?')) {
        fetch('<?= URLROOT ?>/admin/clear-cache', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cache cleared successfully: ' + data.message);
            } else {
                alert('Failed to clear cache: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error clearing cache: ' + error.message);
        });
    }
    toggleDropdown('adminDropdown');
}

function showSystemInfo() {
    fetch('<?= URLROOT ?>/admin/system-info')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error loading system info: ' + data.error);
                return;
            }
            
            let info = `
=== SYSTEM INFORMATION ===

PHP Version: ${data.php_version}
Server: ${data.server_software}
Memory Limit: ${data.memory_limit}
Max Execution Time: ${data.max_execution_time}s
Upload Max Filesize: ${data.upload_max_filesize}
Post Max Size: ${data.post_max_size}

Disk Free Space: ${data.disk_free_space}
Disk Total Space: ${data.disk_total_space}

Current Time: ${data.current_time}
Timezone: ${data.timezone}

Extensions:
- GD: ${data.extensions.gd ? 'Enabled' : 'Disabled'}
- cURL: ${data.extensions.curl ? 'Enabled' : 'Disabled'}
- PDO: ${data.extensions.pdo ? 'Enabled' : 'Disabled'}
- ZIP: ${data.extensions.zip ? 'Enabled' : 'Disabled'}
            `;
            
            // Create a modal or use alert for now
            const modal = document.createElement('div');
            modal.className = 'system-info-modal';
            modal.innerHTML = `
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>System Information</h3>
                        <button onclick="this.closest('.system-info-modal').remove()">×</button>
                    </div>
                    <pre class="system-info-text">${info}</pre>
                </div>
            `;
            document.body.appendChild(modal);
        })
        .catch(error => {
            alert('Error loading system info: ' + error.message);
        });
    
    toggleDropdown('adminDropdown');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.matches('.dropdown button')) {
        const dropdowns = document.querySelectorAll('.dropdown-content');
        dropdowns.forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    }
});
</script>

<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
    .card-icon .feather { width: 28px; height: 28px; }
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
    .section-container { 
        margin-top: 2.5rem; 
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .section-header { 
        padding: 2rem; 
        border-bottom: 1px solid #e2e8f0; 
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    .section-title { 
        font-size: 1.375rem; 
        font-weight: 700; 
        margin: 0; 
        color: #1e293b;
    }
    .badge {
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        color: #4338ca;
        padding: 0.375rem 1rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.875rem;
        border: 1px solid #a5b4fc;
    }
    
    /* Page actions */
    .page-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }
    
    /* Dropdown */
    .dropdown {
        position: relative;
        display: inline-block;
    }
    
    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background-color: var(--card-bg);
        min-width: 220px;
        box-shadow: var(--shadow-lg);
        border-radius: var(--border-radius);
        border: 1px solid var(--border-color);
        z-index: 1000;
        padding: 0.5rem 0;
        margin-top: 0.5rem;
    }
    
    .dropdown-content.show {
        display: block;
        animation: slideDown 0.2s ease-out;
    }
    
    .dropdown-content a {
        color: var(--text-color);
        padding: 0.75rem 1.25rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
        transition: var(--transition);
    }
    
    .dropdown-content a:hover {
        background-color: #f8fafc;
        color: var(--primary-color);
    }
    
    .dropdown-content a .feather {
        width: 16px;
        height: 16px;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* System Info Modal */
    .system-info-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease-out;
    }
    
    .modal-content {
        background-color: var(--card-bg);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        max-width: 600px;
        max-height: 80vh;
        width: 90%;
        overflow: hidden;
        animation: slideIn 0.3s ease-out;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-color);
        background: linear-gradient(135deg, #fafbfc 0%, #f8fafc 100%);
    }
    
    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-color);
    }
    
    .modal-header button {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--text-muted);
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        transition: var(--transition);
    }
    
    .modal-header button:hover {
        background-color: #fee2e2;
        color: #dc2626;
    }
    
    .system-info-text {
        padding: 2rem;
        margin: 0;
        background-color: var(--bg-secondary);
        color: var(--text-color);
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        line-height: 1.6;
        max-height: 60vh;
        overflow-y: auto;
        white-space: pre-wrap;
    }
    
    @media (max-width: 768px) {
        .page-actions {
            flex-direction: column;
            align-items: stretch;
            gap: 0.75rem;
            margin-top: 1rem;
        }
        
        .dropdown-content {
            right: auto;
            left: 0;
            min-width: 100%;
        }
        
        .modal-content {
            width: 95%;
            max-height: 90vh;
        }
        
        .modal-header {
            padding: 1rem;
        }
        
        .system-info-text {
            padding: 1rem;
            font-size: 0.8rem;
        }
    }
</style>