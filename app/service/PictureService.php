<?php

namespace app\service;

use app\model\Picture;
use app\dao\PictureDAO;

class PictureService {
    private PictureDAO $pictureDAO;

    public function __construct() {
        $this->pictureDAO = new PictureDAO();
    }

    public function getPictureById(int $id): ?Picture {
        return $this->pictureDAO->findById($id);
    }

    public function savePictureFromUpload(array $file): ?int {
        $imageData = file_get_contents($file["tmp_name"]);
        $mimeType = $file["type"];

        return $this->pictureDAO->savePictureBinary($imageData, $mimeType);
    }
}
