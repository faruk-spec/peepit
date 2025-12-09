<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class TemplateController extends Controller
{
    public function index()
    {
        require_role('manager');

        try {
            $templates = $this->db->fetchAll("SELECT * FROM label_templates ORDER BY name ASC");
            
            $csrfToken = $this->generateCSRF();
            $this->view('admin/templates/index', [
                'csrf_token' => $csrfToken,
                'templates' => $templates
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching templates: ' . $e->getMessage());
            flash('error', 'Unable to load label templates.');
            $this->view('admin/templates/index', ['csrf_token' => $this->generateCSRF(), 'templates' => []]);
        }
    }

    public function create()
    {
        require_role('manager');

        $csrfToken = $this->generateCSRF();
        $this->view('admin/templates/create', ['csrf_token' => $csrfToken]);
    }

    public function store()
    {
        require_role('manager');
        $this->validateCSRF();

        $name = sanitize($_POST['name'] ?? '');
        $category = sanitize($_POST['category'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');

        if (empty($name)) {
            flash('error', 'Template name is required');
            set_old($_POST);
            $this->redirect(url('admin/templates/create'));
        }

        // Handle image upload
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['image'], __DIR__ . '/../../../public/uploads/templates');
            if (isset($upload['success'])) {
                $image = $upload['filename'];
            } else {
                flash('error', $upload['error']);
                set_old($_POST);
                $this->redirect(url('admin/templates/create'));
            }
        }

        try {
            $this->db->query(
                "INSERT INTO label_templates (name, category, description, image, status) VALUES (?, ?, ?, ?, ?)",
                [$name, $category, $description, $image, $status]
            );

            flash('success', 'Label template created successfully');
            clear_old();
            $this->redirect(url('admin/templates'));
        } catch (\Exception $e) {
            error_log('Error creating template: ' . $e->getMessage());
            flash('error', 'Failed to create label template');
            set_old($_POST);
            $this->redirect(url('admin/templates/create'));
        }
    }

    public function edit($id)
    {
        require_role('manager');

        try {
            $template = $this->db->fetch("SELECT * FROM label_templates WHERE id = ?", [$id]);

            if (!$template) {
                flash('error', 'Template not found');
                $this->redirect(url('admin/templates'));
            }

            $csrfToken = $this->generateCSRF();
            $this->view('admin/templates/edit', [
                'csrf_token' => $csrfToken,
                'template' => $template
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching template: ' . $e->getMessage());
            flash('error', 'Unable to load template');
            $this->redirect(url('admin/templates'));
        }
    }

    public function update($id)
    {
        require_role('manager');
        $this->validateCSRF();

        $template = $this->db->fetch("SELECT * FROM label_templates WHERE id = ?", [$id]);
        
        if (!$template) {
            flash('error', 'Template not found');
            $this->redirect(url('admin/templates'));
        }

        $name = sanitize($_POST['name'] ?? '');
        $category = sanitize($_POST['category'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');

        if (empty($name)) {
            flash('error', 'Template name is required');
            $this->redirect(url("admin/templates/edit/{$id}"));
        }

        $image = $template['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['image'], __DIR__ . '/../../../public/uploads/templates');
            if (isset($upload['success'])) {
                // Delete old image
                if ($image && file_exists(__DIR__ . "/../../../public/uploads/templates/{$image}")) {
                    unlink(__DIR__ . "/../../../public/uploads/templates/{$image}");
                }
                $image = $upload['filename'];
            }
        }

        try {
            $this->db->query(
                "UPDATE label_templates SET name = ?, category = ?, description = ?, image = ?, status = ? WHERE id = ?",
                [$name, $category, $description, $image, $status, $id]
            );

            flash('success', 'Label template updated successfully');
            $this->redirect(url('admin/templates'));
        } catch (\Exception $e) {
            error_log('Error updating template: ' . $e->getMessage());
            flash('error', 'Failed to update label template');
            $this->redirect(url("admin/templates/edit/{$id}"));
        }
    }

    public function delete($id)
    {
        require_role('manager');
        $this->validateCSRF();

        try {
            $template = $this->db->fetch("SELECT * FROM label_templates WHERE id = ?", [$id]);
            
            if ($template && $template['image']) {
                $imagePath = __DIR__ . "/../../../public/uploads/templates/{$template['image']}";
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $this->db->query("DELETE FROM label_templates WHERE id = ?", [$id]);
            flash('success', 'Label template deleted successfully');
        } catch (\Exception $e) {
            error_log('Error deleting template: ' . $e->getMessage());
            flash('error', 'Failed to delete template. It may be in use.');
        }

        $this->redirect(url('admin/templates'));
    }
}
