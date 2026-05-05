<?php
/**
 * Admin Movie Controller
 */

require_once __DIR__ . '/../../models/Movie.php';

requireAdmin();

$movieModel = new Movie();

switch ($action) {
    case 'create':
        handleCreate($movieModel);
        break;
    case 'edit':
        handleEdit($movieModel);
        break;
    case 'delete':
        handleDelete($movieModel);
        break;
    case 'toggle':
        handleToggle($movieModel);
        break;
    default:
        showList($movieModel);
        break;
}

function showList(Movie $movieModel): void
{
    $search = sanitize($_GET['search'] ?? '');
    $page   = max(1, (int)($_GET['p'] ?? 1));
    $movies = $movieModel->getAll($search, $page);

    require_once __DIR__ . '/../../views/admin/movies/index.php';
}

function handleCreate(Movie $movieModel): void
{
    $errors = [];
    $old = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $old = [
            'title'        => sanitize($_POST['title'] ?? ''),
            'description'  => sanitize($_POST['description'] ?? ''),
            'trailer_url'  => sanitize($_POST['trailer_url'] ?? ''),
            'duration_min' => (int)($_POST['duration_min'] ?? 90),
            'genre'        => sanitize($_POST['genre'] ?? ''),
            'release_date' => sanitize($_POST['release_date'] ?? ''),
            'rating'       => sanitize($_POST['rating'] ?? 'TP'),
        ];

        // Validate
        $required = validateRequired(
            ['title' => 'Titre', 'genre' => 'Genre', 'duration_min' => 'Durée'],
            $old
        );
        $errors = array_merge($errors, $required);

        if (!validateInt($old['duration_min'], 1, 500)) {
            $errors[] = 'La durée doit être entre 1 et 500 minutes.';
        }

        // Handle poster upload
        $posterFilename = null;
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload = uploadFile($_FILES['poster']);
            if ($upload['success']) {
                $posterFilename = $upload['filename'];
            } else {
                $errors[] = $upload['error'];
            }
        }

        if (empty($errors)) {
            $old['poster'] = $posterFilename;
            $movieModel->create($old);

            setFlash('success', 'Film ajouté avec succès.');
            header('Location: ' . BASE_URL . '/index.php?page=admin-movies');
            exit;
        }
    }

    require_once __DIR__ . '/../../views/admin/movies/create.php';
}

function handleEdit(Movie $movieModel): void
{
    global $id;

    $movie = $movieModel->findById($id);
    if (!$movie) {
        setFlash('error', 'Film introuvable.');
        header('Location: ' . BASE_URL . '/index.php?page=admin-movies');
        exit;
    }

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'title'        => sanitize($_POST['title'] ?? ''),
            'description'  => sanitize($_POST['description'] ?? ''),
            'trailer_url'  => sanitize($_POST['trailer_url'] ?? ''),
            'duration_min' => (int)($_POST['duration_min'] ?? 90),
            'genre'        => sanitize($_POST['genre'] ?? ''),
            'release_date' => sanitize($_POST['release_date'] ?? ''),
            'rating'       => sanitize($_POST['rating'] ?? 'TP'),
        ];

        $required = validateRequired(
            ['title' => 'Titre', 'genre' => 'Genre'],
            $data
        );
        $errors = array_merge($errors, $required);

        // Handle poster upload
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload = uploadFile($_FILES['poster']);
            if ($upload['success']) {
                // Delete old poster
                if ($movie['poster']) {
                    deleteUploadedFile($movie['poster']);
                }
                $data['poster'] = $upload['filename'];
            } else {
                $errors[] = $upload['error'];
            }
        }

        if (empty($errors)) {
            $movieModel->update($id, $data);

            setFlash('success', 'Film mis à jour avec succès.');
            header('Location: ' . BASE_URL . '/index.php?page=admin-movies');
            exit;
        }

        $movie = array_merge($movie, $data);
    }

    require_once __DIR__ . '/../../views/admin/movies/edit.php';
}

function handleDelete(Movie $movieModel): void
{
    global $id;

    $movie = $movieModel->findById($id);
    if ($movie) {
        if ($movie['poster']) {
            deleteUploadedFile($movie['poster']);
        }
        $movieModel->delete($id);
        setFlash('success', 'Film supprimé avec succès.');
    }

    header('Location: ' . BASE_URL . '/index.php?page=admin-movies');
    exit;
}

function handleToggle(Movie $movieModel): void
{
    global $id;
    $movieModel->toggleStatus($id);
    setFlash('success', 'Statut du film mis à jour.');
    header('Location: ' . BASE_URL . '/index.php?page=admin-movies');
    exit;
}
