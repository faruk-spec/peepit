<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\BottleModel;

class BottleController extends Controller
{
    public function index()
    {
        require_role('manager');

        $bottleModel = new BottleModel();
        $bottles = $bottleModel->all();

        $csrfToken = $this->generateCSRF();
        $this->view('admin/bottles/index', [
            'csrf_token' => $csrfToken,
            'bottles' => $bottles
        ]);
    }

    public function create()
    {
        require_role('manager');

        $csrfToken = $this->generateCSRF();
        $this->view('admin/bottles/create', ['csrf_token' => $csrfToken]);
    }

    public function store()
    {
        require_role('manager');
        $this->validateCSRF();

        $name = sanitize($_POST['name'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');

        if (empty($name)) {
            flash('error', 'Bottle name is required');
            set_old($_POST);
            $this->redirect(url('admin/bottles/create'));
        }

        // Handle image upload
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['image'], __DIR__ . '/../../../public/uploads/bottles');
            if (isset($upload['success'])) {
                $image = $upload['filename'];
            } else {
                $errorMsg = isset($upload['error']) ? $upload['error'] : 'Image upload failed';
                flash('error', 'Image Upload Error: ' . $errorMsg);
                set_old($_POST);
                $this->redirect(url('admin/bottles/create'));
            }
        } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Handle other upload errors
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File too large (exceeds server limit)',
                UPLOAD_ERR_FORM_SIZE => 'File too large (exceeds form limit)', 
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'File upload stopped by extension',
            ];
            $errorMsg = isset($errorMessages[$_FILES['image']['error']]) 
                ? $errorMessages[$_FILES['image']['error']] 
                : 'Unknown upload error (code: ' . $_FILES['image']['error'] . ')';
            flash('error', 'Image Upload Error: ' . $errorMsg);
            set_old($_POST);
            $this->redirect(url('admin/bottles/create'));
        }

        try {
            $bottleModel = new BottleModel();
            $bottleModel->create([
                'name' => $name,
                'description' => $description,
                'image' => $image,
                'status' => $status
            ]);

            flash('success', 'Bottle model created successfully');
            clear_old();
            $this->redirect(url('admin/bottles'));
        } catch (\Exception $e) {
            flash('error', 'Failed to create bottle model');
            set_old($_POST);
            $this->redirect(url('admin/bottles/create'));
        }
    }

    public function edit($id)
    {
        require_role('manager');

        $bottleModel = new BottleModel();
        $bottle = $bottleModel->find($id);

        if (!$bottle) {
            flash('error', 'Bottle model not found');
            $this->redirect(url('admin/bottles'));
        }

        $csrfToken = $this->generateCSRF();
        $this->view('admin/bottles/edit', [
            'csrf_token' => $csrfToken,
            'bottle' => $bottle
        ]);
    }

    public function update($id)
    {
        require_role('manager');
        $this->validateCSRF();

        $bottleModel = new BottleModel();
        $bottle = $bottleModel->find($id);

        if (!$bottle) {
            flash('error', 'Bottle model not found');
            $this->redirect(url('admin/bottles'));
        }

        $name = sanitize($_POST['name'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');

        if (empty($name)) {
            flash('error', 'Bottle name is required');
            $this->redirect(url("admin/bottles/edit/{$id}"));
        }

        $image = $bottle['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['image'], __DIR__ . '/../../../public/uploads/bottles');
            if (isset($upload['success'])) {
                // Delete old image
                if ($image && file_exists(__DIR__ . "/../../../public/uploads/bottles/{$image}")) {
                    unlink(__DIR__ . "/../../../public/uploads/bottles/{$image}");
                }
                $image = $upload['filename'];
            } else {
                $errorMsg = isset($upload['error']) ? $upload['error'] : 'Image upload failed';
                flash('error', 'Image Upload Error: ' . $errorMsg);
                $this->redirect(url("admin/bottles/edit/{$id}"));
            }
        } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Handle other upload errors
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File too large (exceeds server limit)',
                UPLOAD_ERR_FORM_SIZE => 'File too large (exceeds form limit)',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'File upload stopped by extension',
            ];
            $errorMsg = isset($errorMessages[$_FILES['image']['error']])
                ? $errorMessages[$_FILES['image']['error']]
                : 'Unknown upload error (code: ' . $_FILES['image']['error'] . ')';
            flash('error', 'Image Upload Error: ' . $errorMsg);
            $this->redirect(url("admin/bottles/edit/{$id}"));
        }

        try {
            $bottleModel->update($id, [
                'name' => $name,
                'description' => $description,
                'image' => $image,
                'status' => $status
            ]);

            flash('success', 'Bottle model updated successfully');
            $this->redirect(url('admin/bottles'));
        } catch (\Exception $e) {
            flash('error', 'Failed to update bottle model');
            $this->redirect(url("admin/bottles/edit/{$id}"));
        }
    }

    public function delete($id)
    {
        require_role('manager');
        $this->validateCSRF();

        $bottleModel = new BottleModel();
        $bottle = $bottleModel->find($id);

        if (!$bottle) {
            flash('error', 'Bottle model not found');
            $this->redirect(url('admin/bottles'));
        }

        try {
            // Delete image
            if ($bottle['image'] && file_exists(__DIR__ . "/../../../public/uploads/bottles/{$bottle['image']}")) {
                unlink(__DIR__ . "/../../../public/uploads/bottles/{$bottle['image']}");
            }

            $bottleModel->delete($id);
            flash('success', 'Bottle model deleted successfully');
        } catch (\Exception $e) {
            flash('error', 'Failed to delete bottle model. It may be in use.');
        }

        $this->redirect(url('admin/bottles'));
    }
}
