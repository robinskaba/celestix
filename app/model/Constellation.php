<?php

namespace App\Model;

class Constellation
{
    public string $name;
    public string $story;
    public string $mainStar;
    public string $hemisphere;
    public string $symbolism;
    public int $index;

    public function __construct(string $name, string $story, string $mainStar, string $hemisphere, string $symbolism, int $index) {
        $this->name = $name;
        $this->story = $story;
        $this->mainStar = $mainStar;
        $this->hemisphere = $hemisphere;
        $this->symbolism = $symbolism;
        $this->index = $index;
    }

    public function getLowerCaseName(): string {
        return str_replace(' ', '_', strtolower($this->name));
    }

    public function getHeaderImgPath(): string {
        $lowercase_name = $this->getLowerCaseName();
        return "/assets/img/constellation-headers/{$lowercase_name}.png";
    }

    public function getGuessPair(): array
    {
        $lowercase_name = $this->getLowerCaseName();
        $webPath = "/assets/img/guess/{$lowercase_name}/";
        $fsPath = $_SERVER['DOCUMENT_ROOT'] . $webPath;

        if (!is_dir($fsPath)) {
            return null;
        }

        $pairs = [];
        foreach (glob($fsPath . '*_clean.png') as $cleanPath) {
            $num = basename($cleanPath, '_clean.png');
            $linesPath = $fsPath . $num . '_lines.png';
            if (file_exists($linesPath)) {
                $pairs[] = [
                    'clean' => $webPath . "{$num}_clean.png",
                    'lines' => $webPath . "{$num}_lines.png"
                ];
            }
        }

        return $pairs[array_rand($pairs)];
    }
}