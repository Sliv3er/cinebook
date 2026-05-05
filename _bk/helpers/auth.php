<?php
/**
 * Authentication Helper Functions
 */

/**
 * Check if user is logged in
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Check if current user is admin
 */
function isAdmin(): bool
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Get current user ID
 */
function currentUserId(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user name
 */
function currentUserName(): ?string
{
    return $_SESSION['user_name'] ?? null;
}

/**
 * Require login - redirect to login page if not authenticated
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        $_SESSION['flash_error'] = 'Veuillez vous connecter pour accéder à cette page.';
        header('Location: ' . BASE_URL . '/index.php?page=login');
        exit;
    }
}

/**
 * Require admin role - redirect if not admin
 */
function requireAdmin(): void
{
    requireLogin();
    if (!isAdmin()) {
        $_SESSION['flash_error'] = 'Accès refusé. Vous n\'avez pas les droits nécessaires.';
        header('Location: ' . BASE_URL . '/index.php?page=home');
        exit;
    }
}

/**
 * Set flash message
 */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash_' . $type] = $message;
}

/**
 * Get and clear flash message
 */
function getFlash(string $type): ?string
{
    $key = 'flash_' . $type;
    if (isset($_SESSION[$key])) {
        $message = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $message;
    }
    return null;
}

/**
 * Hash password
 */
function hashPassword(string $password): string
{
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Verify password
 */
function verifyPassword(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

/**
 * Generate CSRF token
 */
function generateCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCsrfToken(string $token): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Output CSRF hidden input field
 */
function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . generateCsrfToken() . '">';
}
