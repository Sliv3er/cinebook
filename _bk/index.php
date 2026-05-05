<?php
/**
 * CinéBook - Front Controller / Router
 */

session_start();

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/auth.php';
require_once __DIR__ . '/helpers/validation.php';
require_once __DIR__ . '/helpers/upload.php';

// Get the requested page
$page = isset($_GET['page']) ? trim($_GET['page']) : 'home';
$action = isset($_GET['action']) ? trim($_GET['action']) : 'index';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Route mapping
$routes = [
    // Public routes
    'home'            => 'controllers/HomeController.php',
    'movies'          => 'controllers/MovieController.php',
    'movie'           => 'controllers/MovieController.php',
    'screening'       => 'controllers/ScreeningController.php',
    'booking'         => 'controllers/BookingController.php',

    // Auth routes
    'login'           => 'controllers/AuthController.php',
    'register'        => 'controllers/AuthController.php',
    'logout'          => 'controllers/AuthController.php',

    // User routes
    'profile'         => 'controllers/ProfileController.php',

    // Admin routes
    'admin'           => 'controllers/admin/DashboardController.php',
    'admin-movies'    => 'controllers/admin/AdminMovieController.php',
    'admin-halls'     => 'controllers/admin/AdminHallController.php',
    'admin-screenings'=> 'controllers/admin/AdminScreeningController.php',
    'admin-bookings'  => 'controllers/admin/AdminBookingController.php',
    'admin-users'     => 'controllers/admin/AdminUserController.php',
];

// Check if route exists
if (array_key_exists($page, $routes)) {
    require_once __DIR__ . '/' . $routes[$page];
} else {
    http_response_code(404);
    require_once __DIR__ . '/views/errors/404.php';
}
