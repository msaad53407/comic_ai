<?php

namespace App\Controllers;

class BaseController
{
    protected function view($view, $data = [], $useLayout = true)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        extract($data);

        if ($useLayout === true) {
            $viewPath = __DIR__ . '/../../views/' . $view . '.php';
            require_once __DIR__ . '/../../views/layouts/main.php';
        } elseif ($useLayout === 'auth') {
            $viewPath = __DIR__ . '/../../views/' . $view . '.php';
            require_once __DIR__ . '/../../views/layouts/auth.php';
        } else {
            require_once __DIR__ . '/../../views/' . $view . '.php';
        }
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
