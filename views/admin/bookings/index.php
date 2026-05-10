<?php $pageTitle = 'Gestion des réservations'; ?>
<?php require_once __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <h1>Gestion des réservations</h1>
</div>

<div class="filter-tabs mb-24">
    <a href="<?= BASE_URL ?>/index.php?page=admin-bookings" class="filter-tab <?= empty($status) ? 'active' : '' ?>">Toutes</a>
    <a href="<?= BASE_URL ?>/index.php?page=admin-bookings&status=en_attente" class="filter-tab <?= $status === 'en_attente' ? 'active' : '' ?>">En attente</a>
    <a href="<?= BASE_URL ?>/index.php?page=admin-bookings&status=confirme" class="filter-tab <?= $status === 'confirme' ? 'active' : '' ?>">Confirmées</a>
    <a href="<?= BASE_URL ?>/index.php?page=admin-bookings&status=annule" class="filter-tab <?= $status === 'annule' ? 'active' : '' ?>">Annulées</a>
</div>

<div class="table-wrap">
    <div class="table-header">
        <form method="GET" action="<?= BASE_URL ?>/index.php" class="search-box">
            <input type="hidden" name="page" value="admin-bookings">
            <input type="hidden" name="status" value="<?= e($status) ?>">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Client ou film..." value="<?= e($search) ?>">
        </form>
        <span class="text-muted" style="font-size:0.85rem;"><?= $bookings['total'] ?> réservation(s)</span>
    </div>
    <table class="data-table">
        <thead>
            <tr><th>N°</th><th>Client</th><th>Film</th><th>Date</th><th>Salle</th><th>Places</th><th>Total</th><th>Statut</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php if (empty($bookings['data'])): ?>
                <tr><td colspan="9" class="text-center text-muted" style="padding:32px;">Aucune réservation.</td></tr>
            <?php else: ?>
                <?php foreach ($bookings['data'] as $b): ?>
                    <tr>
                        <td>#<?= str_pad($b['id'], 6, '0', STR_PAD_LEFT) ?></td>
                        <td><strong><?= e($b['user_name']) ?></strong><br><small class="text-muted"><?= e($b['user_email']) ?></small></td>
                        <td><?= e($b['movie_title']) ?></td>
                        <td><?= date('d/m/Y', strtotime($b['show_date'])) ?><br><small><?= date('H:i', strtotime($b['show_time'])) ?></small></td>
                        <td><?= e($b['hall_name']) ?></td>
                        <td><?= $b['total_seats'] ?></td>
                        <td><strong><?= number_format($b['total_price'], 2) ?> DT</strong></td>
                        <td>
                            <?php $sc = match($b['status']) { 'confirme'=>'badge-success','annule'=>'badge-danger',default=>'badge-warning' }; ?>
                            <span class="badge <?= $sc ?>"><?= e(BOOKING_STATUSES[$b['status']] ?? $b['status']) ?></span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <?php if ($b['status'] === 'en_attente'): ?>
                                    <a href="<?= BASE_URL ?>/index.php?page=admin-bookings&action=confirm&id=<?= $b['id'] ?>" class="btn btn-success btn-sm" title="Confirmer"><i class="fas fa-check"></i></a>
                                    <a href="<?= BASE_URL ?>/index.php?page=admin-bookings&action=cancel&id=<?= $b['id'] ?>" class="btn btn-danger btn-sm" title="Annuler"><i class="fas fa-times"></i></a>
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-bookings&action=detail&id=<?= $b['id'] ?>" class="btn btn-secondary btn-sm" title="Détails"><i class="fas fa-eye"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($bookings['totalPages'] > 1): ?>
    <div class="pagination">
        <?php for ($p = 1; $p <= $bookings['totalPages']; $p++): ?>
            <?php if ($p == $bookings['page']): ?><span class="active"><?= $p ?></span>
            <?php else: ?><a href="<?= BASE_URL ?>/index.php?page=admin-bookings&status=<?= e($status) ?>&p=<?= $p ?>"><?= $p ?></a><?php endif; ?>
        <?php endfor; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>
