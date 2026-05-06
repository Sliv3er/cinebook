<?php
/**
 * Hall Model
 */

class Hall
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM halls WHERE id = ?");
        $stmt->execute([$id]);
        $hall = $stmt->fetch();
        return $hall ?: null;
    }

    public function getAll(int $page = 1, int $perPage = ITEMS_PER_PAGE): array
    {
        $offset = ($page - 1) * $perPage;

        $total = (int)$this->db->query("SELECT COUNT(*) FROM halls")->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT * FROM halls ORDER BY name ASC LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute();

        return [
            'data'       => $stmt->fetchAll(),
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => (int)ceil($total / $perPage),
        ];
    }

    public function getActive(): array
    {
        $stmt = $this->db->query("SELECT * FROM halls WHERE is_active = 1 ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO halls (name, rows_count, cols_count, hall_type) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['name'],
            $data['rows_count'],
            $data['cols_count'],
            $data['hall_type'] ?? 'Standard',
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE halls SET name = ?, rows_count = ?, cols_count = ?, hall_type = ? WHERE id = ?"
        );
        return $stmt->execute([
            $data['name'],
            $data['rows_count'],
            $data['cols_count'],
            $data['hall_type'],
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM halls WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function toggleStatus(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE halls SET is_active = NOT is_active WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countAll(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM halls")->fetchColumn();
    }

    /**
     * Get hall capacity (rows * cols)
     */
    public function getCapacity(int $id): int
    {
        $hall = $this->findById($id);
        return $hall ? ($hall['rows_count'] * $hall['cols_count']) : 0;
    }

    /**
     * Get occupancy rate per hall (for statistics)
     */
    public function getOccupancyRates(): array
    {
        $stmt = $this->db->query(
            "SELECT h.name, h.hall_type, h.rows_count * h.cols_count as capacity,
                    COUNT(DISTINCT s.id) as screening_count,
                    COALESCE(SUM(b.total_seats), 0) as booked_seats
             FROM halls h
             LEFT JOIN screenings s ON h.id = s.hall_id
             LEFT JOIN bookings b ON s.id = b.screening_id AND b.status != 'annule'
             GROUP BY h.id
             ORDER BY h.name"
        );
        return $stmt->fetchAll();
    }
}
