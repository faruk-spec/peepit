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
            
            // Get top customers
            $topCustomers = $this->db->fetchAll(
                "SELECT u.name, COUNT(o.id) as order_count, SUM(o.total_amount) as total_spent
                 FROM users u
                 LEFT JOIN orders o ON u.id = o.user_id
                 WHERE u.role = 'customer' AND o.status = 'completed'
                 GROUP BY u.id
                 ORDER BY total_spent DESC
                 LIMIT 10"
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
                'top_customers' => $topCustomers,
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
                'top_customers' => [],
                'monthly_trend' => []
            ]);
        }
    }
    
    public function export()
    {
        require_role('manager');
        
        $format = $_GET['format'] ?? 'csv';
        
        try {
            // Get comprehensive analytics data
            $orders = $this->db->fetchAll(
                "SELECT o.*, u.name as customer_name, u.email as customer_email
                 FROM orders o
                 LEFT JOIN users u ON o.user_id = u.id
                 ORDER BY o.created_at DESC"
            );
            
            if ($format === 'csv') {
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="analytics_export_' . date('Y-m-d') . '.csv"');
                
                $output = fopen('php://output', 'w');
                
                // Write headers
                fputcsv($output, ['Order ID', 'Customer Name', 'Customer Email', 'Status', 'Total Amount', 'Created Date']);
                
                // Write data
                foreach ($orders as $order) {
                    fputcsv($output, [
                        $order['id'],
                        $order['customer_name'],
                        $order['customer_email'],
                        $order['status'],
                        $order['total_amount'],
                        $order['created_at']
                    ]);
                }
                
                fclose($output);
                exit;
            } elseif ($format === 'pdf') {
                // Simple PDF generation (basic HTML to PDF)
                header('Content-Type: text/html');
                echo '<!DOCTYPE html>
                <html>
                <head>
                    <title>Analytics Report</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #0EA5E9; color: white; }
                        h1 { color: #0EA5E9; }
                    </style>
                </head>
                <body>
                    <h1>Analytics Report - ' . date('F d, Y') . '</h1>
                    <table>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>';
                
                foreach ($orders as $order) {
                    echo '<tr>
                        <td>' . htmlspecialchars($order['id']) . '</td>
                        <td>' . htmlspecialchars($order['customer_name']) . '</td>
                        <td>' . htmlspecialchars($order['customer_email']) . '</td>
                        <td>' . htmlspecialchars($order['status']) . '</td>
                        <td>$' . number_format($order['total_amount'], 2) . '</td>
                        <td>' . date('M d, Y', strtotime($order['created_at'])) . '</td>
                    </tr>';
                }
                
                echo '</table>
                    <script>window.print();</script>
                </body>
                </html>';
                exit;
            }
        } catch (\Exception $e) {
            error_log('Error exporting analytics: ' . $e->getMessage());
            flash('error', 'Unable to export data.');
            redirect('admin/analytics');
        }
    }
}
