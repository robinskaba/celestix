<?php

namespace app\model;

class Constellation
{
    public int $id;
    public string $name;
    public string $story;
    public string $mainStar;
    public string $hemisphere;
    public string $symbolism;
    public int $index;
    public int $headerPictureId;

    public function __construct(int $id, string $name, string $story, string $mainStar, string $hemisphere, string $symbolism, int $index, int $headerPictureId) {
        $this->id = $id;
        $this->name = $name;
        $this->story = $story;
        $this->mainStar = $mainStar;
        $this->hemisphere = $hemisphere;
        $this->symbolism = $symbolism;
        $this->index = $index;
        $this->headerPictureId = $headerPictureId;
    }

    public function getLowerCaseName(): string {
        return str_replace(' ', '_', strtolower($this->name));
    }
}