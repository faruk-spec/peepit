<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class PagesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Auth and role checking will be done in each method
    }

    /**
     * List all pages
     */
    public function index()
    {
        require_role('manager');
        
        try {
            $pages = $this->db->fetchAll("
                SELECT p.*, u.name as created_by_name 
                FROM pages p 
                LEFT JOIN users u ON p.created_by = u.id 
                ORDER BY p.updated_at DESC
            ");
            
            $data = [
                'title' => 'Pages Management',
                'csrf_token' => $this->generateCSRF(),
                'pages' => $pages
            ];
            
            $this->view('admin/pages/index', $data);
        } catch (\Exception $e) {
            error_log('Pages list error: ' . $e->getMessage());
            flash('error', 'Failed to load pages. Please try again.');
            redirect('/admin/dashboard');
        }
    }

    /**
     * Show create page form
     */
    public function create()
    {
        require_role('manager');
        
        $data = [
            'title' => 'Create New Page',
            'csrf_token' => $this->generateCSRF()
        ];
        
        $this->view('admin/pages/create', $data);
    }

    /**
     * Store new page
     */
    public function store()
    {
        require_role('manager');
        $this->validateCSRF();

        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = $_POST['content'] ?? '';
        $meta_title = trim($_POST['meta_title'] ?? '');
        $meta_description = trim($_POST['meta_description'] ?? '');
        $status = $_POST['status'] ?? 'draft';

        // Validation
        if (empty($title)) {
            flash('error', 'Page title is required.');
            redirect('/admin/pages/create');
        }

        // Auto-generate slug if not provided
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        } else {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug), '-'));
        }

        // Check if slug already exists
        $existing = $this->db->fetchOne("SELECT id FROM pages WHERE slug = ?", [$slug]);
        if ($existing) {
            flash('error', 'A page with this slug already exists.');
            redirect('/admin/pages/create');
        }

        try {
            $userId = $_SESSION['user_id'] ?? null;
            
            $this->db->execute("
                INSERT INTO pages (title, slug, content, meta_title, meta_description, status, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ", [$title, $slug, $content, $meta_title, $meta_description, $status, $userId]);

            flash('success', 'Page created successfully!');
            redirect('/admin/pages');
        } catch (\Exception $e) {
            error_log('Page create error: ' . $e->getMessage());
            flash('error', 'Failed to create page. Please try again.');
            redirect('/admin/pages/create');
        }
    }

    /**
     * Show edit page form
     */
    public function edit($id)
    {
        require_role('manager');
        
        try {
            $page = $this->db->fetchOne("SELECT * FROM pages WHERE id = ?", [$id]);
            
            if (!$page) {
                flash('error', 'Page not found.');
                redirect('/admin/pages');
            }
            
            $data = [
                'title' => 'Edit Page',
                'csrf_token' => $this->generateCSRF(),
                'page' => $page
            ];
            
            $this->view('admin/pages/edit', $data);
        } catch (\Exception $e) {
            error_log('Page edit load error: ' . $e->getMessage());
            flash('error', 'Failed to load page. Please try again.');
            redirect('/admin/pages');
        }
    }

    /**
     * Update page
     */
    public function update($id)
    {
        require_role('manager');
        $this->validateCSRF();

        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = $_POST['content'] ?? '';
        $meta_title = trim($_POST['meta_title'] ?? '');
        $meta_description = trim($_POST['meta_description'] ?? '');
        $status = $_POST['status'] ?? 'draft';

        // Validation
        if (empty($title)) {
            flash('error', 'Page title is required.');
            redirect('/admin/pages/edit/' . $id);
        }

        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        } else {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug), '-'));
        }

        // Check if slug already exists (excluding current page)
        $existing = $this->db->fetchOne("SELECT id FROM pages WHERE slug = ? AND id != ?", [$slug, $id]);
        if ($existing) {
            flash('error', 'A page with this slug already exists.');
            redirect('/admin/pages/edit/' . $id);
        }

        try {
            $this->db->execute("
                UPDATE pages 
                SET title = ?, slug = ?, content = ?, meta_title = ?, meta_description = ?, status = ?
                WHERE id = ?
            ", [$title, $slug, $content, $meta_title, $meta_description, $status, $id]);

            flash('success', 'Page updated successfully!');
            redirect('/admin/pages');
        } catch (\Exception $e) {
            error_log('Page update error: ' . $e->getMessage());
            flash('error', 'Failed to update page. Please try again.');
            redirect('/admin/pages/edit/' . $id);
        }
    }

    /**
     * Delete page
     */
    public function delete()
    {
        require_role('manager');
        $this->validateCSRF();

        $id = $_POST['id'] ?? 0;

        try {
            $this->db->execute("DELETE FROM pages WHERE id = ?", [$id]);
            flash('success', 'Page deleted successfully!');
        } catch (\Exception $e) {
            error_log('Page delete error: ' . $e->getMessage());
            flash('error', 'Failed to delete page. Please try again.');
        }

        redirect('/admin/pages');
    }
}
