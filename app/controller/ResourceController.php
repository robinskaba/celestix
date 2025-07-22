<?php

namespace app\controller;

use app\service\PictureService;

class ResourceController {

    public function serveImage() {
        $id = $_GET['id'] ?? 0;

        $pictureService = new PictureService();
        $picture = $pictureService->getPictureById($id);
    
        if ($picture != null) {
            header("Content-Type: " . $picture->mimeType);
            echo $picture->data;
        } else {
            http_response_code(404);
        }
    }
}