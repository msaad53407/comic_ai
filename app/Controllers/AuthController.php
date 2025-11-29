<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Core\AuthMiddleware;

class AuthController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        AuthMiddleware::guest();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->findByEmail($email);

            if ($user && $this->userModel->verifyPassword($password, $user['password_hash'])) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header('Location: /');
                exit;
            } else {
                $this->view('auth/login', ['error' => 'Invalid email or password'], 'auth');
                return;
            }
        }

        $this->view('auth/login', [], 'auth');
    }

    public function register()
    {
        AuthMiddleware::guest();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($password !== $confirmPassword) {
                $this->view('auth/register', ['error' => 'Passwords do not match'], 'auth');
                return;
            }

            if (strlen($password) < 6) {
                $this->view('auth/register', ['error' => 'Password must be at least 6 characters'], 'auth');
                return;
            }

            $userId = $this->userModel->create($username, $email, $password);

            if ($userId) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username;

                header('Location: /');
                exit;
            } else {
                $this->view('auth/register', ['error' => 'Username or Email already exists'], 'auth');
                return;
            }
        }

        $this->view('auth/register', [], 'auth');
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /login');
        exit;
    }
}
