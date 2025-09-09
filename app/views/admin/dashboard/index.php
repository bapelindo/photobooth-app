<div class="page-header">
    <h1>Dashboard</h1>
</div>

<div class="dashboard-grid">
    <div class="stat-card">
        <div class="card-icon" style="background-color: #E0E7FF;">
            <i data-feather="dollar-sign" style="color: #4F46E5;"></i>
        </div>
        <div class="card-content">
            <h3 class="card-title">Revenue Today</h3>
            <p class="card-value">Rp <?= number_format($summary->revenue_today, 0, ',', '.') ?></p>
        </div>
    </div>
     <div class="stat-card">
        <div class="card-icon" style="background-color: #FEF3C7;">
            <i data-feather="shopping-cart" style="color: #D97706;"></i>
        </div>
        <div class="card-content">
            <h3 class="card-title">Transactions Today</h3>
            <p class="card-value"><?= $summary->transactions_today ?></p>
        </div>
    </div>
     <div class="stat-card">
        <div class="card-icon" style="background-color: #D1FAE5;">
            <i data-feather="bar-chart-2" style="color: #059669;"></i>
        </div>
        <div class="card-content">
            <h3 class="card-title">Total Revenue</h3>
            <p class="card-value">Rp <?= number_format($summary->total_revenue, 0, ',', '.') ?></p>
        </div>
    </div>
     <div class="stat-card">
        <div class="card-icon" style="background-color: #FEE2E2;">
            <i data-feather="check-circle" style="color: #DC2626;"></i>
        </div>
        <div class="card-content">
            <h3 class="card-title">Total Transactions</h3>
            <p class="card-value"><?= $summary->total_transactions ?></p>
        </div>
    </div>
</div>

<!-- Queue Statistics -->
<div class="section-container card" style="margin-top: 2rem;">
    <div class="section-header">
        <h2 class="section-title">Queue System Status</h2>
        <a href="/photobooth-app/public/admin/queue" class="btn btn-primary">Manage Queues</a>
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
            <?php foreach ($popular_packages as $package): ?>
            <tr>
                <td><strong><?= htmlspecialchars($package->name) ?></strong></td>
                <td><span class="badge"><?= $package->transaction_count ?></span></td>
            </tr>
            <?php endforeach; ?>
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
    fetch('/photobooth-app/public/admin/queue-stats')
        .then(response => response.json())
        .then(data => {
            // Update email queue stats
            const emailCard = document.querySelector('.stat-card:has([data-feather="mail"])');
            if (emailCard) {
                const emailValue = emailCard.querySelector('.card-value');
                const emailSubtitle = emailCard.querySelector('.card-subtitle');
                
                emailValue.textContent = `${data.email_stats.pending || 0} pending`;
                emailSubtitle.textContent = `${data.email_stats.completed || 0} completed • ${data.email_stats.failed || 0} failed`;
            }
            
            // Update print queue stats
            const printCard = document.querySelector('.stat-card:has([data-feather="printer"])');
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
</script>

<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    .stat-card {
        background-color: var(--card-bg);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
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
    .card-title { font-size: 0.9rem; color: var(--text-muted); margin: 0 0 5px 0; }
    .card-value { font-size: 1.75rem; font-weight: 700; color: var(--text-color); margin: 0; }
    .section-container { margin-top: 2.5rem; }
    .section-header { padding: 1.5rem; border-bottom: 1px solid var(--border-color); }
    .section-title { font-size: 1.25rem; font-weight: 600; margin: 0; }
    .badge {
        background-color: #E0E7FF;
        color: #4F46E5;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.875rem;
    }
</style>