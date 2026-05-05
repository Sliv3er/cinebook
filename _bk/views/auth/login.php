<?php $pageTitle = 'Connexion'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — CinéBook</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card">
            <a href="<?= BASE_URL ?>/index.php?page=home" class="site-logo text-center" style="display:block;margin-bottom:32px;">Ciné<span>Book</span></a>
            <h1>Connexion</h1>
            <p class="subtitle">Accédez à votre espace personnel</p>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <?php foreach ($errors as $error): ?>
                        <div><?= e($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/index.php?page=login" id="loginForm">
                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="votre@email.com" value="<?= e($email ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Votre mot de passe" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" id="loginSubmit">Se connecter</button>
            </form>

            <div class="auth-links">
                Pas encore de compte ? <a href="<?= BASE_URL ?>/index.php?page=register">Créer un compte</a>
            </div>
        </div>
    </div>
</body>
</html>
