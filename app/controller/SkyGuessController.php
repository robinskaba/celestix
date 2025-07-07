<?php

use app\service\ConstellationService;
use app\service\SessionService;

class SkyGuessController {

    // sky guess page
    public function skyGuessPage() {
        $title = 'Sky Guess | Celestix';
        $view = 'sky-guess';
        $css = ['/assets/css/sky-guess.css'];
        $scripts = [
            [
                'src' => '/js/sky-guesser.js',
                'defer' => true
            ]
        ];

        require __DIR__ . '/../template.php';
    }

    // api
    public function fetchConstellationImgPair() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            exit;
        }

        $constellationService = new ConstellationService();
        $constellations = $constellationService->getAll();
        if (empty($constellations)) {
            http_response_code(404);
            echo json_encode(['error' => 'No constellations found.']);
            exit;
        }

        $constellation = $constellations[array_rand($constellations)];
        $pair = $constellation->getGuessPair();

        header('Content-Type: application/json');

        $data = [
            "name"=>$constellation->name,
            "clean"=>$pair["clean"],
            "lines"=>$pair["lines"]
        ];

        SessionService::setField("sky-guess-solution", $constellation->name);

        echo json_encode($data);
        exit;
    }

    public function validateResult() {
        // get only
        if($_SERVER["REQUEST_METHOD"] !== "GET") {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            exit;
        }

        $guessedName = $_GET["name"] ?? '';
        $constellationName = SessionService::getField("sky-guess-solution");
        if ($constellationName === null) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Session expirated',
                'raw' => $this->generated
            ]);
            exit;
        }

        $formattedTargetName = str_replace(' ', '_', strtolower($constellationName));
        $formattedGuessedName = str_replace(' ', '_', strtolower($guessedName));

        echo json_encode(['matching' => $formattedGuessedName === $formattedTargetName]);
        exit;
    }
}