<?php
/**
 * Home Controller
 */

require_once __DIR__ . '/../models/Movie.php';
require_once __DIR__ . '/../models/Screening.php';

$movieModel = new Movie();

$featuredMovies = $movieModel->getFeatured(6);
$nowShowing = $movieModel->getWithUpcomingScreenings();

require_once __DIR__ . '/../views/user/home.php';
