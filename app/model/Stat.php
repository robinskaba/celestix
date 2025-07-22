<?php

namespace app\model;

class Stat {
    public string $strCode;
    public string $name;
    public int $success;
    public int $total;
    public int $userId;

    public function __construct(string $strCode, string $name, int $success, int $total, int $userId) {
        $this->strCode = $strCode;
        $this->name = $name;
        $this->success = $success;
        $this->total = $total;
        $this->userId = $userId;
    }

    public function getRatio(): int {
        if ($this->total === 0) {
            return 0;
        }
        return (int) round(($this->success / $this->total) * 100);
    }

    public function increaseTotal() {
        $this->total += 1;
    }

    public function increaseSuccess() {
        if ($this->success >= $this->total) return;
        $this->success += 1;
    }
}