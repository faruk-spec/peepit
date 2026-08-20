<?php

namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        require_login();
        
        $userId = user_id();
        $user = current_user();
        
        try {
            // Get user statistics
            $stats = [
                'total_orders' => 0,
                'pending_orders' => 0,
                'completed_orders' => 0,
                'total_spent' => 0
            ];
            
            // Total orders count
            $orderCount = $this->db->fetch("
                SELECT COUNT(*) as count FROM orders WHERE user_id = ?
            ", [$userId]);
            $stats['total_orders'] = $orderCount['count'] ?? 0;
            
            // Pending orders (not completed)
            $pendingCount = $this->db->fetch("
                SELECT COUNT(*) as count FROM orders 
                WHERE user_id = ? AND status NOT IN ('completed', 'cancelled')
            ", [$userId]);
            $stats['pending_orders'] = $pendingCount['count'] ?? 0;
            
            // Completed orders
            $completedCount = $this->db->fetch("
                SELECT COUNT(*) as count FROM orders 
                WHERE user_id = ? AND status = 'completed'
            ", [$userId]);
            $stats['completed_orders'] = $completedCount['count'] ?? 0;
            
            // Total amount spent
            $totalSpent = $this->db->fetch("
                SELECT SUM(total_amount) as total FROM orders 
                WHERE user_id = ? AND status != 'cancelled'
            ", [$userId]);
            $stats['total_spent'] = $totalSpent['total'] ?? 0;
            
            // Get recent orders (last 5)
            $recentOrders = $this->db->fetchAll("
                SELECT o.*, COUNT(oi.id) as item_count 
                FROM orders o 
                LEFT JOIN order_items oi ON o.id = oi.order_id 
                WHERE o.user_id = ? 
                GROUP BY o.id 
                ORDER BY o.created_at DESC 
                LIMIT 5
            ", [$userId]);
            
            $this->view('frontend/dashboard', [
                'user' => $user,
                'stats' => $stats,
                'recent_orders' => $recentOrders
            ]);
        } catch (\Exception $e) {
            error_log('Dashboard error: ' . $e->getMessage());
            flash('error', 'Unable to load dashboard. Please try again later.');
            redirect('/');
        }
    }
}
