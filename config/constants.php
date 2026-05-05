<?php
/**
 * Application Constants
 */

// Base URL - adjust if your folder name differs
define('BASE_URL', '/cinebook');

// App info
define('APP_NAME', 'CinéBook');
define('APP_TAGLINE', 'Votre cinéma, vos places, en un clic.');

// Upload settings
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/movies/');
define('UPLOAD_URL', BASE_URL . '/assets/uploads/movies/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'gif']);

// Pagination
define('ITEMS_PER_PAGE', 12);

// Genres list
define('GENRES', [
    'Action',
    'Animation',
    'Aventure',
    'Comédie',
    'Documentaire',
    'Drame',
    'Fantastique',
    'Horreur',
    'Romance',
    'Science-Fiction',
    'Thriller',
    'Western'
]);

// Ratings list
define('RATINGS', [
    'TP'   => 'Tous Publics',
    '-10'  => 'Interdit aux moins de 10 ans',
    '-12'  => 'Interdit aux moins de 12 ans',
    '-16'  => 'Interdit aux moins de 16 ans',
    '-18'  => 'Interdit aux moins de 18 ans',
]);

// Hall types
define('HALL_TYPES', ['Standard', 'IMAX', 'VIP']);

// Booking statuses
define('BOOKING_STATUSES', [
    'en_attente' => 'En attente',
    'confirme'   => 'Confirmé',
    'annule'     => 'Annulé',
]);
