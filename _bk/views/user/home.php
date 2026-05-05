<?php $pageTitle = 'Accueil'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<section class="hero">
    <div class="hero-bg"></div>
    <div class="container">
        <div class="hero-content">
            <h1>Réservez vos <span>places</span> de cinéma</h1>
            <p>Découvrez les derniers films à l'affiche, choisissez votre séance et vos sièges en quelques clics. Une expérience de réservation simple et rapide.</p>
            <div class="flex gap-12">
                <a href="<?= BASE_URL ?>/index.php?page=movies" class="btn btn-primary">Voir les films</a>
                <?php if (!isLoggedIn()): ?>
                    <a href="<?= BASE_URL ?>/index.php?page=register" class="btn btn-secondary">Créer un compte</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <?php if (!empty($featuredMovies)): ?>
    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Films à l'affiche</h2>
            <a href="<?= BASE_URL ?>/index.php?page=movies" class="btn btn-ghost btn-sm">Voir tout <i class="fas fa-arrow-right" style="margin-left:4px;"></i></a>
        </div>
        <div class="movie-grid">
            <?php foreach ($featuredMovies as $movie): ?>
                <a href="<?= BASE_URL ?>/index.php?page=movie&action=detail&id=<?= $movie['id'] ?>" class="movie-card" id="movie-<?= $movie['id'] ?>">
                    <div class="poster-wrap">
                        <?php if ($movie['poster']): ?>
                            <img src="<?= posterUrl($movie['poster']) ?>" alt="<?= e($movie['title']) ?>">
                        <?php else: ?>
                            <div class="no-poster"><i class="fas fa-film" style="font-size:2rem;opacity:0.3;"></i></div>
                        <?php endif; ?>
                        <div class="poster-overlay">
                            <span class="btn btn-primary btn-sm">Réserver</span>
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
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($nowShowing)): ?>
    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Séances disponibles</h2>
        </div>
        <div class="movie-grid">
            <?php foreach (array_slice($nowShowing, 0, 6) as $movie): ?>
                <a href="<?= BASE_URL ?>/index.php?page=movie&action=detail&id=<?= $movie['id'] ?>" class="movie-card">
                    <div class="poster-wrap">
                        <?php if ($movie['poster']): ?>
                            <img src="<?= posterUrl($movie['poster']) ?>" alt="<?= e($movie['title']) ?>">
                        <?php else: ?>
                            <div class="no-poster"><i class="fas fa-film" style="font-size:2rem;opacity:0.3;"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="movie-info">
                        <div class="movie-title"><?= e($movie['title']) ?></div>
                        <div class="movie-meta">
                            <span><?= e($movie['genre']) ?></span>
                            <span><?= e(RATINGS[$movie['rating']] ?? $movie['rating']) ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
