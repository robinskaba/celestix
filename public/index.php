<?php

require __DIR__ . '/../core/Router.php';
require __DIR__ . '/../app/controller/PageController.php';
require __DIR__ . "/../app/controller/UserController.php";
require __DIR__ . "/../app/controller/ConstellationController.php";
require __DIR__ . "/../app/controller/ApiController.php";
require __DIR__ . "/../app/controller/SkyGuessController.php";
require __DIR__ . "/../app/controller/ResourceController.php";

spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

use core\Router;

$router = new Router();

$router->get('/', [PageController::class, 'home']);
$router->get('/not-found', [PageController::class, 'not_found']);

$router->get('/login', [UserController::class, 'login']);
$router->get('/register', [UserController::class, 'register']);
$router->get('/profile', [UserController::class, 'profile']);
$router->get('/change-password', [UserController::class, 'change_password']);
$router->get('/log-out', [UserController::class, 'logout']);

$router->get('/name-guess', [ConstellationController::class, 'name_guess']);
$router->get('/browse', [ConstellationController::class, 'browse']);
$router->get('/constellation', [ConstellationController::class, 'constellation']);
$router->get('/api/check-constellation-name', [ConstellationController::class, 'check_constellation_name']);

$router->get('/api/increase-stat', [ApiController::class, 'increaseStat']);

$router->get('/sky-guess', [SkyGuessController::class, 'skyGuessPage']);
$router->get('/api/fetch-constellation-img-pair', [SkyGuessController::class, 'fetchConstellationImgPair']);
$router->get('/api/validate-sky-guess', [SkyGuessController::class, 'validateResult']);

$router->get('/resources/image', [ResourceController::class, 'serveImage']);

$router->dispatch();
