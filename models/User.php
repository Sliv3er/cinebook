<?php
/**
 * User Model
 */

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    /**
     * Find user by ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Create a new user
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['name'],
            $data['email'],
            hashPassword($data['password']),
            $data['phone'] ?? null,
            $data['role'] ?? 'user',
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Update user profile
     */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $values = [];

        foreach (['name', 'email', 'phone'] as $field) {
            if (isset($data[$field])) {
                $fields[] = "{$field} = ?";
                $values[] = $data[$field];
            }
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $fields[] = "password = ?";
            $values[] = hashPassword($data['password']);
        }

        if (empty($fields)) return false;

        $values[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    /**
     * Get all users (for admin)
     */
    public function getAll(string $search = '', int $page = 1, int $perPage = ITEMS_PER_PAGE): array
    {
        $offset = ($page - 1) * $perPage;

        $where = "WHERE role = 'user'";
        $params = [];

        if ($search) {
            $where .= " AND (name LIKE ? OR email LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        // Get total count
        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM users {$where}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // Get paginated results
        $stmt = $this->db->prepare(
            "SELECT * FROM users {$where} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute($params);
        $users = $stmt->fetchAll();

        return [
            'data'       => $users,
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => ceil($total / $perPage),
        ];
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(int $id): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET is_active = NOT is_active WHERE id = ? AND role = 'user'"
        );
        return $stmt->execute([$id]);
    }

    /**
     * Count total users
     */
    public function countAll(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'user'");
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get recent users
     */
    public function getRecent(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE role = 'user' ORDER BY created_at DESC LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
