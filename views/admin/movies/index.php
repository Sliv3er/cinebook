<?php $pageTitle = 'Gestion des films'; ?>
<?php require_once __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <h1>Gestion des films</h1>
    <a href="<?= BASE_URL ?>/index.php?page=admin-movies&action=create" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter un film</a>
</div>

<div class="table-wrap">
    <div class="table-header">
        <form method="GET" action="<?= BASE_URL ?>/index.php" class="search-box">
            <input type="hidden" name="page" value="admin-movies">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Rechercher..." value="<?= e($search ?? '') ?>">
        </form>
        <span class="text-muted" style="font-size:0.85rem;"><?= $movies['total'] ?> film(s)</span>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Affiche</th>
                <th>Titre</th>
                <th>Genre</th>
                <th>Durée</th>
                <th>Sortie</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($movies['data'])): ?>
                <tr><td colspan="7" class="text-center text-muted" style="padding:32px;">Aucun film trouvé.</td></tr>
            <?php else: ?>
                <?php foreach ($movies['data'] as $m): ?>
                    <tr id="movie-row-<?= $m['id'] ?>">
                        <td>
                            <div style="width:48px;height:72px;border-radius:6px;overflow:hidden;background:var(--bg-elevated);">
                                <?php if ($m['poster']): ?>
                                    <img src="<?= posterUrl($m['poster']) ?>" alt="" style="width:100%;height:100%;object-fit:cover;">
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><strong><?= e($m['title']) ?></strong></td>
                        <td><?= e($m['genre']) ?></td>
                        <td><?= $m['duration_min'] ?> min</td>
                        <td><?= $m['release_date'] ? date('d/m/Y', strtotime($m['release_date'])) : '-' ?></td>
                        <td>
                            <span class="badge <?= $m['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                                <?= $m['is_active'] ? 'Actif' : 'Inactif' ?>
                            </span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="<?= BASE_URL ?>/index.php?page=admin-movies&action=edit&id=<?= $m['id'] ?>" class="btn btn-secondary btn-sm" title="Modifier"><i class="fas fa-edit"></i></a>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-movies&action=toggle&id=<?= $m['id'] ?>" class="btn btn-warning btn-sm" title="Activer/Désactiver"><i class="fas fa-power-off"></i></a>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-movies&action=delete&id=<?= $m['id'] ?>" class="btn btn-danger btn-sm" data-confirm="Supprimer ce film ?" title="Supprimer"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($movies['totalPages'] > 1): ?>
    <div class="pagination">
        <?php for ($p = 1; $p <= $movies['totalPages']; $p++): ?>
            <?php if ($p == $movies['page']): ?>
                <span class="active"><?= $p ?></span>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/index.php?page=admin-movies&p=<?= $p ?>&search=<?= urlencode($search ?? '') ?>"><?= $p ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>
