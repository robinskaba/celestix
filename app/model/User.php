<?php

namespace app\model;

class User {
    public string $username;
    public string $email;
    public ?string $profileImg;
    public string $passwordHash;
    public array $stats;

    public function __construct(string $username, string $email, ?string $profileImg, string $passwordHash, array $stats) {
        $this->username = $username;
        $this->email = $email;
        $this->profileImg = $profileImg;
        $this->passwordHash = $passwordHash;
        $this->stats = $stats;
    }
}
