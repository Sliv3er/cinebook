<?php
/**
 * Admin Dashboard Controller
 */

require_once __DIR__ . '/../../models/Movie.php';
require_once __DIR__ . '/../../models/Hall.php';
require_once __DIR__ . '/../../models/Screening.php';
require_once __DIR__ . '/../../models/Booking.php';
require_once __DIR__ . '/../../models/User.php';

requireAdmin();

$movieModel     = new Movie();
$hallModel      = new Hall();
$screeningModel = new Screening();
$bookingModel   = new Booking();
$userModel      = new User();

// Gather statistics
$stats = [
    'total_movies'     => $movieModel->countAll(),
    'total_halls'      => $hallModel->countAll(),
    'total_screenings' => $screeningModel->countAll(),
    'total_bookings'   => $bookingModel->countAll(),
    'total_users'      => $userModel->countAll(),
    'total_revenue'    => $bookingModel->getTotalRevenue(),
    'pending_bookings' => $bookingModel->countByStatus('en_attente'),
    'today_screenings' => $screeningModel->countToday(),
];

// Chart data
$revenueByDay    = $bookingModel->getRevenueByDay(30);
$bookingsByDay   = $bookingModel->getBookingsByDay(7);
$topMovies       = $movieModel->getTopByBookings(5);
$bookingsByGenre = $movieModel->getBookingsByGenre();
$occupancyRates  = $hallModel->getOccupancyRates();
$recentBookings  = $bookingModel->getRecent(5);

require_once __DIR__ . '/../../views/admin/dashboard.php';
