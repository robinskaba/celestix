<?php

use app\service\SessionService;
use app\service\UserService;

class ApiController {

    public function increaseStat() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: /not-found");
            exit;
        }

        // Get raw POST data and decode JSON payload
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        $statCode = trim($data["stat"] ?? '');
        $property = trim($data["property"] ?? '');

        if (empty($statCode) || empty($property)) {
            echo "Invalid request", 403;
            exit;
        }

        $user = SessionService::getUser();
        if ($user == null) {
            echo "No user logged in", 201;
            exit;
        }

        $userService = new UserService();
        $updateSuccess = $userService->increaseStat($user, $statCode, $property);

        if ($updateSuccess) {
            echo "Increased stat", 200;
            exit;
        } else {
            echo "Something went wrong", 500;
            exit;
        }
    }
}