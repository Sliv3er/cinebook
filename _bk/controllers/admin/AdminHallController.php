<?php
/**
 * Admin Hall Controller
 */

require_once __DIR__ . '/../../models/Hall.php';

requireAdmin();

$hallModel = new Hall();

switch ($action) {
    case 'create':
        handleCreate($hallModel);
        break;
    case 'edit':
        handleEdit($hallModel);
        break;
    case 'delete':
        handleDelete($hallModel);
        break;
    case 'toggle':
        handleToggle($hallModel);
        break;
    default:
        showList($hallModel);
        break;
}

function showList(Hall $hallModel): void
{
    $page  = max(1, (int)($_GET['p'] ?? 1));
    $halls = $hallModel->getAll($page);

    require_once __DIR__ . '/../../views/admin/halls/index.php';
}

function handleCreate(Hall $hallModel): void
{
    $errors = [];
    $old = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $old = [
            'name'       => sanitize($_POST['name'] ?? ''),
            'rows_count' => (int)($_POST['rows_count'] ?? 8),
            'cols_count' => (int)($_POST['cols_count'] ?? 12),
            'hall_type'  => sanitize($_POST['hall_type'] ?? 'Standard'),
        ];

        $required = validateRequired(['name' => 'Nom de la salle'], $old);
        $errors = array_merge($errors, $required);

        if (!validateInt($old['rows_count'], 1, 30)) {
            $errors[] = 'Le nombre de rangées doit être entre 1 et 30.';
        }
        if (!validateInt($old['cols_count'], 1, 30)) {
            $errors[] = 'Le nombre de sièges par rangée doit être entre 1 et 30.';
        }
        if (!in_array($old['hall_type'], HALL_TYPES)) {
            $errors[] = 'Type de salle invalide.';
        }

        if (empty($errors)) {
            $hallModel->create($old);
            setFlash('success', 'Salle ajoutée avec succès.');
            header('Location: ' . BASE_URL . '/index.php?page=admin-halls');
            exit;
        }
    }

    require_once __DIR__ . '/../../views/admin/halls/create.php';
}

function handleEdit(Hall $hallModel): void
{
    global $id;

    $hall = $hallModel->findById($id);
    if (!$hall) {
        setFlash('error', 'Salle introuvable.');
        header('Location: ' . BASE_URL . '/index.php?page=admin-halls');
        exit;
    }

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'name'       => sanitize($_POST['name'] ?? ''),
            'rows_count' => (int)($_POST['rows_count'] ?? 8),
            'cols_count' => (int)($_POST['cols_count'] ?? 12),
            'hall_type'  => sanitize($_POST['hall_type'] ?? 'Standard'),
        ];

        $required = validateRequired(['name' => 'Nom de la salle'], $data);
        $errors = array_merge($errors, $required);

        if (!validateInt($data['rows_count'], 1, 30)) {
            $errors[] = 'Le nombre de rangées doit être entre 1 et 30.';
        }
        if (!validateInt($data['cols_count'], 1, 30)) {
            $errors[] = 'Le nombre de sièges par rangée doit être entre 1 et 30.';
        }

        if (empty($errors)) {
            $hallModel->update($id, $data);
            setFlash('success', 'Salle mise à jour avec succès.');
            header('Location: ' . BASE_URL . '/index.php?page=admin-halls');
            exit;
        }

        $hall = array_merge($hall, $data);
    }

    require_once __DIR__ . '/../../views/admin/halls/edit.php';
}

function handleDelete(Hall $hallModel): void
{
    global $id;
    $hallModel->delete($id);
    setFlash('success', 'Salle supprimée avec succès.');
    header('Location: ' . BASE_URL . '/index.php?page=admin-halls');
    exit;
}

function handleToggle(Hall $hallModel): void
{
    global $id;
    $hallModel->toggleStatus($id);
    setFlash('success', 'Statut de la salle mis à jour.');
    header('Location: ' . BASE_URL . '/index.php?page=admin-halls');
    exit;
}
