<?php
/**
 * Input Validation Helper Functions
 */

/**
 * Sanitize input string
 */
function sanitize(string $input): string
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email format
 */
function validateEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate password strength (min 6 chars)
 */
function validatePassword(string $password): bool
{
    return strlen($password) >= 6;
}

/**
 * Validate required fields
 * Returns array of error messages
 */
function validateRequired(array $fields, array $data): array
{
    $errors = [];
    foreach ($fields as $field => $label) {
        if (empty($data[$field]) || trim($data[$field]) === '') {
            $errors[] = "Le champ « {$label} » est obligatoire.";
        }
    }
    return $errors;
}

/**
 * Validate integer value
 */
function validateInt($value, int $min = 0, int $max = PHP_INT_MAX): bool
{
    $val = filter_var($value, FILTER_VALIDATE_INT);
    return $val !== false && $val >= $min && $val <= $max;
}

/**
 * Validate decimal / float
 */
function validateDecimal($value, float $min = 0): bool
{
    $val = filter_var($value, FILTER_VALIDATE_FLOAT);
    return $val !== false && $val >= $min;
}

/**
 * Validate date format (Y-m-d)
 */
function validateDate(string $date): bool
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

/**
 * Validate time format (H:i or H:i:s)
 */
function validateTime(string $time): bool
{
    return (bool)preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $time);
}

/**
 * Escape output for HTML display
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
