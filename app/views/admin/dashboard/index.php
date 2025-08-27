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