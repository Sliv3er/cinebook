<?php $pageTitle = e($movie['title']); ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-content">
    <div class="container">
        <a href="<?= BASE_URL ?>/index.php?page=movies" class="btn btn-ghost btn-sm mb-32"><i class="fas fa-arrow-left"></i> Retour aux films</a>

        <div class="movie-detail-header">
            <div class="movie-detail-poster">
                <?php if ($movie['poster']): ?>
                    <img src="<?= posterUrl($movie['poster']) ?>" alt="<?= e($movie['title']) ?>">
                <?php else: ?>
                    <div class="no-poster" style="min-height:400px;"><i class="fas fa-film" style="font-size:3rem;opacity:0.3;"></i></div>
                <?php endif; ?>
            </div>

            <div class="movie-detail-info">
                <div class="movie-detail-tags">
                    <span class="badge badge-info"><?= e($movie['genre']) ?></span>
                    <span class="badge badge-warning"><?= e(RATINGS[$movie['rating']] ?? $movie['rating']) ?></span>
                </div>
                <h1><?= e($movie['title']) ?></h1>

                <div class="detail-meta">
                    <div class="detail-meta-item">
                        <div class="label">Durée</div>
                        <div class="value"><?= $movie['duration_min'] ?> min</div>
                    </div>
                    <div class="detail-meta-item">
                        <div class="label">Date de sortie</div>
                        <div class="value"><?= $movie['release_date'] ? date('d/m/Y', strtotime($movie['release_date'])) : 'N/A' ?></div>
                    </div>
                    <div class="detail-meta-item">
                        <div class="label">Classification</div>
                        <div class="value"><?= e(RATINGS[$movie['rating']] ?? $movie['rating']) ?></div>
                    </div>
                </div>

                <?php if ($movie['description']): ?>
                    <div class="movie-detail-description"><?= nl2br(e($movie['description'])) ?></div>
                <?php endif; ?>

                <?php if ($movie['trailer_url']): ?>
                    <div style="margin-bottom:32px;">
                        <h3 style="margin-bottom:12px;">Bande-annonce</h3>
                        <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:var(--radius-lg);background:var(--bg-elevated);">
                            <iframe src="<?= e($movie['trailer_url']) ?>" style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;" allowfullscreen></iframe>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Screenings -->
        <section class="section">
            <h2 class="section-title mb-24">Séances disponibles</h2>

            <?php if (empty($screenings)): ?>
                <div class="empty-state">
                    <h3>Aucune séance disponible</h3>
                    <p>Il n'y a pas de séances programmées pour ce film actuellement.</p>
                </div>
            <?php else: ?>
                <div class="screening-list">
                    <?php
                    $currentDate = '';
                    foreach ($screenings as $s):
                        $dateFormatted = date('l d F Y', strtotime($s['show_date']));
                        if ($dateFormatted !== $currentDate):
                            $currentDate = $dateFormatted;
                    ?>
                        <h3 style="margin-top:16px;margin-bottom:8px;color:var(--text-secondary);font-size:0.9rem;font-weight:600;text-transform:capitalize;"><?= $dateFormatted ?></h3>
                    <?php endif; ?>

                    <div class="screening-card" id="screening-<?= $s['id'] ?>">
                        <div class="screening-info">
                            <div class="screening-time"><?= date('H:i', strtotime($s['show_time'])) ?></div>
                            <div>
                                <div class="screening-hall"><?= e($s['hall_name']) ?></div>
                                <span class="badge badge-<?= strtolower($s['hall_type']) === 'vip' ? 'vip' : (strtolower($s['hall_type']) === 'imax' ? 'imax' : 'info') ?>"><?= e($s['hall_type']) ?></span>
                            </div>
                        </div>
                        <div class="flex gap-16" style="align-items:center;">
                            <div class="screening-price"><?= number_format($s['price'], 2) ?> <small>DT</small></div>
                            <?php if (isLoggedIn()): ?>
                                <a href="<?= BASE_URL ?>/index.php?page=booking&action=seats&id=<?= $s['id'] ?>" class="btn btn-primary btn-sm">Réserver</a>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>/index.php?page=login" class="btn btn-secondary btn-sm">Connectez-vous</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
