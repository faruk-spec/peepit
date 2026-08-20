<?php

namespace App\Controllers;

use App\Core\Controller;

class MyOrdersController extends Controller
{
    public function index()
    {
        require_login();
        
        $userId = user_id();
        
        try {
            // Get user's orders
            $orders = $this->db->fetchAll(
                "SELECT o.*, COUNT(oi.id) as item_count 
                 FROM orders o 
                 LEFT JOIN order_items oi ON o.id = oi.order_id 
                 WHERE o.user_id = ? 
                 GROUP BY o.id 
                 ORDER BY o.created_at DESC",
                [$userId]
            );
            
            $this->view('frontend/my-orders', [
                'orders' => $orders
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching orders: ' . $e->getMessage());
            flash('error', 'Unable to load orders. Please try again later.');
            $this->view('frontend/my-orders', ['orders' => []]);
        }
    }
    
    public function viewOrder($orderId)
    {
        require_login();
        
        $userId = user_id();
        
        try {
            // Get order details
            $order = $this->db->fetch(
                "SELECT * FROM orders WHERE id = ? AND user_id = ?",
                [$orderId, $userId]
            );
            
            if (!$order) {
                flash('error', 'Order not found.');
                $this->redirect(url('my-orders'));
            }
            
            // Get order items with model and size details
            $items = $this->db->fetchAll(
                "SELECT oi.*, bm.name as model_name, bs.size as bottle_size 
                 FROM order_items oi 
                 LEFT JOIN bottle_models bm ON oi.bottle_model_id = bm.id 
                 LEFT JOIN bottle_sizes bs ON oi.bottle_size_id = bs.id 
                 WHERE oi.order_id = ?",
                [$orderId]
            );
            
            $this->view('frontend/order-detail', [
                'order' => $order,
                'items' => $items
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching order details: ' . $e->getMessage());
            flash('error', 'Unable to load order details. Please try again later.');
            $this->redirect(url('my-orders'));
        }
    }
}
