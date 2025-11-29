<?php

namespace App\Models;

use App\Core\Database;

class PanelModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($comicId, $imagePath, $dialogue, $panelOrder)
    {
        $sql = "INSERT INTO panels (comic_id, image_path, dialogue, panel_order) VALUES (?, ?, ?, ?)";
        $this->db->query($sql, [$comicId, $imagePath, $dialogue, $panelOrder]);
        return $this->db->lastInsertId();
    }

    public function getByComicId($comicId)
    {
        $sql = "SELECT * FROM panels WHERE comic_id = ? ORDER BY panel_order ASC";
        return $this->db->query($sql, [$comicId])->fetchAll();
    }

    public function deleteByComicId($comicId)
    {
        $sql = "DELETE FROM panels WHERE comic_id = ?";
        return $this->db->query($sql, [$comicId]);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM panels WHERE id = ?";
        return $this->db->query($sql, [$id])->fetch();
    }
}
