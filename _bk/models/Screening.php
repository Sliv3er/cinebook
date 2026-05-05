<?php
/**
 * Screening Model
 */

class Screening
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT s.*, m.title as movie_title, m.poster as movie_poster, m.duration_min,
                    m.genre as movie_genre, m.rating as movie_rating,
                    h.name as hall_name, h.hall_type, h.rows_count, h.cols_count
             FROM screenings s
             INNER JOIN movies m ON s.movie_id = m.id
             INNER JOIN halls h ON s.hall_id = h.id
             WHERE s.id = ?"
        );
        $stmt->execute([$id]);
        $screening = $stmt->fetch();
        return $screening ?: null;
    }

    /**
     * Get upcoming screenings for a specific movie
     */
    public function getByMovie(int $movieId): array
    {
        $stmt = $this->db->prepare(
            "SELECT s.*, h.name as hall_name, h.hall_type, h.rows_count, h.cols_count
             FROM screenings s
             INNER JOIN halls h ON s.hall_id = h.id
             WHERE s.movie_id = ? AND s.is_active = 1
             AND (s.show_date > CURDATE() OR (s.show_date = CURDATE() AND s.show_time > CURTIME()))
             ORDER BY s.show_date ASC, s.show_time ASC"
        );
        $stmt->execute([$movieId]);
        return $stmt->fetchAll();
    }

    /**
     * Get all screenings (admin)
     */
    public function getAll(string $search = '', int $page = 1, int $perPage = ITEMS_PER_PAGE): array
    {
        $offset = ($page - 1) * $perPage;
        $where = "WHERE 1=1";
        $params = [];

        if ($search) {
            $where .= " AND (m.title LIKE ? OR h.name LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $countStmt = $this->db->prepare(
            "SELECT COUNT(*) FROM screenings s
             INNER JOIN movies m ON s.movie_id = m.id
             INNER JOIN halls h ON s.hall_id = h.id
             {$where}"
        );
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT s.*, m.title as movie_title, m.poster as movie_poster,
                    h.name as hall_name, h.hall_type
             FROM screenings s
             INNER JOIN movies m ON s.movie_id = m.id
             INNER JOIN halls h ON s.hall_id = h.id
             {$where}
             ORDER BY s.show_date DESC, s.show_time DESC
             LIMIT {$perPage} OFFSET {$offset}"
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

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO screenings (movie_id, hall_id, show_date, show_time, price)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['movie_id'],
            $data['hall_id'],
            $data['show_date'],
            $data['show_time'],
            $data['price'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE screenings SET movie_id = ?, hall_id = ?, show_date = ?, show_time = ?, price = ?
             WHERE id = ?"
        );
        return $stmt->execute([
            $data['movie_id'],
            $data['hall_id'],
            $data['show_date'],
            $data['show_time'],
            $data['price'],
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM screenings WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function toggleStatus(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE screenings SET is_active = NOT is_active WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Get booked seats for a screening
     */
    public function getBookedSeats(int $screeningId): array
    {
        $stmt = $this->db->prepare(
            "SELECT bs.seat_row, bs.seat_number
             FROM booking_seats bs
             INNER JOIN bookings b ON bs.booking_id = b.id
             WHERE b.screening_id = ? AND b.status != 'annule'"
        );
        $stmt->execute([$screeningId]);
        return $stmt->fetchAll();
    }

    /**
     * Count available seats for a screening
     */
    public function getAvailableSeats(int $screeningId): int
    {
        $screening = $this->findById($screeningId);
        if (!$screening) return 0;

        $totalSeats = $screening['rows_count'] * $screening['cols_count'];
        $bookedSeats = count($this->getBookedSeats($screeningId));

        return $totalSeats - $bookedSeats;
    }

    public function countAll(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM screenings")->fetchColumn();
    }

    /**
     * Get today's screenings count
     */
    public function countToday(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM screenings WHERE show_date = CURDATE() AND is_active = 1"
        );
        return (int)$stmt->fetchColumn();
    }
}
