<?php

namespace app\model;

class Picture
{
    public int $id;
    public string $data;
    public string $mimeType;

    public function __construct(int $id, string $data, string $mimeType)
    {
        $this->id = $id;
        $this->data = $data;
        $this->mimeType = $mimeType;
    }
}
