<?php
/**
 * Booking Model
 */

class Booking
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT b.*, u.name as user_name, u.email as user_email,
                    s.show_date, s.show_time, s.price as unit_price,
                    m.title as movie_title, m.poster as movie_poster,
                    h.name as hall_name, h.hall_type
             FROM bookings b
             INNER JOIN users u ON b.user_id = u.id
             INNER JOIN screenings s ON b.screening_id = s.id
             INNER JOIN movies m ON s.movie_id = m.id
             INNER JOIN halls h ON s.hall_id = h.id
             WHERE b.id = ?"
        );
        $stmt->execute([$id]);
        $booking = $stmt->fetch();
        return $booking ?: null;
    }

    /**
     * Create a booking with seats
     */
    public function create(int $userId, int $screeningId, array $seats, float $totalPrice): int
    {
        $this->db->beginTransaction();

        try {
            // Create booking
            $stmt = $this->db->prepare(
                "INSERT INTO bookings (user_id, screening_id, total_seats, total_price, status)
                 VALUES (?, ?, ?, ?, 'en_attente')"
            );
            $stmt->execute([$userId, $screeningId, count($seats), $totalPrice]);
            $bookingId = (int)$this->db->lastInsertId();

            // Insert seats
            $seatStmt = $this->db->prepare(
                "INSERT INTO booking_seats (booking_id, seat_row, seat_number) VALUES (?, ?, ?)"
            );
            foreach ($seats as $seat) {
                $seatStmt->execute([$bookingId, $seat['row'], $seat['number']]);
            }

            $this->db->commit();
            return $bookingId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Get bookings for a specific user
     */
    public function getByUser(int $userId, int $page = 1, int $perPage = ITEMS_PER_PAGE): array
    {
        $offset = ($page - 1) * $perPage;

        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ?");
        $countStmt->execute([$userId]);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT b.*, s.show_date, s.show_time, s.price as unit_price,
                    m.title as movie_title, m.poster as movie_poster,
                    h.name as hall_name, h.hall_type
             FROM bookings b
             INNER JOIN screenings s ON b.screening_id = s.id
             INNER JOIN movies m ON s.movie_id = m.id
             INNER JOIN halls h ON s.hall_id = h.id
             WHERE b.user_id = ?
             ORDER BY b.booked_at DESC
             LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute([$userId]);

        return [
            'data'       => $stmt->fetchAll(),
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => (int)ceil($total / $perPage),
        ];
    }

    /**
     * Get all bookings (admin)
     */
    public function getAll(string $status = '', string $search = '', int $page = 1, int $perPage = ITEMS_PER_PAGE): array
    {
        $offset = ($page - 1) * $perPage;
        $where = "WHERE 1=1";
        $params = [];

        if ($status) {
            $where .= " AND b.status = ?";
            $params[] = $status;
        }

        if ($search) {
            $where .= " AND (u.name LIKE ? OR m.title LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $countStmt = $this->db->prepare(
            "SELECT COUNT(*) FROM bookings b
             INNER JOIN users u ON b.user_id = u.id
             INNER JOIN screenings s ON b.screening_id = s.id
             INNER JOIN movies m ON s.movie_id = m.id
             {$where}"
        );
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT b.*, u.name as user_name, u.email as user_email,
                    s.show_date, s.show_time,
                    m.title as movie_title,
                    h.name as hall_name, h.hall_type
             FROM bookings b
             INNER JOIN users u ON b.user_id = u.id
             INNER JOIN screenings s ON b.screening_id = s.id
             INNER JOIN movies m ON s.movie_id = m.id
             INNER JOIN halls h ON s.hall_id = h.id
             {$where}
             ORDER BY b.booked_at DESC
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

    /**
     * Get seats for a booking
     */
    public function getSeats(int $bookingId): array
    {
        $stmt = $this->db->prepare(
            "SELECT seat_row, seat_number FROM booking_seats WHERE booking_id = ? ORDER BY seat_row, seat_number"
        );
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll();
    }

    /**
     * Update booking status
     */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    /**
     * Cancel a booking (user)
     */
    public function cancel(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE bookings SET status = 'annule' WHERE id = ? AND user_id = ? AND status = 'en_attente'"
        );
        return $stmt->execute([$id, $userId]);
    }

    /**
     * Count all bookings
     */
    public function countAll(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
    }

    /**
     * Count by status
     */
    public function countByStatus(string $status): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM bookings WHERE status = ?");
        $stmt->execute([$status]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get total revenue
     */
    public function getTotalRevenue(): float
    {
        $stmt = $this->db->query(
            "SELECT COALESCE(SUM(total_price), 0) FROM bookings WHERE status != 'annule'"
        );
        return (float)$stmt->fetchColumn();
    }

    /**
     * Get revenue by day (last 30 days) for chart
     */
    public function getRevenueByDay(int $days = 30): array
    {
        $stmt = $this->db->prepare(
            "SELECT DATE(booked_at) as date, SUM(total_price) as revenue, COUNT(*) as count
             FROM bookings
             WHERE status != 'annule' AND booked_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
             GROUP BY DATE(booked_at)
             ORDER BY date ASC"
        );
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }

    /**
     * Get bookings count by day (last 7 days)
     */
    public function getBookingsByDay(int $days = 7): array
    {
        $stmt = $this->db->prepare(
            "SELECT DATE(booked_at) as date, COUNT(*) as count
             FROM bookings
             WHERE booked_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
             GROUP BY DATE(booked_at)
             ORDER BY date ASC"
        );
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }

    /**
     * Get recent bookings
     */
    public function getRecent(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            "SELECT b.*, u.name as user_name, m.title as movie_title
             FROM bookings b
             INNER JOIN users u ON b.user_id = u.id
             INNER JOIN screenings s ON b.screening_id = s.id
             INNER JOIN movies m ON s.movie_id = m.id
             ORDER BY b.booked_at DESC
             LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
