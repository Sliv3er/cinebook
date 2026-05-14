<?php
/**
 * Favorite Controller
 * Gestion des films préférés
 */

require_once __DIR__ . '/../models/Favorite.php';
require_once __DIR__ . '/../models/Movie.php';

$favoriteModel = new Favorite();

switch ($action) {
    case 'toggle':
        toggleFavorite($favoriteModel);
        break;
    default:
        showFavorites($favoriteModel);
        break;
}

/**
 * Ajouter/retirer un film des favoris (AJAX)
 */
function toggleFavorite(Favorite $favoriteModel): void
{
    requireLogin();

    // Accept both GET and POST
    $movieId = (int)($_POST['movie_id'] ?? $_GET['id'] ?? 0);
    $userId  = currentUserId();

    if ($movieId <= 0) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Film invalide']);
            exit;
        }
        setFlash('error', 'Film invalide.');
        header('Location: ' . BASE_URL . '/index.php?page=movies');
        exit;
    }

    $isFav = $favoriteModel->isFavorite($userId, $movieId);

    if ($isFav) {
        $favoriteModel->remove($userId, $movieId);
        $newState = false;
        $message = 'Film retiré des favoris.';
    } else {
        $favoriteModel->add($userId, $movieId);
        $newState = true;
        $message = 'Film ajouté aux favoris !';
    }

    $count = $favoriteModel->countByUser($userId);

    // AJAX response
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode([
            'success'    => true,
            'isFavorite' => $newState,
            'count'      => $count,
            'message'    => $message
        ]);
        exit;
    }

    // Normal redirect
    setFlash('success', $message);
    $redirect = $_SERVER['HTTP_REFERER'] ?? BASE_URL . '/index.php?page=movies';
    header('Location: ' . $redirect);
    exit;
}

/**
 * Afficher la page des favoris
 */
function showFavorites(Favorite $favoriteModel): void
{
    requireLogin();

    $userId    = currentUserId();
    $favorites = $favoriteModel->getUserFavorites($userId);

    require_once __DIR__ . '/../views/user/favorites.php';
}
