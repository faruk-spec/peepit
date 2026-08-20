<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class OrderManagementController extends Controller
{
    public function index()
    {
        require_role('sales');

        try {
            $orders = $this->db->fetchAll(
                "SELECT o.*, u.name as user_name, u.email as user_email 
                 FROM orders o 
                 LEFT JOIN users u ON o.user_id = u.id 
                 ORDER BY o.created_at DESC 
                 LIMIT 100"
            );
            
            $csrfToken = $this->generateCSRF();
            $this->view('admin/orders/index', [
                'csrf_token' => $csrfToken,
                'orders' => $orders
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching orders: ' . $e->getMessage());
            flash('error', 'Unable to load orders.');
            $this->view('admin/orders/index', ['csrf_token' => $this->generateCSRF(), 'orders' => []]);
        }
    }

    public function viewOrder($id)
    {
        require_role('sales');

        try {
            $order = $this->db->fetch(
                "SELECT o.*, u.name as user_name, u.email as user_email, u.phone as user_phone
                 FROM orders o 
                 LEFT JOIN users u ON o.user_id = u.id 
                 WHERE o.id = ?",
                [$id]
            );

            if (!$order) {
                flash('error', 'Order not found');
                $this->redirect(url('admin/orders'));
            }

            $items = $this->db->fetchAll(
                "SELECT oi.*, bm.name as model_name, bs.size as bottle_size 
                 FROM order_items oi 
                 LEFT JOIN bottle_models bm ON oi.bottle_model_id = bm.id 
                 LEFT JOIN bottle_sizes bs ON oi.bottle_size_id = bs.id 
                 WHERE oi.order_id = ?",
                [$id]
            );

            $csrfToken = $this->generateCSRF();
            $this->view('admin/orders/view', [
                'csrf_token' => $csrfToken,
                'order' => $order,
                'items' => $items
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching order details: ' . $e->getMessage());
            flash('error', 'Unable to load order details');
            $this->redirect(url('admin/orders'));
        }
    }

    public function updateStatus($id)
    {
        require_role('sales');
        $this->validateCSRF();

        $status = sanitize($_POST['status'] ?? '');
        $notes = sanitize($_POST['notes'] ?? '');

        if (!in_array($status, ['pending', 'processing', 'completed', 'cancelled'])) {
            flash('error', 'Invalid status');
            $this->redirect(url("admin/orders/{$id}"));
        }

        try {
            $this->db->query(
                "UPDATE orders SET status = ?, notes = ? WHERE id = ?",
                [$status, $notes, $id]
            );

            flash('success', 'Order status updated successfully');
            $this->redirect(url("admin/orders/{$id}"));
        } catch (\Exception $e) {
            error_log('Error updating order status: ' . $e->getMessage());
            flash('error', 'Failed to update order status');
            $this->redirect(url("admin/orders/{$id}"));
        }
    }
}
