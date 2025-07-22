<?php

// autoload
spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

use core\Router;

use app\controller\PageController;
use app\controller\UserController;
use app\controller\ConstellationController;
use app\controller\ApiController;
use app\controller\SkyGuessController;
use app\controller\ResourceController;

$router = new Router();

$router->get('/', [PageController::class, 'home']);
$router->get('/not-found', [PageController::class, 'notFound']);

$router->get('/login', [UserController::class, 'login']);
$router->get('/register', [UserController::class, 'register']);
$router->get('/profile', [UserController::class, 'profile']);
$router->get('/change-password', [UserController::class, 'changePassword']);
$router->get('/log-out', [UserController::class, 'logout']);

$router->get('/name-guess', [ConstellationController::class, 'nameGuess']);
$router->get('/browse', [ConstellationController::class, 'browse']);
$router->get('/constellation', [ConstellationController::class, 'constellation']);
$router->get('/api/check-constellation-name', [ConstellationController::class, 'checkConstellationName']);

$router->get('/api/increase-stat', [ApiController::class, 'increaseStat']);

$router->get('/sky-guess', [SkyGuessController::class, 'skyGuessPage']);
$router->get('/api/fetch-constellation-img-pair', [SkyGuessController::class, 'fetchConstellationImgPair']);
$router->get('/api/validate-sky-guess', [SkyGuessController::class, 'validateResult']);

$router->get('/resources/image', [ResourceController::class, 'serveImage']);

$router->dispatch();
