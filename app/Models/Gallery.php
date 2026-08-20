<?php
namespace App\Models;

use PDO;

class Gallery {
    private $db;
    
    public function __construct() {
        $this->db = \App\Core\Database::getInstance()->getConnection();
    }
    
    /**
     * Get all gallery images
     */
    public function getAll($enabledOnly = false) {
        $sql = "SELECT * FROM gallery";
        if ($enabledOnly) {
            $sql .= " WHERE is_enabled = 1";
        }
        $sql .= " ORDER BY priority ASC, created_at DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get single gallery image by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create new gallery image
     */
    public function create($data) {
        $sql = "INSERT INTO gallery (image_path, caption, description, priority, is_enabled) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['image_path'],
            $data['caption'] ?? null,
            $data['description'] ?? null,
            $data['priority'] ?? 0,
            isset($data['is_enabled']) ? (int)$data['is_enabled'] : 1
        ]);
    }
    
    /**
     * Update gallery image
     */
    public function update($id, $data) {
        $sql = "UPDATE gallery SET 
                caption = ?, 
                description = ?, 
                priority = ?, 
                is_enabled = ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['caption'] ?? null,
            $data['description'] ?? null,
            $data['priority'] ?? 0,
            isset($data['is_enabled']) ? (int)$data['is_enabled'] : 1,
            $id
        ]);
    }
    
    /**
     * Delete gallery image
     */
    public function delete($id) {
        // Get image path before deleting
        $image = $this->getById($id);
        
        $stmt = $this->db->prepare("DELETE FROM gallery WHERE id = ?");
        $result = $stmt->execute([$id]);
        
        // Delete physical file if deletion was successful
        if ($result && $image) {
            $filePath = __DIR__ . '/../../public/uploads/gallery/' . $image['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        return $result;
    }
    
    /**
     * Toggle enable/disable status
     */
    public function toggleStatus($id) {
        $sql = "UPDATE gallery SET is_enabled = NOT is_enabled WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Get maximum priority value
     */
    public function getMaxPriority() {
        $stmt = $this->db->query("SELECT COALESCE(MAX(priority), 0) as max_priority FROM gallery");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['max_priority'];
    }
}
