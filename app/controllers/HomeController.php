<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $csrfToken = $this->generateCSRF();
        
        // Get active bottle models
        $bottleModels = [];
        $heroSlides = [];
        $homeContent = [];
        
        if ($this->db) {
            try {
                $bottleModels = $this->db->fetchAll("SELECT * FROM bottle_models WHERE status = 'active' ORDER BY name");
                
                // Get active hero slides
                $heroSlides = $this->db->fetchAll("
                    SELECT * FROM hero_slides 
                    WHERE status = 'active' 
                    ORDER BY `order` ASC, id DESC
                ");
                
                // Get homepage content
                $contentRows = $this->db->fetchAll("SELECT * FROM home_content");
                foreach ($contentRows as $row) {
                    $homeContent[$row['section']] = $row['content'];
                }
            } catch (\Exception $e) {
                // Ignore if tables don't exist yet
                error_log('Homepage data load error: ' . $e->getMessage());
            }
        }
        
        $this->view('frontend/home', [
            'csrf_token' => $csrfToken,
            'bottle_models' => $bottleModels,
            'hero_slides' => $heroSlides,
            'home_content' => $homeContent
        ]);
    }
}
