<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        require_role('manager');

        $galleryModel = new Gallery();
        $images = $galleryModel->getAll();

        $csrfToken = $this->generateCSRF();
        $this->view('admin/gallery/index', [
            'csrf_token' => $csrfToken,
            'images' => $images
        ]);
    }

    public function create()
    {
        require_role('manager');

        $galleryModel = new Gallery();
        $maxPriority = $galleryModel->getMaxPriority();

        $csrfToken = $this->generateCSRF();
        $this->view('admin/gallery/create', [
            'csrf_token' => $csrfToken,
            'next_priority' => $maxPriority + 1
        ]);
    }

    public function store()
    {
        require_role('manager');
        $this->validateCSRF();

        $galleryModel = new Gallery();
        $uploadedCount = 0;
        $errors = [];

        // Handle multiple file uploads
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $files = $_FILES['images'];
            $fileCount = count($files['name']);

            for ($i = 0; $i < $fileCount; $i++) {
                // Skip if file has error
                if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                    continue;
                }

                // Create file array for single file
                $file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];

                // Validate image type
                $allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file['type'], $allowed)) {
                    $errors[] = "File {$file['name']} is not a valid image type";
                    continue;
                }

                // Validate image dimensions (1:1 aspect ratio)
                list($width, $height) = getimagesize($file['tmp_name']);
                $aspectRatio = $width / $height;
                if (abs($aspectRatio - 1) > 0.1) { // Allow 10% tolerance
                    $errors[] = "Image {$file['name']} must be square (1:1 aspect ratio). Current: {$width}x{$height}";
                    continue;
                }

                // Upload directory
                $uploadDir = __DIR__ . '/../../../public/uploads/gallery';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Generate unique filename
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = uniqid('gallery_') . '_' . time() . '.' . $extension;
                $uploadPath = $uploadDir . '/' . $filename;

                // Move uploaded file
                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    // Get caption and description from form
                    $caption = sanitize($_POST['caption'] ?? '');
                    $description = sanitize($_POST['description'] ?? '');
                    $priority = (int)($_POST['priority'] ?? 0) + $i;
                    $isEnabled = isset($_POST['is_enabled']) ? 1 : 0;

                    // Save to database
                    $data = [
                        'image_path' => $filename,
                        'caption' => $caption,
                        'description' => $description,
                        'priority' => $priority,
                        'is_enabled' => $isEnabled
                    ];

                    if ($galleryModel->create($data)) {
                        $uploadedCount++;
                    } else {
                        $errors[] = "Failed to save {$file['name']} to database";
                        unlink($uploadPath); // Remove uploaded file if DB insert fails
                    }
                } else {
                    $errors[] = "Failed to upload {$file['name']}";
                }
            }
        } else {
            flash('error', 'Please select at least one image to upload');
            $this->redirect(url('admin/gallery/create'));
            return;
        }

        // Set flash messages
        if ($uploadedCount > 0) {
            flash('success', "$uploadedCount image(s) uploaded successfully");
        }
        if (!empty($errors)) {
            flash('error', implode('<br>', $errors));
        }

        $this->redirect(url('admin/gallery'));
    }

    public function edit($id)
    {
        require_role('manager');

        $galleryModel = new Gallery();
        $image = $galleryModel->getById($id);

        if (!$image) {
            flash('error', 'Image not found');
            $this->redirect(url('admin/gallery'));
            return;
        }

        $csrfToken = $this->generateCSRF();
        $this->view('admin/gallery/edit', [
            'csrf_token' => $csrfToken,
            'image' => $image
        ]);
    }

    public function update($id)
    {
        require_role('manager');
        $this->validateCSRF();

        $galleryModel = new Gallery();
        $image = $galleryModel->getById($id);

        if (!$image) {
            flash('error', 'Image not found');
            $this->redirect(url('admin/gallery'));
            return;
        }

        $caption = sanitize($_POST['caption'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $priority = (int)($_POST['priority'] ?? 0);
        $isEnabled = isset($_POST['is_enabled']) ? 1 : 0;

        $data = [
            'caption' => $caption,
            'description' => $description,
            'priority' => $priority,
            'is_enabled' => $isEnabled
        ];

        if ($galleryModel->update($id, $data)) {
            flash('success', 'Image updated successfully');
        } else {
            flash('error', 'Failed to update image');
        }

        $this->redirect(url('admin/gallery'));
    }

    public function delete($id)
    {
        require_role('manager');
        $this->validateCSRF();

        $galleryModel = new Gallery();
        
        if ($galleryModel->delete($id)) {
            flash('success', 'Image deleted successfully');
        } else {
            flash('error', 'Failed to delete image');
        }

        $this->redirect(url('admin/gallery'));
    }

    public function toggle($id)
    {
        require_role('manager');
        $this->validateCSRF();

        $galleryModel = new Gallery();
        
        if ($galleryModel->toggleStatus($id)) {
            flash('success', 'Image status toggled successfully');
        } else {
            flash('error', 'Failed to toggle image status');
        }

        $this->redirect(url('admin/gallery'));
    }
}
