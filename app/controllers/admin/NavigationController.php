<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class NavigationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * List all navigation items
     */
    public function index()
    {
        require_role('manager');
        
        try {
            // Get all navigation items with parent information
            $items = $this->db->fetchAll("
                SELECT n.*, p.label as parent_label 
                FROM navigation_items n 
                LEFT JOIN navigation_items p ON n.parent_id = p.id 
                ORDER BY n.parent_id ASC, n.order ASC
            ");
            
            $data = [
                'title' => 'Navigation Management',
                'csrf_token' => $this->generateCSRF(),
                'items' => $items
            ];
            
            $this->view('admin/navigation/index', $data);
        } catch (\Exception $e) {
            error_log('Navigation list error: ' . $e->getMessage());
            flash('error', 'Failed to load navigation items. Please try again.');
            redirect('/admin/dashboard');
        }
    }

    /**
     * Show create navigation item form
     */
    public function create()
    {
        require_role('manager');
        
        try {
            // Get potential parent items (only top-level for now)
            $parentItems = $this->db->fetchAll("
                SELECT id, label 
                FROM navigation_items 
                WHERE parent_id IS NULL AND status = 'active'
                ORDER BY label
            ");
            
            $data = [
                'title' => 'Create Navigation Item',
                'csrf_token' => $this->generateCSRF(),
                'parentItems' => $parentItems
            ];
            
            $this->view('admin/navigation/create', $data);
        } catch (\Exception $e) {
            error_log('Navigation create form error: ' . $e->getMessage());
            flash('error', 'Failed to load form. Please try again.');
            redirect('/admin/navigation');
        }
    }

    /**
     * Store new navigation item
     */
    public function store()
    {
        require_role('manager');
        $this->validateCSRF();

        $label = trim($_POST['label'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $parent_id = !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null;
        $icon = trim($_POST['icon'] ?? '');
        $target = $_POST['target'] ?? '_self';
        $visible_to = $_POST['visible_to'] ?? 'all';
        $order = intval($_POST['order'] ?? 0);
        $status = $_POST['status'] ?? 'active';

        // Validation
        if (empty($label)) {
            flash('error', 'Label is required.');
            redirect('/admin/navigation/create');
        }

        if (empty($url)) {
            flash('error', 'URL is required.');
            redirect('/admin/navigation/create');
        }

        try {
            $this->db->query("
                INSERT INTO navigation_items (parent_id, label, url, target, icon, visible_to, `order`, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ", [$parent_id, $label, $url, $target, $icon, $visible_to, $order, $status]);

            flash('success', 'Navigation item created successfully!');
            redirect('/admin/navigation');
        } catch (\Exception $e) {
            error_log('Navigation create error: ' . $e->getMessage());
            flash('error', 'Failed to create navigation item. Please try again.');
            redirect('/admin/navigation/create');
        }
    }

    /**
     * Show edit navigation item form
     */
    public function edit($id)
    {
        require_role('manager');
        
        try {
            $item = $this->db->fetch("SELECT * FROM navigation_items WHERE id = ?", [$id]);
            
            if (!$item) {
                flash('error', 'Navigation item not found.');
                redirect('/admin/navigation');
            }
            
            // Get potential parent items (exclude self and descendants)
            $parentItems = $this->db->fetchAll("
                SELECT id, label 
                FROM navigation_items 
                WHERE id != ? AND parent_id IS NULL AND status = 'active'
                ORDER BY label
            ", [$id]);
            
            $data = [
                'title' => 'Edit Navigation Item',
                'csrf_token' => $this->generateCSRF(),
                'item' => $item,
                'parentItems' => $parentItems
            ];
            
            $this->view('admin/navigation/edit', $data);
        } catch (\Exception $e) {
            error_log('Navigation edit load error: ' . $e->getMessage());
            flash('error', 'Failed to load navigation item. Please try again.');
            redirect('/admin/navigation');
        }
    }

    /**
     * Update navigation item
     */
    public function update($id)
    {
        require_role('manager');
        $this->validateCSRF();

        $label = trim($_POST['label'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $parent_id = !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null;
        $icon = trim($_POST['icon'] ?? '');
        $target = $_POST['target'] ?? '_self';
        $visible_to = $_POST['visible_to'] ?? 'all';
        $order = intval($_POST['order'] ?? 0);
        $status = $_POST['status'] ?? 'active';

        // Validation
        if (empty($label)) {
            flash('error', 'Label is required.');
            redirect('/admin/navigation/edit/' . $id);
        }

        if (empty($url)) {
            flash('error', 'URL is required.');
            redirect('/admin/navigation/edit/' . $id);
        }

        // Prevent circular reference
        if ($parent_id == $id) {
            flash('error', 'An item cannot be its own parent.');
            redirect('/admin/navigation/edit/' . $id);
        }

        try {
            $this->db->query("
                UPDATE navigation_items 
                SET parent_id = ?, label = ?, url = ?, target = ?, icon = ?, visible_to = ?, `order` = ?, status = ?
                WHERE id = ?
            ", [$parent_id, $label, $url, $target, $icon, $visible_to, $order, $status, $id]);

            flash('success', 'Navigation item updated successfully!');
            redirect('/admin/navigation');
        } catch (\Exception $e) {
            error_log('Navigation update error: ' . $e->getMessage());
            flash('error', 'Failed to update navigation item. Please try again.');
            redirect('/admin/navigation/edit/' . $id);
        }
    }

    /**
     * Delete navigation item
     */
    public function delete()
    {
        require_role('manager');
        $this->validateCSRF();

        $id = $_POST['id'] ?? 0;

        try {
            // Check if item has children
            $children = $this->db->fetch("SELECT COUNT(*) as count FROM navigation_items WHERE parent_id = ?", [$id]);
            
            if ($children['count'] > 0) {
                flash('error', 'Cannot delete navigation item with sub-items. Delete children first.');
                redirect('/admin/navigation');
            }

            $this->db->query("DELETE FROM navigation_items WHERE id = ?", [$id]);
            flash('success', 'Navigation item deleted successfully!');
        } catch (\Exception $e) {
            error_log('Navigation delete error: ' . $e->getMessage());
            flash('error', 'Failed to delete navigation item. Please try again.');
        }

        redirect('/admin/navigation');
    }

    /**
     * Reorder navigation items via AJAX
     */
    public function reorder()
    {
        require_role('manager');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Invalid request method'], 405);
        }

        try {
            $items = json_decode(file_get_contents('php://input'), true);
            
            if (!$items || !is_array($items)) {
                $this->json(['error' => 'Invalid data'], 400);
            }

            foreach ($items as $item) {
                $id = intval($item['id']);
                $order = intval($item['order']);
                
                $this->db->query("UPDATE navigation_items SET `order` = ? WHERE id = ?", [$order, $id]);
            }

            $this->json(['success' => true, 'message' => 'Navigation order updated successfully']);
        } catch (\Exception $e) {
            error_log('Navigation reorder error: ' . $e->getMessage());
            $this->json(['error' => 'Failed to reorder navigation items'], 500);
        }
    }
}
