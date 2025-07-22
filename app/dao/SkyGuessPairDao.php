<?php

namespace app\dao;

use app\model\SkyGuessPair;

class SkyGuessPairDAO extends BaseDAO {

    public function getAllPairsByConstellationId(int $constellationId): array {
        $stmt = $this->db->prepare("SELECT * FROM sky_guess_pair WHERE constellation_id = ?");
        $stmt->execute([$constellationId]);
        $rows = $stmt->fetchAll();

        $results = [];
        foreach ($rows as $row) {
            $results[] = new SkyGuessPair($row["id"], $row["constellation_id"], $row["clean_picture_id"], $row["lines_picture_id"]);
        }

        return $results;
    }

}
