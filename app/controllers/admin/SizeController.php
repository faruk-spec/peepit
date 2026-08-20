<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class SizeController extends Controller
{
    public function index()
    {
        require_role('manager');

        try {
            $sizes = $this->db->fetchAll("SELECT * FROM bottle_sizes ORDER BY capacity_ml ASC");
            
            $csrfToken = $this->generateCSRF();
            $this->view('admin/sizes/index', [
                'csrf_token' => $csrfToken,
                'sizes' => $sizes
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching sizes: ' . $e->getMessage());
            flash('error', 'Unable to load bottle sizes.');
            $this->view('admin/sizes/index', ['csrf_token' => $this->generateCSRF(), 'sizes' => []]);
        }
    }

    public function create()
    {
        require_role('manager');

        $csrfToken = $this->generateCSRF();
        $this->view('admin/sizes/create', ['csrf_token' => $csrfToken]);
    }

    public function store()
    {
        require_role('manager');
        $this->validateCSRF();

        $size = sanitize($_POST['size'] ?? '');
        $capacityMl = intval($_POST['capacity_ml'] ?? 0);
        $status = sanitize($_POST['status'] ?? 'active');

        if (empty($size) || $capacityMl <= 0) {
            flash('error', 'Size name and capacity are required');
            set_old($_POST);
            $this->redirect(url('admin/sizes/create'));
        }

        try {
            $this->db->query(
                "INSERT INTO bottle_sizes (size, capacity_ml, status) VALUES (?, ?, ?)",
                [$size, $capacityMl, $status]
            );

            flash('success', 'Bottle size created successfully');
            clear_old();
            $this->redirect(url('admin/sizes'));
        } catch (\Exception $e) {
            error_log('Error creating size: ' . $e->getMessage());
            flash('error', 'Failed to create bottle size');
            set_old($_POST);
            $this->redirect(url('admin/sizes/create'));
        }
    }

    public function edit($id)
    {
        require_role('manager');

        try {
            $size = $this->db->fetch("SELECT * FROM bottle_sizes WHERE id = ?", [$id]);

            if (!$size) {
                flash('error', 'Bottle size not found');
                $this->redirect(url('admin/sizes'));
            }

            $csrfToken = $this->generateCSRF();
            $this->view('admin/sizes/edit', [
                'csrf_token' => $csrfToken,
                'size' => $size
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching size: ' . $e->getMessage());
            flash('error', 'Unable to load bottle size');
            $this->redirect(url('admin/sizes'));
        }
    }

    public function update($id)
    {
        require_role('manager');
        $this->validateCSRF();

        $size = sanitize($_POST['size'] ?? '');
        $capacityMl = intval($_POST['capacity_ml'] ?? 0);
        $status = sanitize($_POST['status'] ?? 'active');

        if (empty($size) || $capacityMl <= 0) {
            flash('error', 'Size name and capacity are required');
            $this->redirect(url("admin/sizes/edit/{$id}"));
        }

        try {
            $this->db->query(
                "UPDATE bottle_sizes SET size = ?, capacity_ml = ?, status = ? WHERE id = ?",
                [$size, $capacityMl, $status, $id]
            );

            flash('success', 'Bottle size updated successfully');
            $this->redirect(url('admin/sizes'));
        } catch (\Exception $e) {
            error_log('Error updating size: ' . $e->getMessage());
            flash('error', 'Failed to update bottle size');
            $this->redirect(url("admin/sizes/edit/{$id}"));
        }
    }

    public function delete($id)
    {
        require_role('manager');
        $this->validateCSRF();

        try {
            $this->db->query("DELETE FROM bottle_sizes WHERE id = ?", [$id]);
            flash('success', 'Bottle size deleted successfully');
        } catch (\Exception $e) {
            error_log('Error deleting size: ' . $e->getMessage());
            flash('error', 'Failed to delete bottle size. It may be in use.');
        }

        $this->redirect(url('admin/sizes'));
    }
}
