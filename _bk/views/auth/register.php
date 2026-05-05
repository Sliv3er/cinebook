<?php $pageTitle = 'Inscription'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — CinéBook</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card">
            <a href="<?= BASE_URL ?>/index.php?page=home" class="site-logo text-center" style="display:block;margin-bottom:32px;">Ciné<span>Book</span></a>
            <h1>Inscription</h1>
            <p class="subtitle">Créez votre compte CinéBook</p>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <?php foreach ($errors as $error): ?>
                        <div><?= e($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/index.php?page=register" id="registerForm">
                <div class="form-group">
                    <label class="form-label" for="name">Nom complet</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Votre nom complet" value="<?= e($old['name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="votre@email.com" value="<?= e($old['email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="phone">Téléphone (optionnel)</label>
                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="06 XX XX XX XX" value="<?= e($old['phone'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Minimum 6 caractères" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="confirm_password">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Répétez le mot de passe" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" id="registerSubmit">Créer mon compte</button>
            </form>

            <div class="auth-links">
                Déjà inscrit ? <a href="<?= BASE_URL ?>/index.php?page=login">Se connecter</a>
            </div>
        </div>
    </div>
</body>
</html>
