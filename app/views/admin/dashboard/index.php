<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 2rem;
    }
    .card {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
    }
    .card-title {
        font-size: 1rem;
        color: #6c757d;
        margin: 0 0 10px 0;
        text-transform: uppercase;
    }
    .card-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #343a40;
    }
    .section-title {
        font-size: 1.5rem;
        margin-top: 2rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 0.5rem;
    }
</style>

<h1>Dashboard</h1>

<div class="dashboard-grid">
    <div class="card">
        <h3 class="card-title">Revenue Today</h3>
        <p class="card-value">Rp <?= number_format($summary->revenue_today, 0, ',', '.') ?></p>
    </div>
    <div class="card">
        <h3 class="card-title">Transactions Today</h3>
        <p class="card-value"><?= $summary->transactions_today ?></p>
    </div>
    <div class="card">
        <h3 class="card-title">Total Revenue</h3>
        <p class="card-value">Rp <?= number_format($summary->total_revenue, 0, ',', '.') ?></p>
    </div>
    <div class="card">
        <h3 class="card-title">Total Transactions</h3>
        <p class="card-value"><?= $summary->total_transactions ?></p>
    </div>
</div>

<h2 class="section-title">Most Popular Packages</h2>

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
            <td><?= htmlspecialchars($package->name) ?></td>
            <td><?= $package->transaction_count ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>