<?php $pageTitle = 'Mon Profil'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-content">
    <div class="container">
        <div class="profile-header">
            <div class="profile-avatar"><?= strtoupper(substr($user['name'], 0, 1)) ?></div>
            <div class="profile-info">
                <h1><?= e($user['name']) ?></h1>
                <p><?= e($user['email']) ?> <?php if ($user['phone']): ?>| <?= e($user['phone']) ?><?php endif; ?></p>
                <p class="text-muted" style="font-size:0.8rem;">Membre depuis le <?= date('d/m/Y', strtotime($user['created_at'])) ?></p>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=profile&action=edit" class="btn btn-secondary btn-sm" style="margin-left:auto;">Modifier le profil</a>
        </div>

        <section class="section">
            <h2 class="section-title mb-24">Mes réservations</h2>

            <?php if (empty($bookings['data'])): ?>
                <div class="empty-state">
                    <h3>Aucune réservation</h3>
                    <p>Vous n'avez pas encore effectué de réservation.</p>
                    <a href="<?= BASE_URL ?>/index.php?page=movies" class="btn btn-primary mt-16">Découvrir les films</a>
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Film</th>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Salle</th>
                                <th>Places</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings['data'] as $b): ?>
                                <tr>
                                    <td>#<?= str_pad($b['id'], 6, '0', STR_PAD_LEFT) ?></td>
                                    <td><strong><?= e($b['movie_title']) ?></strong></td>
                                    <td><?= date('d/m/Y', strtotime($b['show_date'])) ?></td>
                                    <td><?= date('H:i', strtotime($b['show_time'])) ?></td>
                                    <td><?= e($b['hall_name']) ?></td>
                                    <td><?= $b['total_seats'] ?></td>
                                    <td><strong><?= number_format($b['total_price'], 2) ?> DT</strong></td>
                                    <td>
                                        <?php
                                        $statusClass = match($b['status']) {
                                            'confirme' => 'badge-success',
                                            'annule'   => 'badge-danger',
                                            default    => 'badge-warning',
                                        };
                                        ?>
                                        <span class="badge <?= $statusClass ?>"><?= e(BOOKING_STATUSES[$b['status']] ?? $b['status']) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($b['status'] === 'en_attente'): ?>
                                            <a href="<?= BASE_URL ?>/index.php?page=booking&action=cancel&id=<?= $b['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Annuler cette réservation ?')">Annuler</a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($bookings['totalPages'] > 1): ?>
                    <div class="pagination">
                        <?php for ($p = 1; $p <= $bookings['totalPages']; $p++): ?>
                            <?php if ($p == $bookings['page']): ?>
                                <span class="active"><?= $p ?></span>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>/index.php?page=profile&p=<?= $p ?>"><?= $p ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
