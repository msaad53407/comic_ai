<?php

namespace App\Models;

use App\Core\Database;

class ComicModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($userId, $prompt, $style, $layout, $scriptText = null, $panelCount = 0)
    {
        $sql = "INSERT INTO comics (user_id, prompt, style, layout, script_text, panel_count) VALUES (?, ?, ?, ?, ?, ?)";
        $this->db->query($sql, [$userId, $prompt, $style, $layout, $scriptText, $panelCount]);
        return $this->db->lastInsertId();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM comics ORDER BY created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getAllByUserId($userId)
    {
        $sql = "SELECT * FROM comics WHERE user_id = ? ORDER BY created_at DESC";
        return $this->db->query($sql, [$userId])->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM comics WHERE id = ?";
        return $this->db->query($sql, [$id])->fetch();
    }
}
