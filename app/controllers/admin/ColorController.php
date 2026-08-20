<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class ColorController extends Controller
{
    public function index()
    {
        require_role('manager');

        try {
            $colors = $this->db->fetchAll("SELECT * FROM color_presets ORDER BY name ASC");
            
            $csrfToken = $this->generateCSRF();
            $this->view('admin/colors/index', [
                'csrf_token' => $csrfToken,
                'colors' => $colors
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching colors: ' . $e->getMessage());
            flash('error', 'Unable to load color presets.');
            $this->view('admin/colors/index', ['csrf_token' => $this->generateCSRF(), 'colors' => []]);
        }
    }

    public function create()
    {
        require_role('manager');

        $csrfToken = $this->generateCSRF();
        $this->view('admin/colors/create', ['csrf_token' => $csrfToken]);
    }

    public function store()
    {
        require_role('manager');
        $this->validateCSRF();

        $name = sanitize($_POST['name'] ?? '');
        $hexCode = sanitize($_POST['hex_code'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');

        if (empty($name) || empty($hexCode)) {
            flash('error', 'Color name and hex code are required');
            set_old($_POST);
            $this->redirect(url('admin/colors/create'));
        }

        // Validate hex code
        if (!preg_match('/^#[0-9A-F]{6}$/i', $hexCode)) {
            flash('error', 'Invalid hex code format. Use #RRGGBB format.');
            set_old($_POST);
            $this->redirect(url('admin/colors/create'));
        }

        try {
            // Convert hex to RGB
            $r = hexdec(substr($hexCode, 1, 2));
            $g = hexdec(substr($hexCode, 3, 2));
            $b = hexdec(substr($hexCode, 5, 2));
            $rgbCode = "rgb($r,$g,$b)";

            $this->db->query(
                "INSERT INTO color_presets (name, hex_code, rgb_code, status) VALUES (?, ?, ?, ?)",
                [$name, $hexCode, $rgbCode, $status]
            );

            flash('success', 'Color preset created successfully');
            clear_old();
            $this->redirect(url('admin/colors'));
        } catch (\Exception $e) {
            error_log('Error creating color: ' . $e->getMessage());
            flash('error', 'Failed to create color preset');
            set_old($_POST);
            $this->redirect(url('admin/colors/create'));
        }
    }

    public function edit($id)
    {
        require_role('manager');

        try {
            $color = $this->db->fetch("SELECT * FROM color_presets WHERE id = ?", [$id]);

            if (!$color) {
                flash('error', 'Color preset not found');
                $this->redirect(url('admin/colors'));
            }

            $csrfToken = $this->generateCSRF();
            $this->view('admin/colors/edit', [
                'csrf_token' => $csrfToken,
                'color' => $color
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching color: ' . $e->getMessage());
            flash('error', 'Unable to load color preset');
            $this->redirect(url('admin/colors'));
        }
    }

    public function update($id)
    {
        require_role('manager');
        $this->validateCSRF();

        $name = sanitize($_POST['name'] ?? '');
        $hexCode = sanitize($_POST['hex_code'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');

        if (empty($name) || empty($hexCode)) {
            flash('error', 'Color name and hex code are required');
            $this->redirect(url("admin/colors/edit/{$id}"));
        }

        if (!preg_match('/^#[0-9A-F]{6}$/i', $hexCode)) {
            flash('error', 'Invalid hex code format');
            $this->redirect(url("admin/colors/edit/{$id}"));
        }

        try {
            $r = hexdec(substr($hexCode, 1, 2));
            $g = hexdec(substr($hexCode, 3, 2));
            $b = hexdec(substr($hexCode, 5, 2));
            $rgbCode = "rgb($r,$g,$b)";

            $this->db->query(
                "UPDATE color_presets SET name = ?, hex_code = ?, rgb_code = ?, status = ? WHERE id = ?",
                [$name, $hexCode, $rgbCode, $status, $id]
            );

            flash('success', 'Color preset updated successfully');
            $this->redirect(url('admin/colors'));
        } catch (\Exception $e) {
            error_log('Error updating color: ' . $e->getMessage());
            flash('error', 'Failed to update color preset');
            $this->redirect(url("admin/colors/edit/{$id}"));
        }
    }

    public function delete($id)
    {
        require_role('manager');
        $this->validateCSRF();

        try {
            $this->db->query("DELETE FROM color_presets WHERE id = ?", [$id]);
            flash('success', 'Color preset deleted successfully');
        } catch (\Exception $e) {
            error_log('Error deleting color: ' . $e->getMessage());
            flash('error', 'Failed to delete color preset. It may be in use.');
        }

        $this->redirect(url('admin/colors'));
    }
}
