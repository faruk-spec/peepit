<?php

namespace App\Controllers;

use App\Core\Controller;

class PageController extends Controller
{
    /**
     * Display a custom page by slug
     */
    public function show($slug)
    {
        try {
            // Fetch the page from database
            $page = $this->db->fetchOne("
                SELECT * FROM pages 
                WHERE slug = ? AND status = 'published'
            ", [$slug]);
            
            if (!$page) {
                // Page not found
                http_response_code(404);
                $this->view('frontend/404', [
                    'title' => 'Page Not Found',
                    'message' => 'The page you are looking for does not exist.'
                ]);
                return;
            }
            
            // Pass page data to view
            $data = [
                'title' => $page['meta_title'] ?: $page['title'],
                'meta_description' => $page['meta_description'],
                'page' => $page
            ];
            
            $this->view('frontend/page', $data);
            
        } catch (\Exception $e) {
            error_log('Page load error: ' . $e->getMessage());
            http_response_code(500);
            $this->view('frontend/error', [
                'title' => 'Error',
                'message' => 'An error occurred while loading the page.'
            ]);
        }
    }
}
