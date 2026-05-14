<?php $pageTitle = 'Mes Favoris'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-content">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title"><i class="fas fa-heart" style="color:#e74c3c;margin-right:8px;"></i>Mes Films Préférés</h1>
        </div>

        <?php if (empty($favorites)): ?>
            <div class="empty-state">
                <i class="far fa-heart" style="font-size:3rem;color:#ccc;margin-bottom:16px;display:block;"></i>
                <h3>Aucun film en favori</h3>
                <p>Cliquez sur l'étoile <i class="fas fa-star" style="color:#f1c40f;"></i> sur un film pour l'ajouter à vos favoris.</p>
                <a href="<?= BASE_URL ?>/index.php?page=movies" class="btn btn-primary" style="margin-top:16px;">Découvrir les films</a>
            </div>
        <?php else: ?>
            <p style="color:#888;margin-bottom:20px;"><?= count($favorites) ?> film<?= count($favorites) > 1 ? 's' : '' ?> en favori</p>
            <div class="movie-grid">
                <?php foreach ($favorites as $movie): ?>
                    <div class="movie-card-wrapper" id="fav-wrapper-<?= $movie['id'] ?>">
                        <a href="<?= BASE_URL ?>/index.php?page=movie&action=detail&id=<?= $movie['id'] ?>" class="movie-card">
                            <div class="poster-wrap">
                                <?php if ($movie['poster']): ?>
                                    <img src="<?= posterUrl($movie['poster']) ?>" alt="<?= e($movie['title']) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="no-poster"><i class="fas fa-film" style="font-size:2rem;opacity:0.3;"></i></div>
                                <?php endif; ?>
                                <div class="poster-overlay">
                                    <span class="btn btn-primary btn-sm">Voir les séances</span>
                                </div>
                            </div>
                            <div class="movie-info">
                                <div class="movie-title"><?= e($movie['title']) ?></div>
                                <div class="movie-meta">
                                    <span><?= e($movie['genre']) ?></span>
                                    <span><?= $movie['duration_min'] ?> min</span>
                                </div>
                            </div>
                        </a>
                        <button class="fav-btn is-fav" onclick="toggleFav(<?= $movie['id'] ?>, this)" title="Retirer des favoris">
                            <i class="fas fa-star"></i>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleFav(movieId, btn) {
    fetch('<?= BASE_URL ?>/index.php?page=favorites&action=toggle&id=' + movieId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && !data.isFavorite) {
            const wrapper = document.getElementById('fav-wrapper-' + movieId);
            if (wrapper) {
                wrapper.style.transition = 'opacity 0.3s, transform 0.3s';
                wrapper.style.opacity = '0';
                wrapper.style.transform = 'scale(0.8)';
                setTimeout(() => wrapper.remove(), 300);
            }
            // Update nav counter
            const badge = document.getElementById('fav-count');
            if (badge && data.count > 0) badge.textContent = data.count;
            else if (badge) badge.style.display = 'none';
        }
    });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
