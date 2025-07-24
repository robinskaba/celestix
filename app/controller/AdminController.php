<?php

namespace app\controller;

use app\util\FormValidator;
use app\service\ConstellationService;
use app\service\PictureService;
use app\service\SessionService;

class AdminController {

    public function updateConstellation() {
        $view = 'update-constellation';
        $css = [
            '/assets/css/form.css',
            '/assets/css/update-constellation.css'
        ];
        $scripts = [
            [
                'src' => '/js/update-constellation.js',
                'defer' => true
            ]
        ];

        $user = SessionService::getUser();
        if ($user == null || !$user->isAdmin()) {
            http_response_code(500);
            header("Location: /not-found");
            exit;
        }

        $constellationService = new ConstellationService();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = $_GET["id"] ?? -1;

            $constellation = $constellationService->getConstellationFromId($id);

            if ($constellation == null) {
                http_response_code(404);
                header("Location: /not-found");
                exit;
            }

            $name = $constellation->name;
            $title = "Update {$name} | Celestix";

            $mainStar = $constellation->mainStar;
            $hemisphere = $constellation->hemisphere;
            $symbolism = $constellation->symbolism;
            $about = $constellation->about;
            $story = $constellation->story;
            $headerPictureId = $constellation->headerPictureId;

            $pairs = [];
            foreach($constellationService->getAllGuessPairsForConstellation($constellation) as $pair) {
                $pairs[] = [
                    "id"=>$pair->id,
                    "clean"=>$pair->cleanPictureId,
                    "lines"=>$pair->linesPictureId
                ];
            }

        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST["id"] ?? -1;
            $name = trim($_POST["name"] ?? "");
            $mainStar = trim($_POST["main_star"] ?? "");
            $hemisphere = trim($_POST["hemisphere"] ?? "");
            $symbolism = trim($_POST["symbolism"] ?? "");
            $about = trim($_POST["about"] ?? "");
            $story = trim($_POST["story"] ?? "");

            function getFileUpload(string $key): ?array {
                if (isset($_FILES[$key]) && $_FILES[$key]["error"] !== UPLOAD_ERR_NO_FILE) {
                    return $_FILES[$key];
                }
                return null;
            }
            
            // sorting file inputs to old and new
            $oldInputs = [];
            $newInputs = [];
            foreach ($_FILES as $key => $_) {
                if (stripos($key, 'old') !== false) {
                    $oldInputs[] = $key;
                } elseif (stripos($key, "new") !== false) {
                    $newInputs[] = $key;
                }
            }

            $pictureService = new PictureService();

            // updating existing pictures if any upload overwrites them
            foreach($oldInputs as $oldInput) {
                $file = getFileUpload($oldInput);
                preg_match('/^old#(\d+)$/', $oldInput, $matches);
                $pictureId = isset($matches[1]) ? (int)$matches[1] : null;
                if ($file) {
                    $pictureService->overwritePictureWithUpload($pictureId, $file);
                }
            }

            $constellation = $constellationService->getConstellationFromId($id);

            // calculating keys to pairs
            $pairs = [];
            foreach ($newInputs as $_ => $key) {
                if (preg_match('/^new-(clean|lines)#(\d+)$/', $key, $matches)) {
                    $type = $matches[1];
                    $pairNumber = $matches[2];
                    if (!isset($pairs[$pairNumber])) {
                        $pairs[$pairNumber] = [];
                    }
                    $pairs[$pairNumber][$type] = $key;
                }
            }

            // creating new pictures and guess pairs from uplaoded files
            foreach ($pairs as $pairNumber => $pair) {
                $cleanKey = isset($pair['clean']) ? $pair['clean'] : '';
                $linesKey = isset($pair['lines']) ? $pair['lines'] : '';
            
                $cleanFile = getFileUpload($cleanKey);
                $linesFile = getFileUpload($linesKey);

                // enforce pairs
                if ($cleanFile == null || $linesFile == null) continue;
            
                $cleanId = $cleanFile ? $pictureService->savePictureFromUpload($cleanFile) : null;
                $linesId = $linesFile ? $pictureService->savePictureFromUpload($linesFile) : null;
            
                $constellationService->addGuessPairToConstellation($constellation, $cleanId, $linesId);
            }

            // updating constellation object
            $constellation->name = $name;
            $constellation->mainStar = $mainStar;
            $constellation->hemisphere = $hemisphere;
            $constellation->symbolism = $symbolism;
            $constellation->about = $about;
            $constellation->story = $story;

            $constellationService->updateConstellation($constellation);

            // response
            http_response_code(200);
            header("Location: /constellation?id={$constellation->id}");
            exit;
        }

        require __DIR__ . '/../template.php';
    }
}