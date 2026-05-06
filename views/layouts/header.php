<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CinéBook - Réservez vos places de cinéma en ligne. Consultez les films à l'affiche et choisissez vos sièges.">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?>CinéBook</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a href="<?= BASE_URL ?>/index.php?page=home" class="site-logo">Ciné<span>Book</span></a>
            <nav class="site-nav">
                <a href="<?= BASE_URL ?>/index.php?page=home" class="<?= ($page ?? '') === 'home' ? 'active' : '' ?>">Accueil</a>
                <a href="<?= BASE_URL ?>/index.php?page=movies" class="<?= ($page ?? '') === 'movies' ? 'active' : '' ?>">Films</a>
            </nav>
            <div class="header-actions">
                <?php if (isLoggedIn()): ?>
                    <a href="<?= BASE_URL ?>/index.php?page=profile" class="btn btn-ghost btn-sm">
                        <i class="fas fa-user"></i> <?= e(currentUserName()) ?>
                    </a>
                    <?php if (isAdmin()): ?>
                        <a href="<?= BASE_URL ?>/index.php?page=admin" class="btn btn-secondary btn-sm">Administration</a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/index.php?page=logout" class="btn btn-ghost btn-sm">Déconnexion</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/index.php?page=login" class="btn btn-ghost btn-sm">Connexion</a>
                    <a href="<?= BASE_URL ?>/index.php?page=register" class="btn btn-primary btn-sm">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <?php
    $flashSuccess = getFlash('success');
    $flashError   = getFlash('error');
    ?>
    <?php if ($flashSuccess): ?>
        <div class="container" style="padding-top: calc(var(--header-height) + 16px);">
            <div class="alert alert-success"><?= e($flashSuccess) ?></div>
        </div>
    <?php endif; ?>
    <?php if ($flashError): ?>
        <div class="container" style="padding-top: calc(var(--header-height) + 16px);">
            <div class="alert alert-error"><?= e($flashError) ?></div>
        </div>
    <?php endif; ?>
