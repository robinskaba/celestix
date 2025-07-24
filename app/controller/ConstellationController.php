<?php

namespace app\controller;

use app\model\Constellation;
use app\service\ConstellationService;
use app\service\SessionService;

class ConstellationController {

    private function formatConstellation(Constellation $constellation): array {
        return [
            "id" => $constellation->id,
            "name" => $constellation->name,
            "lowercaseName" => $constellation->getLowerCaseName(),
            "about" => $constellation->about,
            "story" => $constellation->story,
            "mainStar" => $constellation->mainStar,
            "hemisphere" => $constellation->hemisphere,
            "symbolism" => $constellation->symbolism,
            "headerPictureSrc" => "/resources/image?id=".$constellation->headerPictureId
        ];
    }

    private function formatConstellations(): array {
        $constellationService = new ConstellationService();
        $objConstellations = $constellationService->getAll();

        $formattedConstellations = [];
        foreach ($objConstellations as $objConst) {
            $formattedConstellations[] = $this->formatConstellation($objConst);
        };

        return $formattedConstellations;
    }


    public function skyGuess() {
        $title = 'Guess the constellation | Celestix';
        $view = 'sky-guess';
        $css = ['/assets/css/sky-guess.css'];
        $scripts = [
        ];
        
        require __DIR__ . '/../template.php';
    }

    public function nameGuess() {
        $title = 'Name Guess | Celestix';
        $view = 'name-guess';
        $css = ['/assets/css/name-guess.css'];
        $scripts = [
            [
                'src' => '/js/name-guesser.js',
                'defer' => true
            ]
        ];

        require __DIR__ . '/../template.php';
    }

    public function browse() {
        $title = 'Constellations | Celestix';
        $view = 'browse';
        $css = ['/assets/css/browse.css'];
        $scripts = [];

        $constellations = $this->formatConstellations();

        require __DIR__ . '/../template.php';
    }

    public function constellation() {
        $constellationService = new ConstellationService();

        $id = $_GET["id"] ?? '';
        $objConst = $constellationService->getConstellationFromId($id);

        if ($objConst == null) {
            header("Location: /not-found");
            exit;
        }

        $title = "{$objConst->name} | Celestix";
        $view = 'constellation';
        $css = ['/assets/css/constellation.css'];
        $scripts = [];

        $constellation = $this->formatConstellation($objConst);

        $user = SessionService::getUser();
        $isAdmin = $user ? $user->isAdmin() : false; 

        require __DIR__ . '/../template.php';
    }

    // API FOR JS

    public function checkConstellationName() {
        header('Content-Type: application/json');

        $inputName = $_GET['input_name'] ?? '';
        $lowercaseName = strtolower(trim($inputName));
        if($lowercaseName == "bootes") $lowercaseName = "boötes";

        $constellationService = new ConstellationService();
        $objConst = $constellationService->getConstellation($lowercaseName);

        if ($objConst !== null) {
            echo json_encode([
                'name' => $objConst->name,
                'index' => $objConst->index // position in the constellation order
            ]);
        } else {
            echo json_encode(null);
        }
        exit;
    }
}