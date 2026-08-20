<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class HeroSliderController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * List all hero slides
     */
    public function index()
    {
        require_role('manager');
        
        try {
            $slides = $this->db->fetchAll("
                SELECT * FROM hero_slides 
                ORDER BY `order` ASC, id DESC
            ");
            
            $data = [
                'title' => 'Hero Slider Management',
                'csrf_token' => $this->generateCSRF(),
                'slides' => $slides
            ];
            
            $this->view('admin/hero-slider/index', $data);
        } catch (\Exception $e) {
            error_log('Hero slider list error: ' . $e->getMessage());
            flash('error', 'Failed to load hero slides. Please try again.');
            redirect('/admin/dashboard');
        }
    }

    /**
     * Show create hero slide form
     */
    public function create()
    {
        require_role('manager');
        
        $data = [
            'title' => 'Create Hero Slide',
            'csrf_token' => $this->generateCSRF()
        ];
        
        $this->view('admin/hero-slider/create', $data);
    }

    /**
     * Store new hero slide
     */
    public function store()
    {
        require_role('manager');
        $this->validateCSRF();

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $image_alt = trim($_POST['image_alt'] ?? '');
        $button_text = trim($_POST['button_text'] ?? '');
        $button_url = trim($_POST['button_url'] ?? '');
        $order = intval($_POST['order'] ?? 0);
        $status = $_POST['status'] ?? 'active';

        // Validate image upload
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            flash('error', 'Image is required.');
            redirect('/admin/hero-slider/create');
        }

        try {
            // Validate image
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $_FILES['image']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, $allowedTypes)) {
                flash('error', 'Invalid image type. Only JPEG, PNG, and WebP are allowed.');
                redirect('/admin/hero-slider/create');
            }

            // Check file size (max 5MB)
            if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                flash('error', 'Image size must be less than 5MB.');
                redirect('/admin/hero-slider/create');
            }

            // Generate unique filename
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = 'hero_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
            $uploadPath = __DIR__ . '/../../../public/uploads/hero/' . $filename;

            // Create directory if it doesn't exist
            $uploadDir = dirname($uploadPath);
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Move uploaded file
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                flash('error', 'Failed to upload image. Please try again.');
                redirect('/admin/hero-slider/create');
            }

            // Insert into database
            $this->db->query("
                INSERT INTO hero_slides (title, description, image, image_alt, button_text, button_url, `order`, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ", [$title, $description, $filename, $image_alt, $button_text, $button_url, $order, $status]);

            flash('success', 'Hero slide created successfully!');
            redirect('/admin/hero-slider');
        } catch (\Exception $e) {
            error_log('Hero slide create error: ' . $e->getMessage());
            
            // Clean up uploaded file if database insert failed
            if (isset($uploadPath) && file_exists($uploadPath)) {
                unlink($uploadPath);
            }
            
            flash('error', 'Failed to create hero slide. Please try again.');
            redirect('/admin/hero-slider/create');
        }
    }

    /**
     * Show edit hero slide form
     */
    public function edit($id)
    {
        require_role('manager');
        
        try {
            $slide = $this->db->fetch("SELECT * FROM hero_slides WHERE id = ?", [$id]);
            
            if (!$slide) {
                flash('error', 'Hero slide not found.');
                redirect('/admin/hero-slider');
            }
            
            $data = [
                'title' => 'Edit Hero Slide',
                'csrf_token' => $this->generateCSRF(),
                'slide' => $slide
            ];
            
            $this->view('admin/hero-slider/edit', $data);
        } catch (\Exception $e) {
            error_log('Hero slide edit load error: ' . $e->getMessage());
            flash('error', 'Failed to load hero slide. Please try again.');
            redirect('/admin/hero-slider');
        }
    }

    /**
     * Update hero slide
     */
    public function update($id)
    {
        require_role('manager');
        $this->validateCSRF();

        try {
            // Get existing slide
            $slide = $this->db->fetch("SELECT * FROM hero_slides WHERE id = ?", [$id]);
            
            if (!$slide) {
                flash('error', 'Hero slide not found.');
                redirect('/admin/hero-slider');
            }

            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $image_alt = trim($_POST['image_alt'] ?? '');
            $button_text = trim($_POST['button_text'] ?? '');
            $button_url = trim($_POST['button_url'] ?? '');
            $order = intval($_POST['order'] ?? 0);
            $status = $_POST['status'] ?? 'active';
            
            $filename = $slide['image']; // Keep existing image by default

            // Check if new image was uploaded
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate new image
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $_FILES['image']['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mimeType, $allowedTypes)) {
                    flash('error', 'Invalid image type. Only JPEG, PNG, and WebP are allowed.');
                    redirect('/admin/hero-slider/edit/' . $id);
                }

                // Check file size (max 5MB)
                if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                    flash('error', 'Image size must be less than 5MB.');
                    redirect('/admin/hero-slider/edit/' . $id);
                }

                // Generate unique filename
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $newFilename = 'hero_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                $uploadPath = __DIR__ . '/../../../public/uploads/hero/' . $newFilename;

                // Move uploaded file
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    // Delete old image
                    $oldImagePath = __DIR__ . '/../../../public/uploads/hero/' . $slide['image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                    
                    $filename = $newFilename;
                } else {
                    flash('error', 'Failed to upload new image. Keeping existing image.');
                }
            }

            // Update database
            $this->db->query("
                UPDATE hero_slides 
                SET title = ?, description = ?, image = ?, image_alt = ?, button_text = ?, button_url = ?, `order` = ?, status = ?
                WHERE id = ?
            ", [$title, $description, $filename, $image_alt, $button_text, $button_url, $order, $status, $id]);

            flash('success', 'Hero slide updated successfully!');
            redirect('/admin/hero-slider');
        } catch (\Exception $e) {
            error_log('Hero slide update error: ' . $e->getMessage());
            flash('error', 'Failed to update hero slide. Please try again.');
            redirect('/admin/hero-slider/edit/' . $id);
        }
    }

    /**
     * Delete hero slide
     */
    public function delete()
    {
        require_role('manager');
        $this->validateCSRF();

        $id = $_POST['id'] ?? 0;

        try {
            // Get slide to delete image
            $slide = $this->db->fetch("SELECT * FROM hero_slides WHERE id = ?", [$id]);
            
            if ($slide) {
                // Delete image file
                $imagePath = __DIR__ . '/../../../public/uploads/hero/' . $slide['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                
                // Delete from database
                $this->db->query("DELETE FROM hero_slides WHERE id = ?", [$id]);
                flash('success', 'Hero slide deleted successfully!');
            } else {
                flash('error', 'Hero slide not found.');
            }
        } catch (\Exception $e) {
            error_log('Hero slide delete error: ' . $e->getMessage());
            flash('error', 'Failed to delete hero slide. Please try again.');
        }

        redirect('/admin/hero-slider');
    }
}
