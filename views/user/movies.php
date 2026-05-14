<?php $pageTitle = 'Films'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-content">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">Tous les films</h1>
        </div>

        <!-- Filters -->
        <div class="flex-between mb-24" style="flex-wrap:wrap;gap:16px;">
            <div class="filter-tabs">
                <a href="<?= BASE_URL ?>/index.php?page=movies" class="filter-tab <?= empty($genre) ? 'active' : '' ?>">Tous</a>
                <?php foreach ($genres as $g): ?>
                    <a href="<?= BASE_URL ?>/index.php?page=movies&genre=<?= urlencode($g) ?>" class="filter-tab <?= $genre === $g ? 'active' : '' ?>"><?= e($g) ?></a>
                <?php endforeach; ?>
            </div>
            <form method="GET" action="<?= BASE_URL ?>/index.php" class="search-box">
                <input type="hidden" name="page" value="movies">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Rechercher un film..." value="<?= e($search) ?>">
            </form>
        </div>

        <?php if (empty($movies['data'])): ?>
            <div class="empty-state">
                <h3>Aucun film trouvé</h3>
                <p>Essayez une autre recherche ou consultez tous les films.</p>
            </div>
        <?php else: ?>
            <div class="movie-grid">
                <?php foreach ($movies['data'] as $movie): ?>
                    <div class="movie-card-wrapper">
                        <a href="<?= BASE_URL ?>/index.php?page=movie&action=detail&id=<?= $movie['id'] ?>" class="movie-card" id="movie-card-<?= $movie['id'] ?>">
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
                        <?php if (isLoggedIn()): ?>
                            <button class="fav-btn <?= in_array($movie['id'], $__favIds ?? []) ? 'is-fav' : '' ?>" 
                                    onclick="toggleFav(<?= $movie['id'] ?>, this)" 
                                    title="<?= in_array($movie['id'], $__favIds ?? []) ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>">
                                <i class="<?= in_array($movie['id'], $__favIds ?? []) ? 'fas' : 'far' ?> fa-star"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($movies['totalPages'] > 1): ?>
                <div class="pagination">
                    <?php for ($p = 1; $p <= $movies['totalPages']; $p++): ?>
                        <?php $params = array_merge($_GET, ['p' => $p]); ?>
                        <?php if ($p == $movies['page']): ?>
                            <span class="active"><?= $p ?></span>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/index.php?<?= http_build_query($params) ?>"><?= $p ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
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
        if (data.success) {
            const icon = btn.querySelector('i');
            if (data.isFavorite) {
                btn.classList.add('is-fav');
                icon.classList.replace('far', 'fas');
                btn.title = 'Retirer des favoris';
            } else {
                btn.classList.remove('is-fav');
                icon.classList.replace('fas', 'far');
                btn.title = 'Ajouter aux favoris';
            }
            // Update nav badge
            let badge = document.getElementById('fav-count');
            if (badge) {
                badge.textContent = data.count;
                badge.style.display = data.count > 0 ? '' : 'none';
            }
            // Quick animation
            btn.style.transform = 'scale(1.3)';
            setTimeout(() => btn.style.transform = '', 200);
        }
    });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
