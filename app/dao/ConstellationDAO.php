<?php

namespace app\dao;

use app\model\Constellation;

class ConstellationDAO extends BaseDAO {

    private function mapRowToConstellation(array $row): Constellation {
        return new Constellation(
            $row["id"] ?? 0,
            $row['name'] ?? '',
            $row['about'] ?? '',
            $row['story'] ?? '',
            $row['main_star'] ?? '',
            $row['hemisphere'] ?? '',
            $row['symbolism'] ?? '',
            $row["id"] ?? 0,
            $row["header_picture_id"] ?? 0
        );
    }

    public function getConstellationByName(string $name) {
        $stmt = $this->db->prepare("SELECT * FROM constellation WHERE name = ?");
        $stmt->execute([$name]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
            return $this->mapRowToConstellation($row);
        }
        return null;
    }

    public function getConstellationById(string $id) {
        $stmt = $this->db->prepare("SELECT * FROM constellation WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
            return $this->mapRowToConstellation($row);
        }
        return null;
    }

    public function getAll(): array {
        $stmt = $this->db->prepare("SELECT * FROM constellation");
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $constellations = [];
        foreach ($rows as $row) {
            $constellations[] = $this->mapRowToConstellation($row);
        }

        return $constellations;
    }

    public function updateConstellation(Constellation $constellation) {
        $stmt = $this->db->prepare(
            "UPDATE constellation SET name = ?, about = ?, story = ?, main_star = ?, hemisphere = ?, symbolism = ?, header_picture_id = ? WHERE id = ?"
        );
        $stmt->execute([
            $constellation->name,
            $constellation->about,
            $constellation->story,
            $constellation->mainStar,
            $constellation->hemisphere,
            $constellation->symbolism,
            $constellation->headerPictureId,
            $constellation->id
        ]);
    }
}
