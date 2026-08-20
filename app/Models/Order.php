<?php

namespace App\Models;

use App\Core\Model;

class Order extends Model
{
    protected $table = 'orders';

    public function generateOrderNumber()
    {
        return 'ORD-' . date('YmdHis') . '-' . strtoupper(substr(uniqid(), -4));
    }

    public function getUserOrders($userId)
    {
        return $this->db->fetchAll(
            "SELECT o.*, COUNT(oi.id) as item_count FROM orders o LEFT JOIN order_items oi ON o.id = oi.order_id WHERE o.user_id = ? GROUP BY o.id ORDER BY o.created_at DESC",
            [$userId]
        );
    }

    public function getOrderDetails($orderId)
    {
        return $this->db->fetch(
            "SELECT o.*, u.name as user_name, u.email as user_email FROM orders o INNER JOIN users u ON o.user_id = u.id WHERE o.id = ?",
            [$orderId]
        );
    }

    public function getOrderItems($orderId)
    {
        return $this->db->fetchAll(
            "SELECT oi.*, bm.name as model_name, bs.size FROM order_items oi INNER JOIN bottle_models bm ON oi.bottle_model_id = bm.id INNER JOIN bottle_sizes bs ON oi.bottle_size_id = bs.id WHERE oi.order_id = ?",
            [$orderId]
        );
    }
}
