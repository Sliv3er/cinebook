<?php $pageTitle = 'Tableau de bord'; ?>
<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <h1><i class="fas fa-chart-pie" style="margin-right:8px;color:var(--accent);"></i> Tableau de bord</h1>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card accent">
        <div class="stat-label"><i class="fas fa-coins" style="margin-right:6px;"></i> Revenus totaux</div>
        <div class="stat-value"><?= number_format($stats['total_revenue'], 0, ',', ' ') ?></div>
        <div class="stat-sub">DT</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label"><i class="fas fa-ticket-alt" style="margin-right:6px;"></i> Réservations</div>
        <div class="stat-value"><?= $stats['total_bookings'] ?></div>
        <div class="stat-sub"><?= $stats['pending_bookings'] ?> en attente</div>
    </div>
    <div class="stat-card gold">
        <div class="stat-label"><i class="fas fa-film" style="margin-right:6px;"></i> Films</div>
        <div class="stat-value"><?= $stats['total_movies'] ?></div>
    </div>
    <div class="stat-card green">
        <div class="stat-label"><i class="fas fa-users" style="margin-right:6px;"></i> Clients</div>
        <div class="stat-value"><?= $stats['total_users'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="fas fa-door-open" style="margin-right:6px;"></i> Salles</div>
        <div class="stat-value"><?= $stats['total_halls'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="fas fa-calendar-day" style="margin-right:6px;"></i> Séances aujourd'hui</div>
        <div class="stat-value"><?= $stats['today_screenings'] ?></div>
    </div>
</div>

<!-- Charts -->
<div class="charts-grid">
    <div class="chart-card">
        <h3>Revenus (30 derniers jours)</h3>
        <canvas id="revenueChart"></canvas>
    </div>
    <div class="chart-card">
        <h3>Réservations par genre</h3>
        <canvas id="genreChart"></canvas>
    </div>
    <div class="chart-card">
        <h3>Top 5 films</h3>
        <canvas id="topMoviesChart"></canvas>
    </div>
    <div class="chart-card">
        <h3>Taux d'occupation par salle</h3>
        <canvas id="occupancyChart"></canvas>
    </div>
</div>

<!-- Recent Bookings -->
<div class="chart-card">
    <h3>Réservations récentes</h3>
    <?php if (empty($recentBookings)): ?>
        <p class="text-muted">Aucune réservation pour le moment.</p>
    <?php else: ?>
        <div class="recent-list">
            <?php foreach ($recentBookings as $rb): ?>
                <div class="recent-item">
                    <div style="flex:1;">
                        <strong><?= e($rb['user_name']) ?></strong> a réservé <strong><?= e($rb['movie_title']) ?></strong>
                        — <?= $rb['total_seats'] ?> place(s) — <?= number_format($rb['total_price'], 2) ?> DT
                    </div>
                    <div class="time"><?= date('d/m H:i', strtotime($rb['booked_at'])) ?></div>
                    <span class="badge badge-<?= $rb['status'] === 'confirme' ? 'success' : ($rb['status'] === 'annule' ? 'danger' : 'warning') ?>">
                        <?= e(BOOKING_STATUSES[$rb['status']] ?? $rb['status']) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
const chartColors = {
    red: '#e50914', blue: '#3b82f6', gold: '#f5c518', green: '#4ade80',
    purple: '#a855f7', pink: '#ec4899', cyan: '#22d3ee', orange: '#f97316'
};
const palette = [chartColors.red, chartColors.blue, chartColors.gold, chartColors.green, chartColors.purple, chartColors.pink, chartColors.cyan, chartColors.orange];
Chart.defaults.color = '#7a7a8e';
Chart.defaults.borderColor = 'rgba(255,255,255,0.06)';

// Revenue Chart
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($revenueByDay, 'date')) ?>,
        datasets: [{
            label: 'Revenus (DT)',
            data: <?= json_encode(array_map(fn($r) => (float)$r['revenue'], $revenueByDay)) ?>,
            borderColor: chartColors.red,
            backgroundColor: 'rgba(229,9,20,0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 3,
            pointBackgroundColor: chartColors.red,
            pointBorderColor: '#fff',
            pointBorderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.04)' } },
            x: { grid: { display: false } }
        }
    }
});

// Genre Chart
new Chart(document.getElementById('genreChart'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_column($bookingsByGenre, 'genre')) ?>,
        datasets: [{ data: <?= json_encode(array_map(fn($r) => (int)$r['count'], $bookingsByGenre)) ?>, backgroundColor: palette, borderWidth: 0 }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { padding: 16 } } }, cutout: '65%' }
});

// Top Movies Chart
new Chart(document.getElementById('topMoviesChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($topMovies, 'title')) ?>,
        datasets: [{
            label: 'Réservations',
            data: <?= json_encode(array_map(fn($r) => (int)$r['booking_count'], $topMovies)) ?>,
            backgroundColor: palette,
            borderRadius: 6,
            borderSkipped: false
        }]
    },
    options: {
        indexAxis: 'y', responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.04)' } },
            y: { grid: { display: false } }
        }
    }
});

// Occupancy Chart
new Chart(document.getElementById('occupancyChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($occupancyRates, 'name')) ?>,
        datasets: [{
            label: 'Sièges réservés',
            data: <?= json_encode(array_map(fn($r) => (int)$r['booked_seats'], $occupancyRates)) ?>,
            backgroundColor: [chartColors.blue, chartColors.gold, chartColors.purple],
            borderRadius: 6,
            borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.04)' } },
            x: { grid: { display: false } }
        }
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
