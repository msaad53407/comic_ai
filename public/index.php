<?php

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;
use App\Core\Env;
use App\Controllers\ComicController;
use App\Controllers\AuthController;

// Load environment variables
Env::load(__DIR__ . '/../.env');

$router = new Router();

// Define Routes
$router->get('/', [ComicController::class, 'index']);
$router->post('/generate', [ComicController::class, 'generate']);
$router->post('/generate-script', [ComicController::class, 'generateScript']);
$router->post('/generate-panel', [ComicController::class, 'generatePanel']);
$router->post('/enhance-prompt', [ComicController::class, 'enhancePrompt']);
$router->get('/history', [ComicController::class, 'history']);
$router->get('/comic/:id', function ($id) {
    $controller = new ComicController();
    $controller->detail($id);
});

// Auth Routes
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Run Router
$router->resolve();
