<?php $pageTitle = 'Ajouter un film'; ?>
<?php require_once __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <h1>Ajouter un film</h1>
    <a href="<?= BASE_URL ?>/index.php?page=admin-movies" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Retour</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $error): ?>
            <div><?= e($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="admin-form">
    <div class="form-group">
        <label class="form-label" for="title">Titre *</label>
        <input type="text" id="title" name="title" class="form-control" value="<?= e($old['title'] ?? '') ?>" required>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label" for="genre">Genre *</label>
            <select id="genre" name="genre" class="form-control" required>
                <option value="">Sélectionner</option>
                <?php foreach (GENRES as $g): ?>
                    <option value="<?= $g ?>" <?= ($old['genre'] ?? '') === $g ? 'selected' : '' ?>><?= $g ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label" for="duration_min">Durée (min) *</label>
            <input type="number" id="duration_min" name="duration_min" class="form-control" value="<?= e($old['duration_min'] ?? '90') ?>" min="1" max="500" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label" for="release_date">Date de sortie</label>
            <input type="date" id="release_date" name="release_date" class="form-control" value="<?= e($old['release_date'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label class="form-label" for="rating">Classification</label>
            <select id="rating" name="rating" class="form-control">
                <?php foreach (RATINGS as $key => $label): ?>
                    <option value="<?= $key ?>" <?= ($old['rating'] ?? 'TP') === $key ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label" for="description">Description</label>
        <textarea id="description" name="description" class="form-control" rows="4"><?= e($old['description'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
        <label class="form-label" for="trailer_url">URL de la bande-annonce (YouTube embed)</label>
        <input type="url" id="trailer_url" name="trailer_url" class="form-control" placeholder="https://www.youtube.com/embed/..." value="<?= e($old['trailer_url'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label class="form-label" for="poster">Affiche du film</label>
        <input type="file" id="poster" name="poster" class="form-control" accept="image/*" data-preview="posterPreview">
        <div class="poster-preview" id="posterPreview"></div>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer le film</button>
</form>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>
