<?php

namespace app\service;

use app\model\User;
use app\dao\UserDAO;
use app\model\Stat;
use app\service\PictureService;

class UserService {
    private PictureService $pictureService;
    private UserDAO $userDAO;

    public function __construct() {
        $this->userDAO = new UserDao();
        $this->pictureService = new PictureService();
    }

    public function registerUser(string $username, string $email, string $password, ?array $profilePictureFile = null) {
        $username = strtolower($username);

        if ($this->userDAO->findByUsername($username) !== null) {
            return false;
        }
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $profilePictureId = $profilePictureFile != null ? $this->pictureService->savePictureFromUpload($profilePictureFile) : null;

        $this->userDAO->create($username, $email, $profilePictureId, $passwordHash);
    }

    public function getUserByUsername(string $username): ?User {
        $username = strtolower($username);
        
        return $this->userDAO->findByUsername($username);
    }

    public function getUserByEmail(string $email): ?User {
        $email = strtolower($email);

        return $this->userDAO->findByEmail($email);
    }

    public function usernameExists(string $username): bool {
        return $this->getUserByUsername($username) != null;
    }

    public function emailExists(string $email): bool {
        return $this->getUserByEmail($email) != null;
    }

    public function changePassword(User $user, string $newPassword): bool {
        $user->passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->userDAO->updateUser($user);
    }

    public function increaseStat(User $user, string $statCode, string $property): bool {
        $stat = $user->stats[$statCode];
        if ($property == "total") {
            $stat->increaseTotal();
        } elseif ($property == "success") {
            $stat->increaseSuccess();
        } else return false;

        return $this->userDAO->updateStat($stat);
    }
}
