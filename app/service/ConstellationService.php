<?php

namespace app\service;

use app\model\Constellation;
use app\model\SkyGuessPair;
use app\dao\ConstellationDAO;
use app\dao\SkyGuessPairDAO;

class ConstellationService {
    private ConstellationDAO $constellationDAO;
    private SkyGuessPairDAO $skyGuessPairDAO;

    public function __construct() {
        $this->constellationDAO = new ConstellationDAO();
        $this->skyGuessPairDAO = new SkyGuessPairDAO();
    }

    public function getAll(): array {
        return $this->constellationDAO->getAll();
    }

    public function getConstellation(string $lowercaseName): ?Constellation {
        $formattedName = str_replace('_', ' ', $lowercaseName);
        $formattedName = ucwords($formattedName);
        
        return $this->constellationDAO->getConstellationByName($formattedName);
    }

    public function getGuessPairForConstellation(Constellation $constellation): ?SkyGuessPair {
        $allPairs = $this->skyGuessPairDAO->getAllPairsByConstellationId($constellation->id);
        if ($allPairs == null || empty($allPairs)) {
            return null;
        }

        return $allPairs[array_rand($allPairs)];
    }
}
