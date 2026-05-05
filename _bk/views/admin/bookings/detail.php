<?php $pageTitle = 'Détails réservation'; ?>
<?php require_once __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="admin-page-header">
    <h1>Réservation #<?= str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></h1>
    <a href="<?= BASE_URL ?>/index.php?page=admin-bookings" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Retour</a>
</div>

<div style="max-width:600px;">
    <div class="card" style="padding:32px;">
        <div class="confirm-details">
            <div class="detail-row"><span class="label">Client</span><span><?= e($booking['user_name']) ?> (<?= e($booking['user_email']) ?>)</span></div>
            <div class="detail-row"><span class="label">Film</span><span><?= e($booking['movie_title']) ?></span></div>
            <div class="detail-row"><span class="label">Date</span><span><?= date('d/m/Y', strtotime($booking['show_date'])) ?> à <?= date('H:i', strtotime($booking['show_time'])) ?></span></div>
            <div class="detail-row"><span class="label">Salle</span><span><?= e($booking['hall_name']) ?> (<?= e($booking['hall_type']) ?>)</span></div>
            <div class="detail-row"><span class="label">Sièges</span><span><?php foreach ($seats as $s) echo $s['seat_row'].$s['seat_number'].' '; ?></span></div>
            <div class="detail-row"><span class="label">Places</span><span><?= $booking['total_seats'] ?></span></div>
            <div class="detail-row"><span class="label">Prix unitaire</span><span><?= number_format($booking['unit_price'], 2) ?> DT</span></div>
            <div class="detail-row" style="font-weight:700;font-size:1.1rem;"><span class="label">Total</span><span><?= number_format($booking['total_price'], 2) ?> DT</span></div>
            <div class="detail-row">
                <span class="label">Statut</span>
                <?php $sc = match($booking['status']) { 'confirme'=>'badge-success','annule'=>'badge-danger',default=>'badge-warning' }; ?>
                <span class="badge <?= $sc ?>"><?= e(BOOKING_STATUSES[$booking['status']] ?? $booking['status']) ?></span>
            </div>
            <div class="detail-row"><span class="label">Réservé le</span><span><?= date('d/m/Y H:i', strtotime($booking['booked_at'])) ?></span></div>
        </div>

        <?php if ($booking['status'] === 'en_attente'): ?>
            <div class="flex gap-12 mt-24">
                <a href="<?= BASE_URL ?>/index.php?page=admin-bookings&action=confirm&id=<?= $booking['id'] ?>" class="btn btn-success">Confirmer</a>
                <a href="<?= BASE_URL ?>/index.php?page=admin-bookings&action=cancel&id=<?= $booking['id'] ?>" class="btn btn-danger">Annuler</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>
