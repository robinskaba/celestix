<?php

namespace app\model;

class User {
    public int $id;
    public string $username;
    public string $email;
    public ?int $profilePictureId;
    public string $passwordHash;
    public string $privilege;
    public array $stats;

    public function __construct(int $id, string $username, string $email, ?int $profilePictureId, string $passwordHash, string $privilege, array $stats) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->profilePictureId = $profilePictureId;
        $this->passwordHash = $passwordHash;
        $this->privilege = $privilege;
        $this->stats = $stats;
    }

    public function isAdmin() {
        return $this->privilege == "admin";
    }
}
