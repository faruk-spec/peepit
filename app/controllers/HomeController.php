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
        if ($this->db) {
            try {
                $bottleModels = $this->db->fetchAll("SELECT * FROM bottle_models WHERE status = 'active' ORDER BY name");
            } catch (\Exception $e) {
                // Ignore if table doesn't exist yet
            }
        }
        
        $this->view('frontend/home', [
            'csrf_token' => $csrfToken,
            'bottle_models' => $bottleModels
        ]);
    }
}
