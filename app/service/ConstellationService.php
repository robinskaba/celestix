<?php

namespace app\service;

use app\model\Constellation;
use app\dao\ConstellationDAO;

class ConstellationService {
    private ConstellationDAO $constellationDAO;

    public function __construct() {
        $this->constellationDAO = new ConstellationDAO();
    }

    public function getAll(): array {
        return $this->constellationDAO->getAll();
    }

    public function getConstellation(string $lowercaseName): ?Constellation {
        $formattedName = str_replace('_', ' ', $lowercaseName);
        $formattedName = ucwords($formattedName);
        
        return $this->constellationDAO->getConstellationByName($formattedName);
    }
}
