<?php
/**
 * Admin Screening Controller
 */

require_once __DIR__ . '/../../models/Screening.php';
require_once __DIR__ . '/../../models/Movie.php';
require_once __DIR__ . '/../../models/Hall.php';

requireAdmin();

$screeningModel = new Screening();
$movieModel     = new Movie();
$hallModel      = new Hall();

switch ($action) {
    case 'create':
        handleCreate($screeningModel, $movieModel, $hallModel);
        break;
    case 'edit':
        handleEdit($screeningModel, $movieModel, $hallModel);
        break;
    case 'delete':
        handleDelete($screeningModel);
        break;
    case 'toggle':
        handleToggle($screeningModel);
        break;
    default:
        showList($screeningModel);
        break;
}

function showList(Screening $screeningModel): void
{
    $search    = sanitize($_GET['search'] ?? '');
    $page      = max(1, (int)($_GET['p'] ?? 1));
    $screenings = $screeningModel->getAll($search, $page);

    require_once __DIR__ . '/../../views/admin/screenings/index.php';
}

function handleCreate(Screening $screeningModel, Movie $movieModel, Hall $hallModel): void
{
    $errors = [];
    $old = [];

    $allMovies = $movieModel->getAll('', 1, 999)['data'];
    $allHalls  = $hallModel->getActive();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $old = [
            'movie_id'  => (int)($_POST['movie_id'] ?? 0),
            'hall_id'   => (int)($_POST['hall_id'] ?? 0),
            'show_date' => sanitize($_POST['show_date'] ?? ''),
            'show_time' => sanitize($_POST['show_time'] ?? ''),
            'price'     => (float)($_POST['price'] ?? 0),
        ];

        if (!$old['movie_id']) $errors[] = 'Veuillez sélectionner un film.';
        if (!$old['hall_id'])  $errors[] = 'Veuillez sélectionner une salle.';
        if (!validateDate($old['show_date'])) $errors[] = 'Date invalide.';
        if (!validateTime($old['show_time'])) $errors[] = 'Heure invalide.';
        if (!validateDecimal($old['price'], 1)) $errors[] = 'Le prix doit être supérieur à 0.';

        if (empty($errors)) {
            $screeningModel->create($old);
            setFlash('success', 'Séance ajoutée avec succès.');
            header('Location: ' . BASE_URL . '/index.php?page=admin-screenings');
            exit;
        }
    }

    require_once __DIR__ . '/../../views/admin/screenings/create.php';
}

function handleEdit(Screening $screeningModel, Movie $movieModel, Hall $hallModel): void
{
    global $id;

    $screening = $screeningModel->findById($id);
    if (!$screening) {
        setFlash('error', 'Séance introuvable.');
        header('Location: ' . BASE_URL . '/index.php?page=admin-screenings');
        exit;
    }

    $allMovies = $movieModel->getAll('', 1, 999)['data'];
    $allHalls  = $hallModel->getActive();
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'movie_id'  => (int)($_POST['movie_id'] ?? 0),
            'hall_id'   => (int)($_POST['hall_id'] ?? 0),
            'show_date' => sanitize($_POST['show_date'] ?? ''),
            'show_time' => sanitize($_POST['show_time'] ?? ''),
            'price'     => (float)($_POST['price'] ?? 0),
        ];

        if (!$data['movie_id']) $errors[] = 'Veuillez sélectionner un film.';
        if (!$data['hall_id'])  $errors[] = 'Veuillez sélectionner une salle.';
        if (!validateDate($data['show_date'])) $errors[] = 'Date invalide.';
        if (!validateTime($data['show_time'])) $errors[] = 'Heure invalide.';
        if (!validateDecimal($data['price'], 1)) $errors[] = 'Le prix doit être supérieur à 0.';

        if (empty($errors)) {
            $screeningModel->update($id, $data);
            setFlash('success', 'Séance mise à jour avec succès.');
            header('Location: ' . BASE_URL . '/index.php?page=admin-screenings');
            exit;
        }

        $screening = array_merge($screening, $data);
    }

    require_once __DIR__ . '/../../views/admin/screenings/edit.php';
}

function handleDelete(Screening $screeningModel): void
{
    global $id;
    $screeningModel->delete($id);
    setFlash('success', 'Séance supprimée avec succès.');
    header('Location: ' . BASE_URL . '/index.php?page=admin-screenings');
    exit;
}

function handleToggle(Screening $screeningModel): void
{
    global $id;
    $screeningModel->toggleStatus($id);
    setFlash('success', 'Statut de la séance mis à jour.');
    header('Location: ' . BASE_URL . '/index.php?page=admin-screenings');
    exit;
}
