<?php

namespace app\controller;

use app\service\PictureService;

class ResourceController {

    public function serveImage() {
        $id = $_GET['id'] ?? 0;
        $width = isset($_GET['width']) ? intval($_GET['width']) : null;

        $pictureService = new PictureService();
        $picture = $pictureService->getPictureById($id);

        if ($picture != null) {
            $mimeType = $picture->mimeType;
            $imageData = $picture->data;

            if ($width !== null && $width > 0) {
                $tmpInput = tempnam(sys_get_temp_dir(), 'imgin_');
                file_put_contents($tmpInput, $imageData);

                list($original_width, $original_height, $image_type) = getimagesize($tmpInput);
                $aspect_ratio = $original_width / $original_height;
                $new_width = $width;
                $new_height = round($new_width / $aspect_ratio);

                $resized_image = imagecreatetruecolor($new_width, $new_height);
                switch ($image_type) {
                    case IMAGETYPE_JPEG:
                        $original_image = imagecreatefromjpeg($tmpInput);
                        break;
                    case IMAGETYPE_PNG:
                        $original_image = imagecreatefrompng($tmpInput);

                        imagealphablending($resized_image, false);
                        imagesavealpha($resized_image, true);
                        break;
                    default:
                        unlink($tmpInput);
                        http_response_code(415);
                        return;
                }
                imagecopyresampled($resized_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

                header("Content-Type: " . $mimeType);
                switch ($image_type) {
                    case IMAGETYPE_JPEG:
                        imagejpeg($resized_image);
                        break;
                    case IMAGETYPE_PNG:
                        imagepng($resized_image);
                        break;
                }

                imagedestroy($resized_image);
                imagedestroy($original_image);
                unlink($tmpInput);
            } else {
                header("Content-Type: " . $mimeType);
                echo $imageData;
            }
        } else {
            http_response_code(404);
        }
    }
}