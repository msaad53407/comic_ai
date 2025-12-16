<?php

namespace App\Models;

use App\Core\Database;

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($username, $email, $password)
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";

        try {
            $this->db->query($sql, [$username, $email, $passwordHash]);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            // Handle duplicate entry error
            if ($e->getCode() == 23000) {
                return false;
            }
            throw $e;
        }
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->db->query($sql, [$email])->fetch();
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        return $this->db->query($sql, [$id])->fetch();
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
