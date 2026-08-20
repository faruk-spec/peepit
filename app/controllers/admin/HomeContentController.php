<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class HomeContentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show homepage content editor
     */
    public function index()
    {
        require_role('manager');
        
        try {
            // Get all homepage content sections
            $content = $this->db->fetchAll("
                SELECT * FROM home_content 
                ORDER BY section ASC
            ");
            
            // Organize content by section for easier display
            $sections = [];
            foreach ($content as $item) {
                $sections[$item['section']] = $item;
            }
            
            $data = [
                'title' => 'Homepage Content Editor',
                'csrf_token' => $this->generateCSRF(),
                'sections' => $sections
            ];
            
            $this->view('admin/home-content/index', $data);
        } catch (\Exception $e) {
            error_log('Home content load error: ' . $e->getMessage());
            flash('error', 'Failed to load homepage content. Please try again.');
            redirect('/admin/dashboard');
        }
    }

    /**
     * Update homepage content
     */
    public function update()
    {
        require_role('manager');
        $this->validateCSRF();

        try {
            $userId = $_SESSION['user_id'] ?? null;
            $updated = 0;

            // Process each section from POST data
            foreach ($_POST as $key => $value) {
                if ($key === 'csrf_token') continue;
                
                $content = trim($value);
                
                // Update or insert the section
                $existing = $this->db->fetch("SELECT id FROM home_content WHERE section = ?", [$key]);
                
                if ($existing) {
                    $this->db->query("
                        UPDATE home_content 
                        SET content = ?, updated_by = ?
                        WHERE section = ?
                    ", [$content, $userId, $key]);
                } else {
                    $this->db->query("
                        INSERT INTO home_content (section, content, updated_by)
                        VALUES (?, ?, ?)
                    ", [$key, $content, $userId]);
                }
                
                $updated++;
            }

            flash('success', "Homepage content updated successfully! ({$updated} sections)");
            redirect('/admin/home-content');
        } catch (\Exception $e) {
            error_log('Home content update error: ' . $e->getMessage());
            flash('error', 'Failed to update homepage content. Please try again.');
            redirect('/admin/home-content');
        }
    }

    /**
     * Reset a section to default
     */
    public function reset()
    {
        require_role('manager');
        $this->validateCSRF();

        $section = $_POST['section'] ?? '';

        if (empty($section)) {
            flash('error', 'Invalid section.');
            redirect('/admin/home-content');
        }

        try {
            // Default content values
            $defaults = [
                'hero_title' => 'Welcome to Peepit',
                'hero_description' => 'Create Your Perfect Custom Water Bottle',
                'hero_button_text' => 'Get Started',
                'how_it_works_title' => 'How It Works',
                'how_it_works_description' => 'Simple steps to get your custom water bottle',
                'why_choose_title' => 'Why Choose Peepit?',
                'cta_title' => 'Ready to Create Your Custom Bottle?',
                'cta_description' => 'Join thousands of satisfied customers who trust Peepit for their custom water bottle needs',
                'stats_title' => 'Trusted by Thousands'
            ];

            if (!isset($defaults[$section])) {
                flash('error', 'Unknown section.');
                redirect('/admin/home-content');
            }

            $userId = $_SESSION['user_id'] ?? null;
            $defaultContent = $defaults[$section];

            $this->db->query("
                UPDATE home_content 
                SET content = ?, updated_by = ?
                WHERE section = ?
            ", [$defaultContent, $userId, $section]);

            flash('success', 'Section reset to default successfully!');
            redirect('/admin/home-content');
        } catch (\Exception $e) {
            error_log('Home content reset error: ' . $e->getMessage());
            flash('error', 'Failed to reset section. Please try again.');
            redirect('/admin/home-content');
        }
    }
}
