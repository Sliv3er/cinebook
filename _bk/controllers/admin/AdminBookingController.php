<?php
/**
 * Admin Booking Controller
 */

require_once __DIR__ . '/../../models/Booking.php';

requireAdmin();

$bookingModel = new Booking();

switch ($action) {
    case 'confirm':
        $bookingModel->updateStatus($id, 'confirme');
        setFlash('success', 'Réservation confirmée.');
        header('Location: ' . BASE_URL . '/index.php?page=admin-bookings');
        exit;
    case 'cancel':
        $bookingModel->updateStatus($id, 'annule');
        setFlash('success', 'Réservation annulée.');
        header('Location: ' . BASE_URL . '/index.php?page=admin-bookings');
        exit;
    case 'detail':
        $booking = $bookingModel->findById($id);
        $seats   = $bookingModel->getSeats($id);
        require_once __DIR__ . '/../../views/admin/bookings/detail.php';
        break;
    default:
        $status   = sanitize($_GET['status'] ?? '');
        $search   = sanitize($_GET['search'] ?? '');
        $page     = max(1, (int)($_GET['p'] ?? 1));
        $bookings = $bookingModel->getAll($status, $search, $page);
        require_once __DIR__ . '/../../views/admin/bookings/index.php';
        break;
}
