<?php

namespace app\dao;

use app\model\User;
use app\model\Stat;
use PDO;

class UserDAO extends BaseDAO {

    private array $statsConfig;

    public function __construct() {
        parent::__construct();

        $this->statsConfig = json_decode(file_get_contents(__DIR__ . "/../../config/stats.json"), true);
    }

    public function findByUsername(string $username): ?User {
        return $this->findByField('username', $username);
    }

    public function findByEmail(string $email): ?User {
        return $this->findByField('email', $email);
    }

    private function findByField(string $field, string $value): ?User {
        $allowedFields = ['username', 'email'];
        if (!in_array($field, $allowedFields, true)) {
            return null;
        }

        $stmt = $this->db->prepare("SELECT id, username, email, profile_img, password_hash FROM site_user WHERE $field = ?");
        $stmt->execute([$value]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) return null;

        // fetch stats from Stat table
        $stmt = $this->db->prepare("SELECT * FROM stat WHERE username = ?");
        $stmt->execute([$row['username']]);
        $statsRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // build stats as a list of objects inside User
        $stats = [];
        foreach ($statsRows as $statRow) {
            $code = $statRow["str_code"];
            $actualName = $this->statsConfig[$code];
            $stats[$code] = new Stat($code, $actualName, $statRow["success"], $statRow["total"], $statRow["username"]);
        }

        return new User($row['username'], $row['email'], $row['profile_img'], $row["password_hash"], $stats);
    }

    public function create(string $username, string $email, ?string $profileImg, string $passwordHash) {
        $stmt = $this->db->prepare("INSERT INTO site_user (username, email, password_hash, privilege, profile_img) VALUES (?, ?, ?, 'user', ?)");
        $stmt->execute([$username, $email, $passwordHash, $profileImg]);

        // initiate rows in stat db for the user
        $stats = [];
        foreach ($this->statsConfig as $statConfig => $_) {
            $stmt = $this->db->prepare("INSERT INTO stat (str_code, username) VALUES (?, ?)");
            $stmt->execute([$statConfig, $username]);
        }
    }

    public function updateUser(User $user): bool {
        // only user properties - no stats

        $stmt = $this->db->prepare(
            "UPDATE site_user SET email = ?, password_hash = ?, profile_img = ? WHERE username = ?"
        );
        return $stmt->execute([
            $user->email,
            $user->passwordHash,
            $user->profileImg,
            $user->username
        ]);
    }

    public function updateStat(Stat $stat) {
        $stmt = $this->db->prepare("UPDATE stat SET success = ?, total = ? WHERE str_code = ? AND username = ?");
        return $stmt->execute([$stat->success, $stat->total, $stat->strCode, $stat->ownerUsername]);
    }
}
