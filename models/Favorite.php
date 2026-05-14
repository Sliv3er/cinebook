<?php
/**
 * Favorite Model
 * Gestion des films préférés
 */

class Favorite
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    /**
     * Ajouter un film aux favoris
     */
    public function add(int $userId, int $movieId): bool
    {
        $stmt = $this->db->prepare(
            "INSERT IGNORE INTO favorites (user_id, movie_id) VALUES (?, ?)"
        );
        return $stmt->execute([$userId, $movieId]);
    }

    /**
     * Retirer un film des favoris
     */
    public function remove(int $userId, int $movieId): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM favorites WHERE user_id = ? AND movie_id = ?"
        );
        return $stmt->execute([$userId, $movieId]);
    }

    /**
     * Vérifier si un film est en favori
     */
    public function isFavorite(int $userId, int $movieId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM favorites WHERE user_id = ? AND movie_id = ?"
        );
        $stmt->execute([$userId, $movieId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Récupérer tous les IDs des films favoris d'un utilisateur
     */
    public function getUserFavoriteIds(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT movie_id FROM favorites WHERE user_id = ?"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Récupérer les films favoris avec les détails
     */
    public function getUserFavorites(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT m.* FROM favorites f 
             JOIN movies m ON f.movie_id = m.id 
             WHERE f.user_id = ? AND m.is_active = 1 
             ORDER BY f.created_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Compter les favoris d'un utilisateur
     */
    public function countByUser(int $userId): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM favorites WHERE user_id = ?"
        );
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }
}
