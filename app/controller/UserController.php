<?php

namespace app\controller;

use app\service\UserService;
use app\service\SessionService;
use app\util\PasswordValidator;
use app\util\FormValidator;

class UserController {
    
    public function login() {
        $title = 'Login | Celestix';
        $view = 'login';
        $css = ['/assets/css/form.css'];
        $scripts = [];

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = strtolower(trim($_POST['username'] ?? ''));
            $password = trim($_POST['password'] ?? '');

            $userService = new UserService();
            $user = $userService->getUserByUsername($username);

            if($user == null || !password_verify($password, $user->passwordHash)) {
                $errors["password"][] = "Incorrect password.";
            } else {
                $sessionService = new SessionService();
                $sessionService->login($user->username);
                header("Location: /profile?username=".$username);
                exit;
            }
        }

        require __DIR__ . '/../template.php';
    }

    public function register() {
        $title = 'Register | Celestix';
        $view = 'register';
        $css = ['/assets/css/form.css'];
        $scripts = [];

        $errors = [];

        $username = "";
        $email = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = strtolower(trim($_POST['username'] ?? ''));
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $passwordCheck = $_POST['password-check'] ?? '';

            $userService = new UserService();

            // username checks
            $errors["username"] = [];
            if(strlen($username) < 4) {
                $errors["username"][] = "Username must be at least 4 characters long.";
            }
            if (!ctype_alpha($username)) {
                $errors["username"][] = "Username must contain only alphabetic characters.";
            }
            if($userService->usernameExists($username)) {
                $errors["username"][] = "Username already exists.";
            }

            // email checks
            $errors["email"] = FormValidator::validateEmail($email);
            if($userService->emailExists($email)) {
                $errors["email"][] = "Email already exists.";
            }

            // validating profile picture
            $errors["profile-img"] = FormValidator::validateImageUpload($_FILES["profile-img"], 2);

            // validating passwords
            $errors["password"] = PasswordValidator::validatePassword($password);
            $errors["password-check"] = PasswordValidator::validatePasswordConfirmation($password, $passwordCheck);

            // validating if form can pass (no errors detected)
            $noErrors = FormValidator::isValid($errors);

            if ($noErrors) {
                $profilePictureFile = null;
                if (isset($_FILES["profile-img"]) && $_FILES["profile-img"]["error"] !== UPLOAD_ERR_NO_FILE) {
                    $profilePictureFile = $_FILES["profile-img"];
                }
                
                $userService->registerUser($username, $email, $password, $profilePictureFile);

                $sessionService = new SessionService();
                $sessionService->login($username);

                header("Location: /profile?username={$username}");
                exit;
            }
        }

        require __DIR__ . '/../template.php';
    }

    public function changePassword() {
        $title = 'Change password | Celestix';
        $view = 'change-password';
        $css = ['/assets/css/form.css'];
        $scripts = [];

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = SessionService::getUser();
            if ($user == null) {
                exit;
            }

            $oldPassword = trim($_POST["old-password"] ?? "");
            $newPassword = trim($_POST["new-password"] ?? "");
            $newPasswordCheck = trim($_POST["new-password-check"] ?? "");

            $errors["old-password"] = [];
            if(!password_verify($oldPassword, $user->passwordHash)) {
                $errors["old-password"][] = "Old password is incorrect.";
            }

            $errors["new-password"] = PasswordValidator::validatePassword($newPassword);
            $errors["new-password-check"] = PasswordValidator::validatePasswordConfirmation($newPassword, $newPasswordCheck);

            $validForm = FormValidator::isValid($errors);
            if ($validForm) {
                $userService = new UserService();
                $userService->changePassword($user, $newPassword);

                header("Location: /profile?username=".$user->username);
                exit;
            }
        }

        require __DIR__ . '/../template.php';
    }

    public function profile() {
        $targetUsername = isset($_GET["username"]) ? strtolower($_GET["username"]) : null;

        $userService = new UserService();
        $targetUser = $targetUsername ? $userService->getUserByUsername($targetUsername) : null;

        if ($targetUsername == null || $targetUser == null) {
            header("Location: /not-found");
            exit;
        }

        $title = $targetUsername."'s Profile | Celestix";
        $view = 'profile';
        $css = ['/assets/css/profile.css'];
        $scripts = [
        ];

        $ownsProfile = SessionService::isLoggedIn() && (SessionService::getUser()->username == $targetUsername);
        $stats = [];
        foreach ($targetUser->stats as $stat) {
            $stats[] = [
                "name" => $stat->name,
                "success" => $stat->success,
                "total" => $stat->total,
                "percentage" => $stat->getRatio()
            ];
        }

        require __DIR__ . '/../template.php';
    }

    public function logout() {
        SessionService::logout();
        header("Location: /");
        exit;
    }
}
