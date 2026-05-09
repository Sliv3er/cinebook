<?php $pageTitle = 'Ajouter une salle'; ?>
<?php require_once __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <h1>Ajouter une salle</h1>
    <a href="<?= BASE_URL ?>/index.php?page=admin-halls" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Retour</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error"><?php foreach ($errors as $e): ?><div><?= e($e) ?></div><?php endforeach; ?></div>
<?php endif; ?>

<form method="POST" class="admin-form">
    <div class="form-group">
        <label class="form-label" for="name">Nom de la salle *</label>
        <input type="text" id="name" name="name" class="form-control" value="<?= e($old['name'] ?? '') ?>" required>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label" for="rows_count">Nombre de rangées *</label>
            <input type="number" id="rows_count" name="rows_count" class="form-control" value="<?= $old['rows_count'] ?? 8 ?>" min="1" max="30" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="cols_count">Sièges par rangée *</label>
            <input type="number" id="cols_count" name="cols_count" class="form-control" value="<?= $old['cols_count'] ?? 12 ?>" min="1" max="30" required>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label" for="hall_type">Type de salle</label>
        <select id="hall_type" name="hall_type" class="form-control">
            <?php foreach (HALL_TYPES as $t): ?>
                <option value="<?= $t ?>" <?= ($old['hall_type'] ?? 'Standard') === $t ? 'selected' : '' ?>><?= $t ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
</form>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>
