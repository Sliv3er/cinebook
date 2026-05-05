<?php $pageTitle = 'Gestion des salles'; ?>
<?php require_once __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <h1>Gestion des salles</h1>
    <a href="<?= BASE_URL ?>/index.php?page=admin-halls&action=create" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter une salle</a>
</div>

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr><th>Nom</th><th>Type</th><th>Rangées</th><th>Sièges/rangée</th><th>Capacité</th><th>Statut</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php if (empty($halls['data'])): ?>
                <tr><td colspan="7" class="text-center text-muted" style="padding:32px;">Aucune salle.</td></tr>
            <?php else: ?>
                <?php foreach ($halls['data'] as $h): ?>
                    <tr>
                        <td><strong><?= e($h['name']) ?></strong></td>
                        <td><span class="badge badge-<?= strtolower($h['hall_type']) === 'vip' ? 'vip' : (strtolower($h['hall_type']) === 'imax' ? 'imax' : 'info') ?>"><?= e($h['hall_type']) ?></span></td>
                        <td><?= $h['rows_count'] ?></td>
                        <td><?= $h['cols_count'] ?></td>
                        <td><strong><?= $h['rows_count'] * $h['cols_count'] ?></strong> places</td>
                        <td><span class="badge <?= $h['is_active'] ? 'badge-success' : 'badge-danger' ?>"><?= $h['is_active'] ? 'Active' : 'Inactive' ?></span></td>
                        <td>
                            <div class="table-actions">
                                <a href="<?= BASE_URL ?>/index.php?page=admin-halls&action=edit&id=<?= $h['id'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-halls&action=toggle&id=<?= $h['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-power-off"></i></a>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-halls&action=delete&id=<?= $h['id'] ?>" class="btn btn-danger btn-sm" data-confirm="Supprimer cette salle ?"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>
