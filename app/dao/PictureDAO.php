<?php

namespace app\dao;

use app\model\Picture;
use PDO;

class PictureDAO extends BaseDAO {

    public function findById(int $id): ?Picture {
        $stmt = $this->db->prepare("SELECT data, mime_type FROM picture WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        $parsedData = stream_get_contents($row["data"]);
        $picture = new Picture($id, $parsedData, $row["mime_type"]);
        return $picture;
    }

    public function savePicture(string $imageData, string $mimeType): ?int {
        $stmt = $this->db->prepare("INSERT INTO picture (data, mime_type) VALUES (?, ?) RETURNING id");
        $stmt->bindParam(1, $imageData, PDO::PARAM_LOB);
        $stmt->bindParam(2, $mimeType);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function overwritePicture(int $id, string $imageData, string $mimeType) {
        $stmt = $this->db->prepare("UPDATE picture SET data = ?, mime_type = ? WHERE id = ?");
        $stmt->bindParam(1, $imageData, PDO::PARAM_LOB);
        $stmt->bindParam(2, $mimeType);
        $stmt->bindParam(3, $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
