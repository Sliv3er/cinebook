<?php
/**
 * Screening Controller (Public - for AJAX)
 */

require_once __DIR__ . '/../models/Screening.php';

$screeningModel = new Screening();

// AJAX endpoint: get booked seats for a screening
if ($action === 'booked-seats' && $id) {
    header('Content-Type: application/json');
    $bookedSeats = $screeningModel->getBookedSeats($id);
    echo json_encode($bookedSeats);
    exit;
}

// Default: redirect
header('Location: ' . BASE_URL . '/index.php?page=movies');
exit;
