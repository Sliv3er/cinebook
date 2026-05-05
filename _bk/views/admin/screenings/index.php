<?php $pageTitle = 'Gestion des séances'; ?>
<?php require_once __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <h1>Gestion des séances</h1>
    <a href="<?= BASE_URL ?>/index.php?page=admin-screenings&action=create" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter une séance</a>
</div>

<div class="table-wrap">
    <div class="table-header">
        <form method="GET" action="<?= BASE_URL ?>/index.php" class="search-box">
            <input type="hidden" name="page" value="admin-screenings">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Rechercher..." value="<?= e($search ?? '') ?>">
        </form>
        <span class="text-muted" style="font-size:0.85rem;"><?= $screenings['total'] ?> séance(s)</span>
    </div>
    <table class="data-table">
        <thead>
            <tr><th>Film</th><th>Salle</th><th>Date</th><th>Heure</th><th>Prix</th><th>Statut</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php if (empty($screenings['data'])): ?>
                <tr><td colspan="7" class="text-center text-muted" style="padding:32px;">Aucune séance.</td></tr>
            <?php else: ?>
                <?php foreach ($screenings['data'] as $s): ?>
                    <tr>
                        <td><strong><?= e($s['movie_title']) ?></strong></td>
                        <td><?= e($s['hall_name']) ?> <span class="badge badge-info" style="font-size:0.65rem;"><?= e($s['hall_type']) ?></span></td>
                        <td><?= date('d/m/Y', strtotime($s['show_date'])) ?></td>
                        <td><?= date('H:i', strtotime($s['show_time'])) ?></td>
                        <td><strong><?= number_format($s['price'], 2) ?> DT</strong></td>
                        <td><span class="badge <?= $s['is_active'] ? 'badge-success' : 'badge-danger' ?>"><?= $s['is_active'] ? 'Active' : 'Inactive' ?></span></td>
                        <td>
                            <div class="table-actions">
                                <a href="<?= BASE_URL ?>/index.php?page=admin-screenings&action=edit&id=<?= $s['id'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-screenings&action=toggle&id=<?= $s['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-power-off"></i></a>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-screenings&action=delete&id=<?= $s['id'] ?>" class="btn btn-danger btn-sm" data-confirm="Supprimer cette séance ?"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($screenings['totalPages'] > 1): ?>
    <div class="pagination">
        <?php for ($p = 1; $p <= $screenings['totalPages']; $p++): ?>
            <?php if ($p == $screenings['page']): ?><span class="active"><?= $p ?></span>
            <?php else: ?><a href="<?= BASE_URL ?>/index.php?page=admin-screenings&p=<?= $p ?>"><?= $p ?></a><?php endif; ?>
        <?php endfor; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>
