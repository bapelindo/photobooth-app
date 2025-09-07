<div class="page-header">
    <h1>Reports & Analytics</h1>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
    <div class="card" style="padding: 2rem; text-align: center;">
        <h3 style="color: var(--primary-color); font-size: 2.5rem; margin: 0;"><?= number_format($stats->total_sessions ?? 0) ?></h3>
        <p style="margin: 0.5rem 0 0; color: var(--text-muted);">Total Sessions</p>
    </div>
    <div class="card" style="padding: 2rem; text-align: center;">
        <h3 style="color: #28a745; font-size: 2.5rem; margin: 0;"><?= number_format($stats->completed_sessions ?? 0) ?></h3>
        <p style="margin: 0.5rem 0 0; color: var(--text-muted);">Completed Sessions</p>
    </div>
    <div class="card" style="padding: 2rem; text-align: center;">
        <h3 style="color: #dc3545; font-size: 2.5rem; margin: 0;">Rp <?= number_format($stats->total_revenue ?? 0, 0, ',', '.') ?></h3>
        <p style="margin: 0.5rem 0 0; color: var(--text-muted);">Total Revenue</p>
    </div>
    <div class="card" style="padding: 2rem; text-align: center;">
        <h3 style="color: #ffc107; font-size: 2.5rem; margin: 0;"><?= number_format($stats->total_photostrips ?? 0) ?></h3>
        <p style="margin: 0.5rem 0 0; color: var(--text-muted);">Total Photostrips</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
    <div class="card" style="padding: 2rem;">
        <h2 style="margin-top: 0;">Revenue Trends (Last 30 Days)</h2>
        <div style="height: 300px; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
            <canvas id="revenueChart" width="400" height="200"></canvas>
        </div>
    </div>
    <div class="card" style="padding: 2rem;">
        <h2 style="margin-top: 0;">Package Popularity</h2>
        <div class="package-stats">
            <?php if (!empty($packageStats)): ?>
                <?php foreach ($packageStats as $package): ?>
                    <div class="package-stat-item">
                        <div class="package-name"><?= htmlspecialchars($package->package_name) ?></div>
                        <div class="package-count"><?= number_format($package->usage_count) ?> sessions</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= ($package->usage_count / max(1, $packageStats[0]->usage_count)) * 100 ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: var(--text-muted);">No package data available</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card" style="padding: 2rem;">
    <h2 style="margin-top: 0;">Recent Activity</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Sessions</th>
                <th>Revenue</th>
                <th>Avg. Session Duration</th>
                <th>Print Success Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($dailyStats)): ?>
                <?php foreach ($dailyStats as $daily): ?>
                <tr>
                    <td><?= date('M j, Y', strtotime($daily->date)) ?></td>
                    <td><?= number_format($daily->session_count) ?></td>
                    <td>Rp <?= number_format($daily->daily_revenue, 0, ',', '.') ?></td>
                    <td><?= number_format($daily->avg_duration ?? 0) ?>s</td>
                    <td><?= number_format($daily->print_success_rate ?? 0, 1) ?>%</td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted);">No activity data available</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
    .package-stats {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .package-stat-item {
        padding: 1rem 0;
        border-bottom: 1px solid var(--border-color);
    }
    .package-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .package-count {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }
    .progress-bar {
        width: 100%;
        height: 6px;
        background-color: #f3f4f6;
        border-radius: 3px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background-color: var(--primary-color);
        transition: width 0.3s ease;
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