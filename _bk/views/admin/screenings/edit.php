<?php $pageTitle = 'Modifier la séance'; ?>
<?php require_once __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <h1>Modifier la séance</h1>
    <a href="<?= BASE_URL ?>/index.php?page=admin-screenings" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Retour</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error"><?php foreach ($errors as $er): ?><div><?= e($er) ?></div><?php endforeach; ?></div>
<?php endif; ?>

<form method="POST" class="admin-form">
    <div class="form-group">
        <label class="form-label" for="movie_id">Film *</label>
        <select id="movie_id" name="movie_id" class="form-control" required>
            <?php foreach ($allMovies as $m): ?>
                <option value="<?= $m['id'] ?>" <?= $screening['movie_id'] == $m['id'] ? 'selected' : '' ?>><?= e($m['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label" for="hall_id">Salle *</label>
        <select id="hall_id" name="hall_id" class="form-control" required>
            <?php foreach ($allHalls as $h): ?>
                <option value="<?= $h['id'] ?>" <?= $screening['hall_id'] == $h['id'] ? 'selected' : '' ?>><?= e($h['name']) ?> (<?= $h['hall_type'] ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label" for="show_date">Date *</label>
            <input type="date" id="show_date" name="show_date" class="form-control" value="<?= e($screening['show_date']) ?>" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="show_time">Heure *</label>
            <input type="time" id="show_time" name="show_time" class="form-control" value="<?= e(substr($screening['show_time'], 0, 5)) ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label" for="price">Prix (DT) *</label>
        <input type="number" id="price" name="price" class="form-control" value="<?= $screening['price'] ?>" min="1" step="0.01" required>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>
