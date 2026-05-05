<?php
/**
 * Movie Controller (Public)
 */

require_once __DIR__ . '/../models/Movie.php';
require_once __DIR__ . '/../models/Screening.php';

$movieModel     = new Movie();
$screeningModel = new Screening();

switch ($action) {
    case 'detail':
    case 'view':
        showMovieDetail($movieModel, $screeningModel);
        break;
    default:
        showMoviesList($movieModel);
        break;
}

function showMoviesList(Movie $movieModel): void
{
    $genre  = sanitize($_GET['genre'] ?? '');
    $search = sanitize($_GET['search'] ?? '');
    $page   = max(1, (int)($_GET['p'] ?? 1));

    $movies = $movieModel->getActive($genre, $search, $page);
    $genres = $movieModel->getGenres();

    require_once __DIR__ . '/../views/user/movies.php';
}

function showMovieDetail(Movie $movieModel, Screening $screeningModel): void
{
    global $id;

    $movie = $movieModel->findById($id);
    if (!$movie || !$movie['is_active']) {
        http_response_code(404);
        require_once __DIR__ . '/../views/errors/404.php';
        return;
    }

    $screenings = $screeningModel->getByMovie($id);

    require_once __DIR__ . '/../views/user/movie_detail.php';
}
