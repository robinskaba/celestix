<?php

use app\service\UserService;
use app\service\SessionService;
use app\util\PasswordValidator;
use app\util\FormValidator;

class UserController {
    public function login() {
        $title = 'Login | Stellara';
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
        $title = 'Register | Stellara';
        $view = 'register';
        $css = ['/assets/css/form.css'];
        $scripts = [];

        $errors = [];

        $username = "";
        $email = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = strtolower(trim($_POST['username'] ?? ''));
            $email = trim($_POST['email'] ?? '');
            $profile_img = $_POST["profile-img"] ?? NULL;
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
            $errors["email"] = [];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors["email"][] = "Invalid email format.";
            }
            if (empty($email)) {
                $errors["email"][] = "Email is required.";
            }
            if($userService->emailExists($email)) {
                $errors["email"][] = "Email already exists.";
            }

            // profile img checks
            $errors["profile-img"] = [];
            if (isset($_FILES["profile-img"]) && $_FILES["profile-img"]["error"] !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES["profile-img"];

                if ($file["error"] === UPLOAD_ERR_INI_SIZE || $file["error"] === UPLOAD_ERR_FORM_SIZE) {
                    $errors["profile-img"][] = "Image is too large (server limit exceeded).";
                } elseif ($file["error"] !== UPLOAD_ERR_OK) {
                    $errors["profile-img"][] = "Upload failed (error code: {$file["error"]}).";
                } elseif ($file["size"] > 2 * 1024 * 1024) {
                    $errors["profile-img"][] = "Profile image size must be less than 2MB.";
                }
            }

            // validating passwords
            $errors["password"] = PasswordValidator::validatePassword($password);
            $errors["password-check"] = PasswordValidator::validatePasswordConfirmation($password, $passwordCheck);

            // validating if form can pass (no errors detected)
            $noErrors = FormValidator::isValid($errors);

            if ($noErrors) {
                $imgFileName = null;
                if (isset($_FILES["profile-img"]) && $_FILES["profile-img"]["error"] !== UPLOAD_ERR_NO_FILE) {
                    $ext = pathinfo($_FILES["profile-img"]["name"], PATHINFO_EXTENSION);
                    $imgFileName = uniqid() . '.' . $ext;
                    $imgPath = __DIR__ . '/../../public/uploads/profile_imgs/' . $imgFileName;
                    move_uploaded_file($_FILES["profile-img"]["tmp_name"], $imgPath);
                }
                $userService->registerUser($username, $email, $password, $imgFileName);

                $sessionService = new SessionService();
                $sessionService->login($username);

                header("Location: /profile?username={$username}");
                exit;
            }
        }

        require __DIR__ . '/../template.php';
    }

    public function change_password() {
        $title = 'Change password | Stellara';
        $view = 'change-password';
        $css = ['/assets/css/form.css'];
        $scripts = [
        ];

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

        $title = $targetUsername."'s Profile | Stellara";
        $view = 'profile';
        $css = ['/assets/css/profile.css'];
        $scripts = [
        ];

        $profile_img_path = $targetUser->profileImg
            ? "/uploads/profile_imgs/" . $targetUser->profileImg
            : "/assets/img/nophoto.jpg";
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
