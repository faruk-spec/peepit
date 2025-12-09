<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class AnalyticsController extends Controller
{
    public function index()
    {
        require_role('manager');

        try {
            // Get order statistics
            $totalOrders = $this->db->fetch("SELECT COUNT(*) as count FROM orders")['count'] ?? 0;
            $pendingOrders = $this->db->fetch("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'")['count'] ?? 0;
            $completedOrders = $this->db->fetch("SELECT COUNT(*) as count FROM orders WHERE status = 'completed'")['count'] ?? 0;
            
            // Get revenue data
            $totalRevenue = $this->db->fetch("SELECT SUM(total_amount) as total FROM orders WHERE status = 'completed'")['total'] ?? 0;
            $monthlyRevenue = $this->db->fetch("SELECT SUM(total_amount) as total FROM orders WHERE status = 'completed' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")['total'] ?? 0;
            
            // Get customer statistics
            $totalCustomers = $this->db->fetch("SELECT COUNT(*) as count FROM users WHERE role = 'customer'")['count'] ?? 0;
            $newCustomersThisMonth = $this->db->fetch("SELECT COUNT(*) as count FROM users WHERE role = 'customer' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")['count'] ?? 0;
            
            // Get recent orders
            $recentOrders = $this->db->fetchAll(
                "SELECT o.*, u.name as customer_name 
                 FROM orders o 
                 LEFT JOIN users u ON o.user_id = u.id 
                 ORDER BY o.created_at DESC 
                 LIMIT 10"
            );
            
            // Get top selling products
            $topProducts = $this->db->fetchAll(
                "SELECT bm.name, COUNT(oi.id) as order_count, SUM(oi.quantity) as total_quantity
                 FROM order_items oi
                 LEFT JOIN bottle_models bm ON oi.bottle_model_id = bm.id
                 GROUP BY oi.bottle_model_id
                 ORDER BY order_count DESC
                 LIMIT 5"
            );
            
            // Get monthly order trend (last 6 months)
            $monthlyTrend = $this->db->fetchAll(
                "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as order_count,
                    SUM(total_amount) as revenue
                 FROM orders
                 WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)
                 GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                 ORDER BY month ASC"
            );
            
            $csrfToken = $this->generateCSRF();
            $this->view('admin/analytics/index', [
                'csrf_token' => $csrfToken,
                'stats' => [
                    'total_orders' => $totalOrders,
                    'pending_orders' => $pendingOrders,
                    'completed_orders' => $completedOrders,
                    'total_revenue' => $totalRevenue,
                    'monthly_revenue' => $monthlyRevenue,
                    'total_customers' => $totalCustomers,
                    'new_customers' => $newCustomersThisMonth
                ],
                'recent_orders' => $recentOrders,
                'top_products' => $topProducts,
                'monthly_trend' => $monthlyTrend
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching analytics: ' . $e->getMessage());
            flash('error', 'Unable to load analytics data.');
            $this->view('admin/analytics/index', [
                'csrf_token' => $this->generateCSRF(),
                'stats' => [],
                'recent_orders' => [],
                'top_products' => [],
                'monthly_trend' => []
            ]);
        }
    }
}
