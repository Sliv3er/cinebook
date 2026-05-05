<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?>Administration — CinéBook</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a href="<?= BASE_URL ?>/index.php?page=admin" class="site-logo">Ciné<span>Book</span> <small style="font-size:0.7rem;color:var(--text-muted);font-weight:400;margin-left:8px;">Administration</small></a>
            <div class="header-actions">
                <a href="<?= BASE_URL ?>/index.php?page=home" class="btn btn-ghost btn-sm"><i class="fas fa-external-link-alt"></i> Voir le site</a>
                <a href="<?= BASE_URL ?>/index.php?page=logout" class="btn btn-ghost btn-sm">Déconnexion</a>
            </div>
        </div>
    </header>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="sidebar-section">
                <div class="sidebar-section-title">Principal</div>
                <a href="<?= BASE_URL ?>/index.php?page=admin" class="sidebar-link <?= ($page ?? '') === 'admin' ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i> Tableau de bord
                </a>
            </div>
            <div class="sidebar-section">
                <div class="sidebar-section-title">Gestion</div>
                <a href="<?= BASE_URL ?>/index.php?page=admin-movies" class="sidebar-link <?= ($page ?? '') === 'admin-movies' ? 'active' : '' ?>">
                    <i class="fas fa-film"></i> Films
                </a>
                <a href="<?= BASE_URL ?>/index.php?page=admin-halls" class="sidebar-link <?= ($page ?? '') === 'admin-halls' ? 'active' : '' ?>">
                    <i class="fas fa-door-open"></i> Salles
                </a>
                <a href="<?= BASE_URL ?>/index.php?page=admin-screenings" class="sidebar-link <?= ($page ?? '') === 'admin-screenings' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i> Séances
                </a>
            </div>
            <div class="sidebar-section">
                <div class="sidebar-section-title">Réservations</div>
                <a href="<?= BASE_URL ?>/index.php?page=admin-bookings" class="sidebar-link <?= ($page ?? '') === 'admin-bookings' ? 'active' : '' ?>">
                    <i class="fas fa-ticket-alt"></i> Réservations
                </a>
                <a href="<?= BASE_URL ?>/index.php?page=admin-users" class="sidebar-link <?= ($page ?? '') === 'admin-users' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Clients
                </a>
            </div>
        </aside>

        <main class="admin-main">
            <?php
            $flashSuccess = getFlash('success');
            $flashError   = getFlash('error');
            ?>
            <?php if ($flashSuccess): ?>
                <div class="alert alert-success"><?= e($flashSuccess) ?></div>
            <?php endif; ?>
            <?php if ($flashError): ?>
                <div class="alert alert-error"><?= e($flashError) ?></div>
            <?php endif; ?>
