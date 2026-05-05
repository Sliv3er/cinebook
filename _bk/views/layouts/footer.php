    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="site-logo">Ciné<span>Book</span></div>
                    <p>Votre plateforme de réservation de cinéma en ligne. Consultez les films, choisissez vos sièges et réservez en quelques clics.</p>
                </div>
                <div class="footer-col">
                    <h4>Navigation</h4>
                    <a href="<?= BASE_URL ?>/index.php?page=home">Accueil</a>
                    <a href="<?= BASE_URL ?>/index.php?page=movies">Films</a>
                    <?php if (isLoggedIn()): ?>
                        <a href="<?= BASE_URL ?>/index.php?page=profile">Mon Compte</a>
                    <?php endif; ?>
                </div>
                <div class="footer-col">
                    <h4>Informations</h4>
                    <a href="#">Contact</a>
                    <a href="#">Conditions d'utilisation</a>
                    <a href="#">Politique de confidentialité</a>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; <?= date('Y') ?> CinéBook. Tous droits réservés. Projet universitaire PHP/JS.
            </div>
        </div>
    </footer>
    <script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
