<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <i data-feather="bar-chart-2" class="page-icon"></i>
            <h1>Reports & Analytics</h1>
        </div>
        <div class="page-subtitle">
            <span>Monitor performance and track business metrics</span>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card sessions-card">
        <div class="stat-icon sessions-icon">
            <i data-feather="users"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-value"><?= number_format($stats->total_sessions ?? 0) ?></h3>
            <p class="stat-label">Total Sessions</p>
        </div>
    </div>
    <div class="stat-card completed-card">
        <div class="stat-icon completed-icon">
            <i data-feather="check-circle"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-value"><?= number_format($stats->completed_sessions ?? 0) ?></h3>
            <p class="stat-label">Completed Sessions</p>
        </div>
    </div>
    <div class="stat-card revenue-card">
        <div class="stat-icon revenue-icon">
            <i data-feather="dollar-sign"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-value">Rp <?= number_format($stats->total_revenue ?? 0, 0, ',', '.') ?></h3>
            <p class="stat-label">Total Revenue</p>
        </div>
    </div>
    <div class="stat-card photostrips-card">
        <div class="stat-icon photostrips-icon">
            <i data-feather="image"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-value"><?= number_format($stats->total_photostrips ?? 0) ?></h3>
            <p class="stat-label">Total Photostrips</p>
        </div>
    </div>
</div>

<div class="charts-grid">
    <div class="chart-card">
        <div class="chart-header">
            <h2 class="chart-title">
                <i data-feather="trending-up" class="chart-icon"></i>
                Revenue Trends (Last 30 Days)
            </h2>
        </div>
        <div class="chart-container">
            <canvas id="revenueChart" width="400" height="200"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <div class="chart-header">
            <h2 class="chart-title">
                <i data-feather="package" class="chart-icon"></i>
                Package Popularity
            </h2>
        </div>
        <div class="package-stats">
            <?php if (!empty($packageStats)): ?>
                <?php foreach ($packageStats as $package): ?>
                    <div class="package-stat-item">
                        <div class="package-info">
                            <div class="package-name"><?= htmlspecialchars($package->package_name ?? 'Unknown') ?></div>
                            <div class="package-count"><?= number_format($package->usage_count ?? 0) ?> sessions</div>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= !empty($packageStats) && ($packageStats[0]->usage_count ?? 0) > 0 ? (($package->usage_count ?? 0) / $packageStats[0]->usage_count) * 100 : 0 ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-stats">
                    <i data-feather="inbox" class="empty-icon"></i>
                    <p>No package data available</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="activity-card">
    <div class="activity-header">
        <h2 class="activity-title">
            <i data-feather="activity" class="activity-icon"></i>
            Recent Activity
        </h2>
    </div>
    <div class="table-responsive">
        <table class="activity-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Sessions</th>
                    <th>Revenue</th>
                    <th>Avg. Duration</th>
                    <th>Print Success</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dailyStats)): ?>
                    <?php foreach ($dailyStats as $daily): ?>
                    <tr>
                        <td>
                            <div class="date-cell">
                                <i data-feather="calendar" class="date-icon"></i>
                                <?= date('M j, Y', strtotime($daily->date)) ?>
                            </div>
                        </td>
                        <td>
                            <div class="metric-cell">
                                <i data-feather="users" class="metric-icon"></i>
                                <?= number_format($daily->session_count ?? 0) ?>
                            </div>
                        </td>
                        <td>
                            <div class="revenue-cell">
                                <i data-feather="dollar-sign" class="revenue-icon"></i>
                                Rp <?= number_format($daily->daily_revenue ?? 0, 0, ',', '.') ?>
                            </div>
                        </td>
                        <td>
                            <div class="duration-cell">
                                <i data-feather="clock" class="duration-icon"></i>
                                <?= number_format($daily->avg_duration ?? 0) ?>s
                            </div>
                        </td>
                        <td>
                            <div class="success-rate <?= ($daily->print_success_rate ?? 0) >= 90 ? 'high-success' : (($daily->print_success_rate ?? 0) >= 70 ? 'medium-success' : 'low-success') ?>">
                                <i data-feather="printer" class="success-icon"></i>
                                <?= number_format($daily->print_success_rate ?? 0, 1) ?>%
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty-state">
                            <div class="empty-activity">
                                <i data-feather="inbox" class="empty-icon"></i>
                                <p>No activity data available</p>
                                <small>Activity will appear here as users interact with the photobooth</small>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Page Header */
    .page-header {
        background: var(--card-bg);
        background-image: var(--gradient-card);
        border-radius: var(--border-radius);
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        transition: var(--transition);
    }
    
    .page-header:hover {
        box-shadow: var(--shadow-lg);
        border-color: var(--border-light);
    }
    
    .page-header-content {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .page-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .page-icon {
        width: 32px;
        height: 32px;
        color: var(--primary-light);
        filter: drop-shadow(0 2px 4px rgba(59, 130, 246, 0.3));
    }
    
    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-color);
        background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .page-subtitle span {
        font-size: 1rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
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
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .stat-icon .feather { 
        width: 28px; 
        height: 28px; 
    }
    
    .sessions-icon {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(59, 130, 246, 0.1));
        border: 1px solid rgba(59, 130, 246, 0.3);
    }
    .sessions-icon i { color: var(--primary-light); }
    
    .completed-icon {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.1));
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    .completed-icon i { color: var(--success-color); }
    
    .revenue-icon {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.1));
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    .revenue-icon i { color: var(--error-light); }
    
    .photostrips-icon {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1));
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    .photostrips-icon i { color: var(--warning-color); }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-color);
        margin: 0 0 0.25rem 0;
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin: 0;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Charts Grid */
    .charts-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .chart-card {
        background: var(--card-bg);
        background-image: var(--gradient-card);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: var(--transition);
    }
    
    .chart-card:hover {
        box-shadow: var(--shadow-lg);
        border-color: var(--border-light);
    }
    
    .chart-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-color);
        background: var(--card-secondary);
    }
    
    .chart-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-color);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .chart-icon {
        width: 20px;
        height: 20px;
        color: var(--primary-light);
    }
    
    .chart-container {
        padding: 2rem;
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--card-bg);
    }
    
    /* Package Stats */
    .package-stats {
        padding: 2rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .package-stat-item {
        padding: 1rem 0;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .package-stat-item:last-child {
        border-bottom: none;
    }
    
    .package-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .package-name {
        font-weight: 600;
        color: var(--text-color);
        font-size: 0.95rem;
    }
    
    .package-count {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    
    .progress-bar {
        width: 100%;
        height: 8px;
        background: var(--bg-tertiary);
        border-radius: 4px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-light) 100%);
        transition: width 0.5s ease;
    }
    
    .empty-stats {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-muted);
    }
    
    .empty-icon {
        width: 48px;
        height: 48px;
        margin-bottom: 1rem;
        color: var(--text-dim);
    }

    /* Activity Table */
    .activity-card {
        background: var(--card-bg);
        background-image: var(--gradient-card);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: var(--transition);
    }
    
    .activity-card:hover {
        box-shadow: var(--shadow-lg);
        border-color: var(--border-light);
    }
    
    .activity-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-color);
        background: var(--card-secondary);
    }
    
    .activity-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-color);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .activity-icon {
        width: 20px;
        height: 20px;
        color: var(--primary-light);
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .activity-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }
    
    .activity-table th {
        background: var(--card-secondary);
        color: var(--text-color);
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--border-color);
    }
    
    .activity-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .activity-table tr:hover {
        background-color: var(--card-hover);
        transition: var(--transition);
    }
    
    .date-cell, .metric-cell, .revenue-cell, .duration-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-color);
        font-weight: 500;
    }
    
    .date-icon, .metric-icon, .revenue-icon, .duration-icon, .success-icon {
        width: 16px;
        height: 16px;
        color: var(--text-muted);
    }
    
    .success-rate {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.75rem;
        border-radius: 1rem;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .high-success {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.1));
        color: var(--success-color);
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    
    .medium-success {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1));
        color: var(--warning-color);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    
    .low-success {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.1));
        color: var(--error-color);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .empty-activity {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-muted);
    }
    
    .empty-activity .empty-icon {
        width: 48px;
        height: 48px;
        margin-bottom: 1rem;
        color: var(--text-dim);
    }
    
    .empty-activity p {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
        color: var(--text-secondary);
    }
    
    .empty-activity small {
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }
        
        .page-header h1 {
            font-size: 1.5rem;
        }
        
        .page-icon {
            width: 24px;
            height: 24px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .stat-card {
            padding: 1.5rem;
            flex-direction: column;
            text-align: center;
        }
        
        .stat-value {
            font-size: 1.75rem;
        }
        
        .activity-table th,
        .activity-table td {
            padding: 0.75rem 0.5rem;
        }
        
        .activity-table {
            font-size: 0.875rem;
        }
    }
</style>

<script>
// Simple chart implementation
const canvas = document.getElementById('revenueChart');
if (canvas) {
    const ctx = canvas.getContext('2d');
    const chartData = <?= json_encode($chartData ?? []) ?>;
    
    // Simple line chart implementation
    if (chartData.length > 0) {
        ctx.strokeStyle = '#4F46E5';
        ctx.lineWidth = 2;
        ctx.beginPath();
        
        chartData.forEach((point, index) => {
            const x = (index / (chartData.length - 1)) * canvas.width;
            const y = canvas.height - (point.revenue / Math.max(...chartData.map(d => d.revenue)) * canvas.height * 0.8) - 20;
            
            if (index === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
        });
        
        ctx.stroke();
    } else {
        ctx.fillStyle = '#6B7280';
        ctx.font = '16px Inter';
        ctx.textAlign = 'center';
        ctx.fillText('No data available', canvas.width / 2, canvas.height / 2);
    }
}
</script>