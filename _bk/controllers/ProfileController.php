<?php
/**
 * Profile Controller
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Booking.php';

requireLogin();

$userModel    = new User();
$bookingModel = new Booking();

switch ($action) {
    case 'edit':
        handleEditProfile($userModel);
        break;
    default:
        showProfile($userModel, $bookingModel);
        break;
}

function showProfile(User $userModel, Booking $bookingModel): void
{
    $user = $userModel->findById(currentUserId());
    $page = max(1, (int)($_GET['p'] ?? 1));
    $bookings = $bookingModel->getByUser(currentUserId(), $page);

    require_once __DIR__ . '/../views/user/profile.php';
}

function handleEditProfile(User $userModel): void
{
    $user   = $userModel->findById(currentUserId());
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'name'  => sanitize($_POST['name'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'phone' => sanitize($_POST['phone'] ?? ''),
        ];

        $password        = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate
        $required = validateRequired(['name' => 'Nom', 'email' => 'Email'], $data);
        $errors = array_merge($errors, $required);

        if (!validateEmail($data['email'])) {
            $errors[] = 'L\'adresse email n\'est pas valide.';
        }

        // Check email uniqueness
        $existingUser = $userModel->findByEmail($data['email']);
        if ($existingUser && $existingUser['id'] != currentUserId()) {
            $errors[] = 'Cette adresse email est déjà utilisée.';
        }

        // Password change
        if (!empty($password)) {
            if (!validatePassword($password)) {
                $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
            }
            if ($password !== $confirmPassword) {
                $errors[] = 'Les mots de passe ne correspondent pas.';
            }
            $data['password'] = $password;
        }

        if (empty($errors)) {
            $userModel->update(currentUserId(), $data);

            $_SESSION['user_name']  = $data['name'];
            $_SESSION['user_email'] = $data['email'];

            setFlash('success', 'Profil mis à jour avec succès.');
            header('Location: ' . BASE_URL . '/index.php?page=profile');
            exit;
        }

        $user = array_merge($user, $data);
    }

    require_once __DIR__ . '/../views/user/profile_edit.php';
}
