<?php

namespace app\model;

class SkyGuessPair {

    public int $id;
    public int $constellationId;
    public int $cleanPictureId;
    public int $linesPictureId;

    public function __construct(int $id, int $constellationId, int $cleanPictureId, int $linesPictureId) {
        $this->id = $id;
        $this->constellationId = $constellationId;
        $this->cleanPictureId = $cleanPictureId;
        $this->linesPictureId = $linesPictureId;
    }
}