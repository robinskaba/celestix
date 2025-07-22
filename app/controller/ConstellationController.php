<?php

use app\model\Constellation;
use app\service\ConstellationService;
use app\service\SessionService;

class ConstellationController {

    private function formatConstellation(Constellation $constellation): array {
        return [
            "name" => $constellation->name,
            "lowercaseName" => $constellation->getLowerCaseName(),
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


    public function sky_guess() {
        $title = 'Guess the constellation | Celestix';
        $view = 'sky-guess';
        $css = ['/assets/css/sky-guess.css'];
        $scripts = [
        ];
        
        require __DIR__ . '/../template.php';
    }

    public function name_guess() {
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

        $lowercaseName = $_GET["name"] ?? '';
        $objConst = $constellationService->getConstellation($lowercaseName);

        if ($objConst == null) {
            header("Location: /not-found");
            exit;
        }

        $title = "{$objConst->name} | Celestix";
        $view = 'constellation';
        $css = ['/assets/css/constellation.css'];
        $scripts = [];

        $constellation = $this->formatConstellation($objConst);

        require __DIR__ . '/../template.php';
    }

    // API FOR JS

    public function check_constellation_name() {
        header('Content-Type: application/json');

        $inputName = $_GET['input_name'] ?? '';
        $lowercaseName = strtolower(trim($inputName));
        if($lowercaseName == "bootes") $lowercaseName = "boötes";

        $constellationService = new ConstellationService();
        $objConst = $constellationService->getConstellation($lowercaseName);

        if ($objConst !== null) {
            echo json_encode([
                'name' => $objConst->name,
                'index' => $objConst->index
            ]);
        } else {
            echo json_encode(null);
        }
        exit;
    }

    public function stat_game_initiated() {
        $user = SessionService::getUser();
        if ($user == null) return;

        $userService = new UserService();
        $userService->increaseStatTotal($user, "name-guess");
    }

    public function stat_game_completed() {
        $user = SessionService::getUser();
        if ($user == null) return;

        $userService = new UserService();
        $userService->increaseStatSuccess($user, "name-guess");
    }
}