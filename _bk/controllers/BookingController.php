<?php
/**
 * Booking Controller (Public)
 */

require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Screening.php';

$bookingModel   = new Booking();
$screeningModel = new Screening();

switch ($action) {
    case 'seats':
        showSeatSelection($screeningModel);
        break;
    case 'confirm':
        confirmBooking($bookingModel, $screeningModel);
        break;
    case 'cancel':
        cancelBooking($bookingModel);
        break;
    case 'success':
        showSuccess($bookingModel);
        break;
    default:
        header('Location: ' . BASE_URL . '/index.php?page=movies');
        exit;
}

function showSeatSelection(Screening $screeningModel): void
{
    requireLogin();
    global $id;

    $screening = $screeningModel->findById($id);
    if (!$screening) {
        setFlash('error', 'Séance introuvable.');
        header('Location: ' . BASE_URL . '/index.php?page=movies');
        exit;
    }

    $bookedSeats = $screeningModel->getBookedSeats($id);

    require_once __DIR__ . '/../views/user/seat_selection.php';
}

function confirmBooking(Booking $bookingModel, Screening $screeningModel): void
{
    requireLogin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . '/index.php?page=movies');
        exit;
    }

    $screeningId = (int)($_POST['screening_id'] ?? 0);
    $seatsJson   = $_POST['seats'] ?? '[]';
    $seats       = json_decode($seatsJson, true);

    if (empty($seats) || !$screeningId) {
        setFlash('error', 'Veuillez sélectionner au moins un siège.');
        header('Location: ' . BASE_URL . '/index.php?page=booking&action=seats&id=' . $screeningId);
        exit;
    }

    $screening = $screeningModel->findById($screeningId);
    if (!$screening) {
        setFlash('error', 'Séance introuvable.');
        header('Location: ' . BASE_URL . '/index.php?page=movies');
        exit;
    }

    // Verify seats are not already booked
    $bookedSeats = $screeningModel->getBookedSeats($screeningId);
    $bookedMap = [];
    foreach ($bookedSeats as $bs) {
        $bookedMap[$bs['seat_row'] . $bs['seat_number']] = true;
    }

    foreach ($seats as $seat) {
        $key = $seat['row'] . $seat['number'];
        if (isset($bookedMap[$key])) {
            setFlash('error', 'Un ou plusieurs sièges sélectionnés ne sont plus disponibles.');
            header('Location: ' . BASE_URL . '/index.php?page=booking&action=seats&id=' . $screeningId);
            exit;
        }
    }

    $totalPrice = count($seats) * $screening['price'];

    try {
        $bookingId = $bookingModel->create(
            currentUserId(),
            $screeningId,
            $seats,
            $totalPrice
        );

        setFlash('success', 'Réservation effectuée avec succès !');
        header('Location: ' . BASE_URL . '/index.php?page=booking&action=success&id=' . $bookingId);
        exit;

    } catch (Exception $e) {
        setFlash('error', 'Erreur lors de la réservation. Veuillez réessayer.');
        header('Location: ' . BASE_URL . '/index.php?page=booking&action=seats&id=' . $screeningId);
        exit;
    }
}

function cancelBooking(Booking $bookingModel): void
{
    requireLogin();
    global $id;

    if ($bookingModel->cancel($id, currentUserId())) {
        setFlash('success', 'Réservation annulée avec succès.');
    } else {
        setFlash('error', 'Impossible d\'annuler cette réservation.');
    }

    header('Location: ' . BASE_URL . '/index.php?page=profile');
    exit;
}

function showSuccess(Booking $bookingModel): void
{
    requireLogin();
    global $id;

    $booking = $bookingModel->findById($id);
    if (!$booking || $booking['user_id'] != currentUserId()) {
        header('Location: ' . BASE_URL . '/index.php?page=home');
        exit;
    }

    $seats = $bookingModel->getSeats($id);

    require_once __DIR__ . '/../views/user/booking_confirm.php';
}
