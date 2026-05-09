<?php $pageTitle = 'Modifier le profil'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-content">
    <div class="container">
        <a href="<?= BASE_URL ?>/index.php?page=profile" class="btn btn-ghost btn-sm mb-32"><i class="fas fa-arrow-left"></i> Retour au profil</a>

        <div style="max-width:520px;">
            <h1 class="mb-24">Modifier le profil</h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <?php foreach ($errors as $error): ?>
                        <div><?= e($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/index.php?page=profile&action=edit">
                <div class="form-group">
                    <label class="form-label" for="name">Nom complet</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= e($user['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" class="form-control" value="<?= e($user['phone'] ?? '') ?>">
                </div>
                <hr style="border-color:var(--border);margin:24px 0;">
                <p class="text-muted mb-16" style="font-size:0.85rem;">Laissez vide pour garder le mot de passe actuel.</p>
                <div class="form-group">
                    <label class="form-label" for="password">Nouveau mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Minimum 6 caractères">
                </div>
                <div class="form-group">
                    <label class="form-label" for="confirm_password">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                </div>
                <div class="flex gap-12">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="<?= BASE_URL ?>/index.php?page=profile" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
