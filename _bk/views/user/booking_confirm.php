<?php $pageTitle = 'Réservation confirmée'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-content">
    <div class="container">
        <div class="confirm-card">
            <div class="confirm-icon"><i class="fas fa-check-circle"></i></div>
            <h1>Réservation confirmée</h1>
            <p class="text-muted">Votre réservation a été enregistrée avec succès.</p>

            <div class="confirm-details">
                <div class="detail-row">
                    <span class="label">Numéro de réservation</span>
                    <span>#<?= str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Film</span>
                    <span><?= e($booking['movie_title']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Date et heure</span>
                    <span><?= date('d/m/Y', strtotime($booking['show_date'])) ?> à <?= date('H:i', strtotime($booking['show_time'])) ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Salle</span>
                    <span><?= e($booking['hall_name']) ?> (<?= e($booking['hall_type']) ?>)</span>
                </div>
                <div class="detail-row">
                    <span class="label">Sièges</span>
                    <span>
                        <?php foreach ($seats as $s): ?>
                            <?= $s['seat_row'] . $s['seat_number'] ?>
                        <?php endforeach; ?>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="label">Nombre de places</span>
                    <span><?= $booking['total_seats'] ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Statut</span>
                    <span class="badge badge-warning"><?= e(BOOKING_STATUSES[$booking['status']] ?? $booking['status']) ?></span>
                </div>
                <div class="detail-row" style="font-weight:700;font-size:1.1rem;">
                    <span class="label">Total</span>
                    <span><?= number_format($booking['total_price'], 2) ?> DT</span>
                </div>
            </div>

            <div class="flex gap-12 mt-32" style="justify-content:center;">
                <a href="<?= BASE_URL ?>/index.php?page=profile" class="btn btn-secondary">Mes réservations</a>
                <a href="<?= BASE_URL ?>/index.php?page=movies" class="btn btn-primary">Voir d'autres films</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
