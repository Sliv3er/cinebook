<?php $pageTitle = 'Gestion des clients'; ?>
<?php require_once __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <h1>Gestion des clients</h1>
</div>

<div class="table-wrap">
    <div class="table-header">
        <form method="GET" action="<?= BASE_URL ?>/index.php" class="search-box">
            <input type="hidden" name="page" value="admin-users">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Nom ou email..." value="<?= e($search ?? '') ?>">
        </form>
        <span class="text-muted" style="font-size:0.85rem;"><?= $users['total'] ?> client(s)</span>
    </div>
    <table class="data-table">
        <thead>
            <tr><th>Nom</th><th>Email</th><th>Téléphone</th><th>Inscrit le</th><th>Statut</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php if (empty($users['data'])): ?>
                <tr><td colspan="6" class="text-center text-muted" style="padding:32px;">Aucun client.</td></tr>
            <?php else: ?>
                <?php foreach ($users['data'] as $u): ?>
                    <tr>
                        <td><strong><?= e($u['name']) ?></strong></td>
                        <td><?= e($u['email']) ?></td>
                        <td><?= e($u['phone'] ?? '-') ?></td>
                        <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                        <td><span class="badge <?= $u['is_active'] ? 'badge-success' : 'badge-danger' ?>"><?= $u['is_active'] ? 'Actif' : 'Bloqué' ?></span></td>
                        <td>
                            <a href="<?= BASE_URL ?>/index.php?page=admin-users&action=toggle&id=<?= $u['id'] ?>" class="btn <?= $u['is_active'] ? 'btn-warning' : 'btn-success' ?> btn-sm">
                                <?= $u['is_active'] ? 'Bloquer' : 'Activer' ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($users['totalPages'] > 1): ?>
    <div class="pagination">
        <?php for ($p = 1; $p <= $users['totalPages']; $p++): ?>
            <?php if ($p == $users['page']): ?><span class="active"><?= $p ?></span>
            <?php else: ?><a href="<?= BASE_URL ?>/index.php?page=admin-users&p=<?= $p ?>"><?= $p ?></a><?php endif; ?>
        <?php endfor; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>
