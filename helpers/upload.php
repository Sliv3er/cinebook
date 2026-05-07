<?php
/**
 * File Upload Helper
 */

/**
 * Handle file upload for movie posters
 *
 * @param array $file The $_FILES element
 * @return array ['success' => bool, 'filename' => string|null, 'error' => string|null]
 */
function uploadFile(array $file): array
{
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'filename' => null, 'error' => 'Erreur lors du téléchargement du fichier.'];
    }

    // Check file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'filename' => null, 'error' => 'Le fichier dépasse la taille maximale autorisée (5 Mo).'];
    }

    // Check extension
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_EXTENSIONS)) {
        return [
            'success'  => false,
            'filename' => null,
            'error'    => 'Extension non autorisée. Extensions acceptées : ' . implode(', ', ALLOWED_EXTENSIONS)
        ];
    }

    // Check MIME type
    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowedMimes)) {
        return ['success' => false, 'filename' => null, 'error' => 'Type de fichier non autorisé.'];
    }

    // Create upload directory if not exists
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    // Generate unique filename
    $filename = uniqid('movie_', true) . '.' . $ext;
    $destination = UPLOAD_DIR . $filename;

    // Move the file
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $filename, 'error' => null];
    }

    return ['success' => false, 'filename' => null, 'error' => 'Impossible de sauvegarder le fichier.'];
}

/**
 * Delete an uploaded file
 */
function deleteUploadedFile(string $filename): bool
{
    $path = UPLOAD_DIR . $filename;
    if (file_exists($path)) {
        return unlink($path);
    }
    return false;
}

/**
 * Get the full URL for a movie poster
 * Supports both local filenames and external URLs (e.g. TMDB)
 */
function posterUrl(?string $filename): string
{
    if (!$filename) {
        return BASE_URL . '/assets/img/no-poster.svg';
    }
    // External URL (TMDB, etc.)
    if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) {
        return $filename;
    }
    // Local uploaded file
    if (file_exists(UPLOAD_DIR . $filename)) {
        return UPLOAD_URL . $filename;
    }
    return BASE_URL . '/assets/img/no-poster.svg';
}
