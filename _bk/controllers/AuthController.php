<?php
/**
 * Authentication Controller
 */

require_once __DIR__ . '/../models/User.php';

$userModel = new User();

switch ($page) {
    case 'login':
        handleLogin($userModel);
        break;
    case 'register':
        handleRegister($userModel);
        break;
    case 'logout':
        handleLogout();
        break;
}

function handleLogin(User $userModel): void
{
    // Already logged in
    if (isLoggedIn()) {
        header('Location: ' . BASE_URL . '/index.php?page=home');
        exit;
    }

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email    = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validate
        if (empty($email) || empty($password)) {
            $errors[] = 'Veuillez remplir tous les champs.';
        } else {
            $user = $userModel->findByEmail($email);

            if (!$user || !verifyPassword($password, $user['password'])) {
                $errors[] = 'Email ou mot de passe incorrect.';
            } elseif (!$user['is_active']) {
                $errors[] = 'Votre compte a été désactivé. Contactez l\'administration.';
            } else {
                // Login success
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                setFlash('success', 'Bienvenue, ' . e($user['name']) . ' !');

                if ($user['role'] === 'admin') {
                    header('Location: ' . BASE_URL . '/index.php?page=admin');
                } else {
                    header('Location: ' . BASE_URL . '/index.php?page=home');
                }
                exit;
            }
        }
    }

    require_once __DIR__ . '/../views/auth/login.php';
}

function handleRegister(User $userModel): void
{
    if (isLoggedIn()) {
        header('Location: ' . BASE_URL . '/index.php?page=home');
        exit;
    }

    $errors = [];
    $old = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $old = [
            'name'  => sanitize($_POST['name'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'phone' => sanitize($_POST['phone'] ?? ''),
        ];

        $password        = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate
        $required = validateRequired(
            ['name' => 'Nom complet', 'email' => 'Email', 'password' => 'Mot de passe'],
            array_merge($old, ['password' => $password])
        );
        $errors = array_merge($errors, $required);

        if (!validateEmail($old['email'])) {
            $errors[] = 'L\'adresse email n\'est pas valide.';
        }

        if (!validatePassword($password)) {
            $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }

        if ($userModel->findByEmail($old['email'])) {
            $errors[] = 'Cette adresse email est déjà utilisée.';
        }

        if (empty($errors)) {
            $userModel->create([
                'name'     => $old['name'],
                'email'    => $old['email'],
                'password' => $password,
                'phone'    => $old['phone'],
            ]);

            setFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;
        }
    }

    require_once __DIR__ . '/../views/auth/register.php';
}

function handleLogout(): void
{
    session_destroy();
    header('Location: ' . BASE_URL . '/index.php?page=login');
    exit;
}
