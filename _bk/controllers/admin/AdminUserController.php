<?php
/**
 * Admin User Controller
 */

require_once __DIR__ . '/../../models/User.php';

requireAdmin();

$userModel = new User();

switch ($action) {
    case 'toggle':
        $userModel->toggleStatus($id);
        setFlash('success', 'Statut de l\'utilisateur mis à jour.');
        header('Location: ' . BASE_URL . '/index.php?page=admin-users');
        exit;
    default:
        $search = sanitize($_GET['search'] ?? '');
        $page   = max(1, (int)($_GET['p'] ?? 1));
        $users  = $userModel->getAll($search, $page);
        require_once __DIR__ . '/../../views/admin/users/index.php';
        break;
}
