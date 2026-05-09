<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="bar-chart-2" style="color: var(--primary);"></i>
            Reports & Analytics
        </h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">Monitor performance and track business metrics.</p>
    </div>
</div>

<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="margin-bottom: 0;">
        <div class="card-body" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                <i data-feather="users"></i>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Total Sessions</p>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin: 0;"><?= number_format($stats->total_sessions ?? 0) ?></h3>
            </div>
        </div>
    </div>
    
    <div class="card" style="margin-bottom: 0;">
        <div class="card-body" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--success-bg); color: var(--success); display: flex; align-items: center; justify-content: center;">
                <i data-feather="check-circle"></i>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Completed Sessions</p>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin: 0;"><?= number_format($stats->completed_sessions ?? 0) ?></h3>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 0;">
        <div class="card-body" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--danger-bg); color: var(--danger); display: flex; align-items: center; justify-content: center;">
                <i data-feather="dollar-sign"></i>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Total Revenue</p>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin: 0;">Rp <?= number_format($stats->total_revenue ?? 0, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 0;">
        <div class="card-body" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--warning-bg); color: var(--warning); display: flex; align-items: center; justify-content: center;">
                <i data-feather="image"></i>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Total Photostrips</p>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin: 0;"><?= number_format($stats->total_photostrips ?? 0) ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Charts & Packages -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;" class="charts-layout">
    <div class="card" style="margin-bottom: 0;">
        <div class="card-header" style="display: flex; align-items: center; gap: 0.5rem;">
            <h3 class="card-title" style="margin: 0; display: flex; align-items: center; gap: 0.5rem;"><i data-feather="trending-up" style="color: var(--primary); width: 18px;"></i> Revenue Trends (Last 30 Days)</h3>
        </div>
        <div class="card-body" style="height: 300px; display: flex; align-items: center; justify-content: center;">
            <canvas id="revenueChart" width="400" height="250" style="width: 100%; height: 100%;"></canvas>
        </div>
    </div>
    
    <div class="card" style="margin-bottom: 0;">
        <div class="card-header" style="display: flex; align-items: center; gap: 0.5rem;">
            <h3 class="card-title" style="margin: 0; display: flex; align-items: center; gap: 0.5rem;"><i data-feather="package" style="color: var(--primary); width: 18px;"></i> Package Popularity</h3>
        </div>
        <div class="card-body" style="display: flex; flex-direction: column; gap: 1.5rem;">
            <?php if (!empty($packageStats)): ?>
                <?php foreach ($packageStats as $package): ?>
                    <div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="font-weight: 500; font-size: 0.875rem; color: var(--text-main);"><?= htmlspecialchars($package->package_name ?? 'Unknown') ?></span>
                            <span style="font-size: 0.75rem; color: var(--text-muted);"><?= number_format($package->usage_count ?? 0) ?> sessions</span>
                        </div>
                        <div style="width: 100%; height: 6px; background-color: var(--bg-surface-hover); border-radius: 9999px; overflow: hidden;">
                            <div style="height: 100%; background-color: var(--primary); border-radius: 9999px; width: <?= !empty($packageStats) && ($packageStats[0]->usage_count ?? 0) > 0 ? (($package->usage_count ?? 0) / $packageStats[0]->usage_count) * 100 : 0 ?>%;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 2rem 0; color: var(--text-muted);">
                    <i data-feather="inbox" style="width: 32px; height: 32px; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                    <p style="margin: 0; font-size: 0.875rem;">No package data available</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Activity Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title" style="margin: 0; display: flex; align-items: center; gap: 0.5rem;"><i data-feather="activity" style="color: var(--primary); width: 18px;"></i> Recent Activity</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
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
                        <td style="font-weight: 500; color: var(--text-main);">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i data-feather="calendar" style="width: 14px; color: var(--text-muted);"></i>
                                <?= date('M j, Y', strtotime($daily->date)) ?>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i data-feather="users" style="width: 14px; color: var(--text-muted);"></i>
                                <?= number_format($daily->session_count ?? 0) ?>
                            </div>
                        </td>
                        <td style="font-weight: 600;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--success);">
                                Rp <?= number_format($daily->daily_revenue ?? 0, 0, ',', '.') ?>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted);">
                                <i data-feather="clock" style="width: 14px;"></i>
                                <?= number_format($daily->avg_duration ?? 0) ?>s
                            </div>
                        </td>
                        <td>
                            <?php 
                                $rate = $daily->print_success_rate ?? 0;
                                $bClass = 'badge-danger';
                                if($rate >= 90) $bClass = 'badge-success';
                                elseif($rate >= 70) $bClass = 'badge-warning';
                            ?>
                            <span class="badge <?= $bClass ?>" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                <i data-feather="printer" style="width: 12px;"></i> <?= number_format($rate, 1) ?>%
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                            <i data-feather="inbox" style="width: 32px; height: 32px; margin-bottom: 1rem; opacity: 0.5;"></i>
                            <p style="margin: 0; font-size: 1.125rem; font-weight: 500; color: var(--text-main);">No activity data available</p>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem;">Activity will appear here as users interact.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    @media (max-width: 1024px) {
        .charts-layout { grid-template-columns: 1fr !important; }
    }
</style>

<script>
const canvas = document.getElementById('revenueChart');
if (canvas) {
    const ctx = canvas.getContext('2d');
    const chartData = <?= json_encode($chartData ?? []) ?>;
    
    if (chartData.length > 0) {
        ctx.strokeStyle = '#2563eb';
        ctx.lineWidth = 2;
        ctx.beginPath();
        
        const maxRev = Math.max(...chartData.map(d => d.revenue));
        
        chartData.forEach((point, index) => {
            const x = (index / (chartData.length - 1 || 1)) * canvas.width;
            const y = canvas.height - (maxRev > 0 ? (point.revenue / maxRev * canvas.height * 0.8) : 0) - 20;
            
            if (index === 0) ctx.moveTo(x, y);
            else ctx.lineTo(x, y);
        });
        
        ctx.stroke();
    } else {
        ctx.fillStyle = '#94a3b8';
        ctx.font = '14px Inter';
        ctx.textAlign = 'center';
        ctx.fillText('No data available', canvas.width / 2, canvas.height / 2);
    }
}
</script>