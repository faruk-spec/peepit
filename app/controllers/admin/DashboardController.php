<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        require_role('sales');

        // Get statistics
        $stats = [
            'total_users' => 0,
            'total_orders' => 0,
            'pending_orders' => 0,
            'total_revenue' => 0,
        ];

        try {
            $stats['total_users'] = $this->db->fetch("SELECT COUNT(*) as count FROM users WHERE role = 'user'")['count'];
            $stats['total_orders'] = $this->db->fetch("SELECT COUNT(*) as count FROM orders")['count'];
            $stats['pending_orders'] = $this->db->fetch("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'")['count'];
            $revenueResult = $this->db->fetch("SELECT SUM(total_amount) as total FROM orders WHERE status != 'cancelled'");
            $stats['total_revenue'] = $revenueResult['total'] ?? 0;
        } catch (\Exception $e) {
            error_log('Dashboard stats error: ' . $e->getMessage());
        }

        // Get recent orders
        $recentOrders = [];
        try {
            $recentOrders = $this->db->fetchAll(
                "SELECT o.*, u.name as user_name FROM orders o INNER JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 10"
            );
        } catch (\Exception $e) {
            error_log('Recent orders error: ' . $e->getMessage());
        }

        $csrfToken = $this->generateCSRF();
        $this->view('admin/dashboard', [
            'csrf_token' => $csrfToken,
            'stats' => $stats,
            'recent_orders' => $recentOrders
        ]);
    }
}
