<?php
/**
 * Movie Model
 */

class Movie
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    /**
     * Find movie by ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM movies WHERE id = ?");
        $stmt->execute([$id]);
        $movie = $stmt->fetch();
        return $movie ?: null;
    }

    /**
     * Get all active movies for public display
     */
    public function getActive(string $genre = '', string $search = '', int $page = 1, int $perPage = ITEMS_PER_PAGE): array
    {
        $offset = ($page - 1) * $perPage;
        $where = "WHERE is_active = 1";
        $params = [];

        if ($genre) {
            $where .= " AND genre = ?";
            $params[] = $genre;
        }

        if ($search) {
            $where .= " AND (title LIKE ? OR description LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM movies {$where}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT * FROM movies {$where} ORDER BY release_date DESC, created_at DESC LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute($params);

        return [
            'data'       => $stmt->fetchAll(),
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => (int)ceil($total / $perPage),
        ];
    }

    /**
     * Get all movies (admin - includes inactive)
     */
    public function getAll(string $search = '', int $page = 1, int $perPage = ITEMS_PER_PAGE): array
    {
        $offset = ($page - 1) * $perPage;
        $where = "WHERE 1=1";
        $params = [];

        if ($search) {
            $where .= " AND (title LIKE ? OR genre LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM movies {$where}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT * FROM movies {$where} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute($params);

        return [
            'data'       => $stmt->fetchAll(),
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => (int)ceil($total / $perPage),
        ];
    }

    /**
     * Create a new movie
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO movies (title, description, poster, trailer_url, duration_min, genre, release_date, rating)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['title'],
            $data['description'] ?? null,
            $data['poster'] ?? null,
            $data['trailer_url'] ?? null,
            $data['duration_min'],
            $data['genre'],
            $data['release_date'] ?? null,
            $data['rating'] ?? 'TP',
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Update a movie
     */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $values = [];

        $allowed = ['title', 'description', 'poster', 'trailer_url', 'duration_min', 'genre', 'release_date', 'rating', 'is_active'];

        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "{$field} = ?";
                $values[] = $data[$field];
            }
        }

        if (empty($fields)) return false;

        $values[] = $id;
        $sql = "UPDATE movies SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    /**
     * Delete a movie
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM movies WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE movies SET is_active = NOT is_active WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Count all movies
     */
    public function countAll(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM movies")->fetchColumn();
    }

    /**
     * Get featured movies (latest active)
     */
    public function getFeatured(int $limit = 6): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM movies WHERE is_active = 1 ORDER BY release_date DESC LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Get all distinct genres from active movies
     */
    public function getGenres(): array
    {
        $stmt = $this->db->query("SELECT DISTINCT genre FROM movies WHERE is_active = 1 ORDER BY genre");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get movies with upcoming screenings
     */
    public function getWithUpcomingScreenings(): array
    {
        $stmt = $this->db->prepare(
            "SELECT DISTINCT m.* FROM movies m
             INNER JOIN screenings s ON m.id = s.movie_id
             WHERE m.is_active = 1 AND s.is_active = 1
             AND (s.show_date > CURDATE() OR (s.show_date = CURDATE() AND s.show_time > CURTIME()))
             ORDER BY m.release_date DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get top movies by bookings count (for statistics)
     */
    public function getTopByBookings(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            "SELECT m.title, m.genre, COUNT(b.id) as booking_count, SUM(b.total_price) as revenue
             FROM movies m
             INNER JOIN screenings s ON m.id = s.movie_id
             INNER JOIN bookings b ON s.id = b.screening_id
             WHERE b.status != 'annule'
             GROUP BY m.id
             ORDER BY booking_count DESC
             LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Get bookings count per genre (for statistics)
     */
    public function getBookingsByGenre(): array
    {
        $stmt = $this->db->query(
            "SELECT m.genre, COUNT(b.id) as count
             FROM movies m
             INNER JOIN screenings s ON m.id = s.movie_id
             INNER JOIN bookings b ON s.id = b.screening_id
             WHERE b.status != 'annule'
             GROUP BY m.genre
             ORDER BY count DESC"
        );
        return $stmt->fetchAll();
    }
}
